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
        'round_criteria_id',
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
            ->select('scores.*, rc.criteria_name, rc.percentage, rc.max_score, users.full_name as judge_name')
            ->join('round_criteria rc', 'rc.id = scores.round_criteria_id')
            ->join('users', 'users.id = scores.judge_id')
            ->where('scores.contestant_id', $contestantId)
            ->where('scores.round_id', $roundId)
            ->orderBy('rc.order', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Calculate total score for a contestant in a round
     * 
     * Since each criterion defines its own maximum, we simply sum up the raw scores.
     */
    public function calculateContestantRoundScore($contestantId, $roundId)
    {
        $db = \Config\Database::connect();
        
        // Get all scores for the contestant in this round
        $scores = $db->table('scores')
            ->select('scores.score')
            ->where('scores.contestant_id', $contestantId)
            ->where('scores.round_id', $roundId)
            ->get()
            ->getResultArray();

        if (empty($scores)) {
            return 0;
        }

        // Sum all raw scores
        $totalScore = 0;
        foreach ($scores as $score) {
            $totalScore += $score['score'];
        }

        return round($totalScore, 2);
    }

    /**
     * Get rankings for a round (averaged across all judges)
     */
    public function getRoundRankings($roundId)
    {
        $db = \Config\Database::connect();

        // Fetch all scoring rows with weight metadata
        $scores = $db->table('scores s')
            ->select('s.contestant_id, s.judge_id, s.score, rc.max_score AS criteria_max, rc.percentage AS criteria_weight, contestants.contestant_number, contestants.first_name, contestants.last_name')
            ->join('round_criteria rc', 'rc.id = s.round_criteria_id')
            ->join('contestants', 'contestants.id = s.contestant_id')
            ->where('s.round_id', $roundId)
            ->get()
            ->getResultArray();

        if (empty($scores)) {
            return [];
        }

        // Organize weighted sum per judge per contestant
        $contestantJudgeScores = [];
        foreach ($scores as $row) {
            $contestantId = (int)$row['contestant_id'];
            $judgeId = (int)$row['judge_id'];
            $criteriaWeight = (float)$row['criteria_weight'];
            $criteriaMax = (float)$row['criteria_max'];
            $scoreValue = (float)$row['score'];

            // Normalize to percentage of criterion and apply weight
            $normalized = $criteriaMax > 0 ? ($scoreValue / $criteriaMax) : 0;
            $weightedScore = $normalized * $criteriaWeight;

            if (!isset($contestantJudgeScores[$contestantId])) {
                $contestantJudgeScores[$contestantId] = [
                    'contestant_number' => $row['contestant_number'],
                    'contestant_name' => trim($row['first_name'] . ' ' . $row['last_name']),
                    'judges' => [],
                ];
            }

            if (!isset($contestantJudgeScores[$contestantId]['judges'][$judgeId])) {
                $contestantJudgeScores[$contestantId]['judges'][$judgeId] = 0;
            }

            $contestantJudgeScores[$contestantId]['judges'][$judgeId] += $weightedScore;
        }

        $rankings = [];

        foreach ($contestantJudgeScores as $contestantId => $data) {
            $judgeScores = $data['judges'];
            $judgeCount = count($judgeScores);
            if ($judgeCount === 0) {
                continue;
            }

            $total = array_sum($judgeScores);
            $averageScore = $total / $judgeCount;

            $stateRow = $db->table('round_contestants')
                ->select('state')
                ->where('round_id', $roundId)
                ->where('contestant_id', $contestantId)
                ->get()
                ->getRowArray();

            $rankings[] = [
                'contestant_id' => $contestantId,
                'contestant_number' => $data['contestant_number'],
                'contestant_name' => $data['contestant_name'],
                'total_score' => round($averageScore, 2),
                'judge_count' => $judgeCount,
                'state' => $stateRow['state'] ?? 'active',
            ];
        }

        // Sort by score desc
        usort($rankings, static function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
        }

        return $rankings;
    }

    /**
     * Check if judge has completed scoring for a contestant in a round
     */
    public function hasJudgeCompletedScoring($judgeId, $contestantId, $roundId)
    {
        $db = \Config\Database::connect();
        
        // Get total criteria count for this round
        $totalCriteria = $db->table('round_criteria')
            ->where('round_id', $roundId)
            ->countAllResults();

        // Get scored criteria count
        $scoredCriteria = $this->where('judge_id', $judgeId)
            ->where('contestant_id', $contestantId)
            ->where('round_id', $roundId)
            ->countAllResults();

        return $totalCriteria === $scoredCriteria && $totalCriteria > 0;
    }

    /**
     * Get judge completion statistics for a round
     */
    public function getJudgeCompletionForRound($roundId)
    {
        $db = \Config\Database::connect();
        
        // Get all judges assigned to this round
        $judges = $db->table('round_judges')
            ->select('round_judges.*')
            ->where('round_judges.round_id', $roundId)
            ->get()
            ->getResultArray();
        
        $total = count($judges);
        $completed = 0;
        
        foreach ($judges as $judge) {
            if (!empty($judge['is_completed'])) {
                $completed++;
            }
        }
        
        $percentage = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
        
        return [
            'total' => $total,
            'completed' => $completed,
            'percentage' => $percentage
        ];
    }
    
    /**
     * Get top N contestants by total score for a round
     */
    public function getTopContestantsByRound($roundId, $limit = 3)
    {
        $db = \Config\Database::connect();
        
        // Get total scores for all contestants in this round
        $rankings = $this->getRoundRankings($roundId);
        $top = array_slice($rankings, 0, $limit);

        foreach ($top as $index => &$result) {
            $result['rank'] = $index + 1;
            $contestant = $db->table('contestants')->select('profile_picture')->where('id', $result['contestant_id'])->get()->getRowArray();
            if ($contestant && !empty($contestant['profile_picture'])) {
                $result['photo_url'] = base_url('uploads/contestants/' . $contestant['profile_picture']);
            } else {
                $result['photo_url'] = null;
            }
        }

        return $top;
    }
}
