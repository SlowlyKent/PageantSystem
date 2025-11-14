<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\ContestantModel;
use App\Models\UserModel;
use App\Models\RoundModel;
use App\Models\ScoreModel;

/**
 * Admin Controller
 * Handles all admin dashboard functions
 */
class AdminController extends BaseController
{
    /**
     * Display admin dashboard
     * @return string
     */
    public function dashboard(): string
    {
        // Set session data for role-based template
        session()->set([
            'user_role' => 'admin',
            'user_name' => 'System Administrator'
        ]);
        
        // Get statistics
        $contestantModel = new ContestantModel();
        $userModel = new UserModel();
        $roundModel = new RoundModel();
        $scoreModel = new ScoreModel();
        
        $totalContestants = $contestantModel->where('status', 'active')->countAllResults();
        $db = \Config\Database::connect();
        $eliminatedContestants = $db->table('round_contestants')
            ->select('contestant_id')
            ->where('state', 'eliminated')
            ->groupBy('contestant_id')
            ->get()
            ->getNumRows();
        $activeJudges = $userModel->select('users.*')
                                   ->join('roles', 'roles.id = users.role_id')
                                   ->where('roles.name', 'judge')
                                   ->where('users.status', 'active')
                                   ->countAllResults();
        $totalRounds = $roundModel->countAllResults();
        $scoresSubmitted = $scoreModel->countAllResults();
        
        // Determine current round (prefer latest active, else latest completed)
        $currentRound = $roundModel->where('status', 'active')->orderBy('id', 'DESC')->first();
        if (!$currentRound) {
            $currentRound = $roundModel->where('status', 'completed')->orderBy('id', 'DESC')->first();
        }

        // Build small leaderboard (top 5)
        $dashboardLeaderboard = [];
        $judgeCompletion = ['total' => 0, 'completed' => 0, 'percentage' => 0];
        if (!empty($currentRound)) {
            $roundModel->ensureJudgeAssignments($currentRound['id']);

            $rankings = $scoreModel->getRoundRankings($currentRound['id']);
            $dashboardLeaderboard = array_map(function($row) {
                return [
                    'rank' => $row['rank'],
                    'name' => $row['contestant_name']
                ];
            }, array_slice($rankings, 0, 5));

            // Get all active judges with their completion status for current round
            $judges_list = $db->table('users')
                ->select('users.id, users.full_name, round_judges.completed_at, round_judges.judge_round_status')
                ->join('roles', 'roles.id = users.role_id')
                ->join('round_judges', 'round_judges.judge_id = users.id AND round_judges.round_id = ' . $currentRound['id'], 'left')
                ->where('roles.name', 'judge')
                ->where('users.status', 'active')
                ->orderBy('users.full_name', 'ASC')
                ->get()
                ->getResultArray();
            
            // Calculate judge completion from judges_list
            $totalJudges = count($judges_list);
            $completedJudges = 0;
            foreach ($judges_list as $judge) {
                if (($judge['judge_round_status'] ?? 'pending') === 'completed') {
                    $completedJudges++;
                }
            }
            $judgeCompletion = [
                'total' => $totalJudges,
                'completed' => $completedJudges,
                'percentage' => $totalJudges > 0 ? round(($completedJudges / $totalJudges) * 100, 2) : 0
            ];
        } else {
            $judges_list = [];
        }

        $data = [
            'title' => 'Dashboard',
            'total_contestants' => $totalContestants,
            'active_judges' => $activeJudges,
            'total_rounds' => $totalRounds,
            'scores_submitted' => $scoresSubmitted,
            'eliminated_contestants' => $eliminatedContestants,
            'current_round' => $currentRound,
            'dashboard_leaderboard' => $dashboardLeaderboard,
            'judge_completion' => $judgeCompletion,
            'judges_list' => $judges_list,
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Manage contestants
     * @return string
     */
    public function contestants(): string
    {
        // Set session data for role-based template
        session()->set([
            'user_role' => 'admin',
            'user_name' => 'Admin User'
        ]);
        
        $data = [
            'title' => 'Manage Contestants',
            'user' => [
                'name' => 'Admin User',
                'role' => 'Administrator'
            ]
        ];

        return view('admin/contestants', $data);
    }

    /**
     * Manage judges
     * @return string
     */
    public function judges(): string
    {
        // Set session data for role-based template
        session()->set([
            'user_role' => 'admin',
            'user_name' => 'Admin User'
        ]);
        
        $data = [
            'title' => 'Manage Judges',
            'user' => [
                'name' => 'Admin User',
                'role' => 'Administrator'
            ]
        ];

        return view('admin/judges', $data);
    }

    /**
     * Manage rounds and criteria
     * @return string
     */
    public function roundsCriteria(): string
    {
        // Set session data for role-based template
        session()->set([
            'user_role' => 'admin',
            'user_name' => 'Admin User'
        ]);
        
        $data = [
            'title' => 'Rounds & Criteria',
            'user' => [
                'name' => 'Admin User',
                'role' => 'Administrator'
            ]
        ];

        return view('admin/rounds_criteria', $data);
    }

    /**
     * View results
     * @return string
     */
    public function results(): string
    {
        // Set session data for role-based template
        session()->set([
            'user_role' => 'admin',
            'user_name' => 'Admin User'
        ]);
        
        $data = [
            'title' => 'Results',
            'user' => [
                'name' => 'Admin User',
                'role' => 'Administrator'
            ]
        ];

        return view('admin/results', $data);
    }

}
