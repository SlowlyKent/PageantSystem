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
        $activeJudges = $userModel->select('users.*')
                                   ->join('roles', 'roles.id = users.role_id')
                                   ->where('roles.name', 'judge')
                                   ->where('users.status', 'active')
                                   ->countAllResults();
        $totalRounds = $roundModel->countAllResults();
        $scoresSubmitted = $scoreModel->countAllResults();
        
        $data = [
            'title' => 'Dashboard',
            'total_contestants' => $totalContestants,
            'active_judges' => $activeJudges,
            'total_rounds' => $totalRounds,
            'scores_submitted' => $scoresSubmitted,
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
