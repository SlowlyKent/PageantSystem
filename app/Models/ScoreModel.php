<?php

namespace App\Models;

use CodeIgniter\Model;

class ScoreModel extends Model
{
    protected $table            = 'scores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judge_id',
        'contestant_id',
        'round_id',
        'segment_id',
        'criteria_id',
        'score',
        'remarks'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all scores by a specific judge for a round
     */
    public function getJudgeScoresForRound($judgeId, $roundId)
    {
        return $this->where('judge_id', $judgeId)
                    ->where('round_id', $roundId)
                    ->findAll();
    }

    /**
     * Get scores for a specific contestant in a round
     */
    public function getContestantScoresInRound($contestantId, $roundId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('scores')
            ->select('scores.*, criteria.criteria_name, criteria.percentage, 
                     round_segments.segment_name, users.full_name as judge_name')
            ->join('criteria', 'criteria.id = scores.criteria_id')
            ->join('round_segments', 'round_segments.id = scores.segment_id')
            ->join('users', 'users.id = scores.judge_id')
            ->where('scores.contestant_id', $contestantId)
            ->where('scores.round_id', $roundId)
            ->get()
            ->getResultArray();
    }

    /**
     * Calculate total score for a contestant in a round
     */
    public function calculateContestantRoundScore($contestantId, $roundId)
    {
        $db = \Config\Database::connect();
        
        // Get all scores with criteria percentages and segment weights
        $scores = $db->table('scores')
            ->select('scores.score, criteria.percentage as criteria_percentage, 
                     criteria.max_score, round_segments.weight_percentage as segment_weight')
            ->join('criteria', 'criteria.id = scores.criteria_id')
            ->join('round_segments', 'round_segments.id = scores.segment_id')
            ->where('scores.contestant_id', $contestantId)
            ->where('scores.round_id', $roundId)
            ->get()
            ->getResultArray();

        if (empty($scores)) {
            return 0;
        }

        // Calculate weighted score
        $totalScore = 0;
        foreach ($scores as $score) {
            // Normalize score to percentage of max score
            $normalizedScore = ($score['score'] / $score['max_score']) * 100;
            
            // Apply criteria percentage (within segment)
            $criteriaWeighted = $normalizedScore * ($score['criteria_percentage'] / 100);
            
            // Apply segment weight (within round)
            $finalWeighted = $criteriaWeighted * ($score['segment_weight'] / 100);
            
            $totalScore += $finalWeighted;
        }

        return round($totalScore, 2);
    }

    /**
     * Get rankings for a round (averaged across all judges)
     */
    public function getRoundRankings($roundId)
    {
        $db = \Config\Database::connect();
        $contestantModel = new \App\Models\ContestantModel();
        
        // Get all contestants
        $contestants = $contestantModel->where('status', 'active')->findAll();
        $rankings = [];

        foreach ($contestants as $contestant) {
            // Get all judges who scored this contestant in this round
            $judgeScores = $db->table('scores')
                ->select('DISTINCT judge_id')
                ->where('contestant_id', $contestant['id'])
                ->where('round_id', $roundId)
                ->get()
                ->getResultArray();

            $judgeCount = count($judgeScores);
            
            if ($judgeCount > 0) {
                $totalScore = 0;
                
                // Calculate score from each judge
                foreach ($judgeScores as $judgeScore) {
                    $judgeId = $judgeScore['judge_id'];
                    $score = $this->calculateJudgeScoreForContestant($judgeId, $contestant['id'], $roundId);
                    $totalScore += $score;
                }
                
                // Average across all judges
                $averageScore = $totalScore / $judgeCount;
                
                $rankings[] = [
                    'contestant_id' => $contestant['id'],
                    'contestant_number' => $contestant['contestant_number'],
                    'contestant_name' => $contestant['first_name'] . ' ' . $contestant['last_name'],
                    'total_score' => round($averageScore, 2),
                    'judge_count' => $judgeCount,
                ];
            }
        }

        // Sort by total_score descending
        usort($rankings, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Add rank
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
        }

        return $rankings;
    }

    /**
     * Calculate score from a specific judge for a contestant in a round
     */
    private function calculateJudgeScoreForContestant($judgeId, $contestantId, $roundId)
    {
        $db = \Config\Database::connect();
        
        $scores = $db->table('scores')
            ->select('scores.score, criteria.percentage as criteria_percentage, 
                     criteria.max_score, round_segments.weight_percentage as segment_weight')
            ->join('criteria', 'criteria.id = scores.criteria_id')
            ->join('round_segments', 'round_segments.id = scores.segment_id')
            ->where('scores.judge_id', $judgeId)
            ->where('scores.contestant_id', $contestantId)
            ->where('scores.round_id', $roundId)
            ->get()
            ->getResultArray();

        $totalScore = 0;
        foreach ($scores as $score) {
            $normalizedScore = ($score['score'] / $score['max_score']) * 100;
            $criteriaWeighted = $normalizedScore * ($score['criteria_percentage'] / 100);
            $finalWeighted = $criteriaWeighted * ($score['segment_weight'] / 100);
            $totalScore += $finalWeighted;
        }

        return $totalScore;
    }

    /**
     * Check if judge has completed scoring for a contestant in a round
     */
    public function hasJudgeCompletedScoring($judgeId, $contestantId, $roundId)
    {
        $db = \Config\Database::connect();
        
        // Get total criteria count for this round
        $totalCriteria = $db->table('criteria')
            ->join('round_segments', 'round_segments.id = criteria.segment_id')
            ->where('round_segments.round_id', $roundId)
            ->countAllResults();

        // Get scored criteria count
        $scoredCriteria = $this->where('judge_id', $judgeId)
            ->where('contestant_id', $contestantId)
            ->where('round_id', $roundId)
            ->countAllResults();

        return $totalCriteria === $scoredCriteria && $totalCriteria > 0;
    }
}
