<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\RoundModel;
use App\Models\ContestantModel;
use App\Models\ScoreModel;

class ResultsController extends BaseController
{
    protected $roundModel;
    protected $contestantModel;
    protected $scoreModel;

    public function __construct()
    {
        $this->roundModel = new RoundModel();
        $this->contestantModel = new ContestantModel();
        $this->scoreModel = new ScoreModel();
    }

    /**
     * Display results dashboard
     */
    public function index()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get all rounds ordered by round number
        $rounds = $this->roundModel->orderBy('round_number', 'ASC')->findAll();

        // Determine the current round to display leaderboard for
        // Prefer the latest 'active', then 'completed', then any round with scores
        $currentRound = $this->roundModel
            ->where('status', 'active')
            ->orderBy('round_number', 'DESC')
            ->first();

        if (!$currentRound) {
            $currentRound = $this->roundModel
                ->where('status', 'completed')
                ->orderBy('round_number', 'DESC')
                ->first();
        }
        
        // If still no round, just get the latest round
        if (!$currentRound && !empty($rounds)) {
            $currentRound = end($rounds);
        }

        // Build leaderboard if we have a round
        $leaderboard = [];
        if (!empty($currentRound)) {
            $leaderboard = $this->scoreModel->getRoundRankings($currentRound['id']);
            // Map to Rank/Name pairs expected by the wireframe view
            $leaderboard = array_map(function($row) {
                return [
                    'rank' => $row['rank'],
                    'name' => $row['contestant_name']
                ];
            }, $leaderboard);
        }

        $data = [
            'title' => 'Results & Rankings',
            'rounds' => $rounds,
            'leaderboard' => $leaderboard,
        ];

        return view('admin/results/index', $data);
    }

    /**
     * View rankings for a specific round
     */
    public function viewRound($roundId)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $round = $this->roundModel->find($roundId);
        if (!$round) {
            return redirect()->to(base_url('admin/results'))
                           ->with('error', 'Round not found.');
        }

        $this->roundModel->ensureJudgeAssignments($roundId);

        // Get rankings (will show any contestant who has scores in this round)
        $rankings = $this->scoreModel->getRoundRankings($roundId);
        
        // Get judge statistics for this round
        $db = \Config\Database::connect();
        $totalJudges = $db->table('round_judges')
            ->where('round_id', $roundId)
            ->countAllResults();
        
        $completedJudges = $db->table('round_judges')
            ->where('round_id', $roundId)
            ->where('judge_round_status', 'completed')
            ->countAllResults();

        $data = [
            'title' => 'Round Rankings',
            'round' => $round,
            'rankings' => $rankings,
            'total_judges' => $totalJudges,
            'completed_judges' => $completedJudges,
        ];

        return view('admin/results/view_round', $data);
    }

    /**
     * View detailed scores for a contestant in a round
     */
    public function viewContestantDetails($roundId, $contestantId)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $round = $this->roundModel->getRoundWithDetails($roundId);
        $contestant = $this->contestantModel->find($contestantId);

        if (!$round || !$contestant) {
            return redirect()->back()->with('error', 'Invalid round or contestant.');
        }

        // Get all scores from all judges
        $scores = $this->scoreModel->getContestantScoresInRound($contestantId, $roundId);

        // Group scores by judge
        $scoresByJudge = [];
        foreach ($scores as $score) {
            $judgeId = $score['judge_id'];
            if (!isset($scoresByJudge[$judgeId])) {
                $scoresByJudge[$judgeId] = [
                    'judge_name' => $score['judge_name'],
                    'scores' => []
                ];
            }
            $scoresByJudge[$judgeId]['scores'][] = $score;
        }

        $data = [
            'title' => 'Contestant Detailed Scores',
            'round' => $round,
            'contestant' => $contestant,
            'scores_by_judge' => $scoresByJudge,
        ];

        return view('admin/results/contestant_details', $data);
    }

    /**
     * View overall rankings across all rounds
     */
    public function overall()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get all completed rounds
        $rounds = $this->roundModel->where('status', 'completed')->findAll();
        
        $overallRankings = [];
        
        if (!empty($rounds)) {
            // Get all contestants who have scores in any completed round
            $db = \Config\Database::connect();
            $roundIds = array_column($rounds, 'id');
            
            // Get unique contestant IDs from scores table
            $contestantIdsData = $db->table('scores')
                ->select('contestant_id')
                ->whereIn('round_id', $roundIds)
                ->groupBy('contestant_id')
                ->get()
                ->getResultArray();
            
            foreach ($contestantIdsData as $row) {
                $contestantId = $row['contestant_id'];
                
                // Get contestant details
                $contestant = $this->contestantModel->find($contestantId);
                if (!$contestant) {
                    continue;
                }
                
                $totalScore = 0;
                $roundCount = 0;

                // Calculate scores across all completed rounds
                foreach ($rounds as $round) {
                    $rankings = $this->scoreModel->getRoundRankings($round['id']);
                    
                    foreach ($rankings as $ranking) {
                        if ($ranking['contestant_id'] == $contestantId) {
                            // Sum the average scores (total_score already averaged by judges)
                            $totalScore += $ranking['total_score'];
                            $roundCount++;
                            break;
                        }
                    }
                }

                if ($roundCount > 0) {
                    $overallRankings[] = [
                        'contestant_id' => $contestant['id'],
                        'contestant_number' => $contestant['contestant_number'],
                        'contestant_name' => $contestant['first_name'] . ' ' . $contestant['last_name'],
                        'profile_picture' => $contestant['profile_picture'],
                        'total_score' => round($totalScore, 2), // Total sum of average scores across all rounds
                        'rounds_completed' => $roundCount,
                    ];
                }
            }
        }

        // Sort by total score (descending)
        usort($overallRankings, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Add rank
        foreach ($overallRankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
        }

        $data = [
            'title' => 'Overall Rankings',
            'rankings' => $overallRankings,
            'total_rounds' => count($rounds),
        ];

        return view('admin/results/overall', $data);
    }
}
