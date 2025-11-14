<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\RoundModel;
use App\Models\ContestantModel;
use App\Models\RoundCriteriaModel;
use App\Models\ScoreModel;

/**
 * Judge Controller
 * Handles all judge dashboard and scoring functions
 */
class JudgeController extends BaseController
{
    protected $roundModel;
    protected $contestantModel;
    protected $roundCriteriaModel;
    protected $scoreModel;

    public function __construct()
    {
        $this->roundModel = new RoundModel();
        $this->contestantModel = new ContestantModel();
        $this->roundCriteriaModel = new RoundCriteriaModel();
        $this->scoreModel = new ScoreModel();
    }

    /**
     * Display judge dashboard
     */
    public function dashboard()
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);

        $judgeId = session()->get('user_id') ?? 1; // Get from session
        $db = \Config\Database::connect();
        
        // Get active round
        $activeRound = $this->roundModel->where('status', 'active')->first();
        
        // Get statistics
        $totalContestants = $this->contestantModel->where('status', 'active')->countAllResults();
        
        // Initialize default values
        $completedScores = 0;
        $averageScore = 0;
        $currentLeaderboard = [];
        $eliminatedContestants = 0;
        
        if ($activeRound) {
            // Get scores completed by this judge for this round
            $completedScoresQuery = $db->table('scores')
                ->select('contestant_id')
                ->where('round_id', $activeRound['id'])
                ->where('judge_id', $judgeId)
                ->groupBy('contestant_id')
                ->get()
                ->getResultArray();
            $completedScores = count($completedScoresQuery);
            
            // Count eliminated contestants in this round
            $eliminatedContestants = $db->table('round_contestants')
                ->where('round_id', $activeRound['id'])
                ->where('state', 'eliminated')
                ->countAllResults();

            // Calculate average score for this judge
            $avgQuery = $db->table('scores')
                ->select('AVG(score) as avg_score')
                ->where('round_id', $activeRound['id'])
                ->where('judge_id', $judgeId)
                ->get()
                ->getRow();
            $averageScore = $avgQuery ? round($avgQuery->avg_score, 1) : 0;
            
            // Get current leaderboard (top 5 contestants by average score)
            $rankings = $this->scoreModel->getRoundRankings($activeRound['id']);
            
            // Take only top 5 and format for the view
            $currentLeaderboard = array_slice($rankings, 0, 5);
            
            // Map to expected format
            $currentLeaderboard = array_map(function($ranking) {
                return [
                    'id' => $ranking['contestant_id'],
                    'contestant_number' => $ranking['contestant_number'],
                    'name' => $ranking['contestant_name'],
                    'avg_score' => $ranking['total_score']
                ];
            }, $currentLeaderboard);
        }
        
        $data = [
            'title' => 'Judge Dashboard',
            'active_round' => $activeRound,
            'total_contestants' => $totalContestants,
            'eliminated_contestants' => $eliminatedContestants,
            'completed_scores' => $completedScores,
            'average_score' => $averageScore,
            'current_leaderboard' => $currentLeaderboard,
        ];

        return view('judge/dashboard', $data);
    }

    /**
     * Select round to score
     */
    public function selectRound()
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);

        $judgeId = session()->get('user_id') ?? 1;
        $db = \Config\Database::connect();
        
        // Fetch ALL rounds to show judges
        $rounds = $this->roundModel->orderBy('round_number', 'ASC')->findAll();
        $criteriaCounts = $this->roundCriteriaModel
            ->select('round_id, COUNT(*) AS criteria_count')
            ->groupBy('round_id')
            ->findAll();
        $criteriaMap = [];
        foreach ($criteriaCounts as $row) {
            $criteriaMap[$row['round_id']] = (int)$row['criteria_count'];
        }

        // Check which rounds this judge has completed and if they're locked
        foreach ($rounds as &$round) {
            $this->roundModel->ensureJudgeAssignments((int)$round['id']);

            $judgeAssignment = $db->table('round_judges')
                ->where('round_id', $round['id'])
                ->where('judge_id', $judgeId)
                ->get()
                ->getRowArray();
            
            $judgeStatus = $judgeAssignment['judge_round_status'] ?? 'pending';
            $round['judge_completed'] = $judgeStatus === 'completed';
            
            // Check if round is locked by admin
            $round['is_locked'] = isset($round['is_locked']) && $round['is_locked'] == 1;
            $round['criteria_count'] = $criteriaMap[$round['id']] ?? 0;

            // Judge completion stats for this round
            $round['completed_judges'] = $db->table('round_judges')
                ->where('round_id', $round['id'])
                ->where('judge_round_status', 'completed')
                ->countAllResults();
            $round['total_judges'] = $db->table('round_judges')
                ->where('round_id', $round['id'])
                ->countAllResults();
            $round['all_judges_completed'] = (
                $round['total_judges'] > 0 &&
                $round['completed_judges'] > 0 &&
                $round['completed_judges'] === $round['total_judges']
            );
        }

        $data = [
            'title' => 'Select Round',
            'rounds' => $rounds,
        ];

        return view('judge/select_round', $data);
    }

    /**
     * View contestants to score for a specific round
     */
    public function scoreRound($roundId)
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);

        $judgeId = session()->get('user_id') ?? 1;
        $db = \Config\Database::connect();
        
        $round = $this->roundModel->getRoundWithDetails($roundId);
        if (!$round) {
            return redirect()->to(base_url('judge/select-round'))
                           ->with('error', 'Round not found.');
        }

        // Make sure every active judge has an assignment row for this round
        $this->roundModel->ensureJudgeAssignments($roundId);
        
        // Check if judge is assigned to this round and get completion status
        $judgeAssignment = $db->table('round_judges')
            ->where('round_id', $roundId)
            ->where('judge_id', $judgeId)
            ->get()
            ->getRowArray();
        
        // Auto-assign current judge to this round if not already assigned
        if (!$judgeAssignment) {
            // Assign this judge to the round
            $db->table('round_judges')->insert([
                'round_id' => $roundId,
                'judge_id' => $judgeId,
                'assigned' => 1,
                'judge_round_status' => 'pending',
                'completed_at' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            // Refresh the assignment data
            $judgeAssignment = $db->table('round_judges')
                ->where('round_id', $roundId)
                ->where('judge_id', $judgeId)
                ->get()
                ->getRowArray();
        }

        // Get all rounds for navigation
        $all_rounds = $this->roundModel->orderBy('round_number', 'ASC')->findAll();
        
        // Get total active judges count once (used for all rounds)
        $totalActiveJudges = $db->table('users')
            ->join('roles', 'roles.id = users.role_id')
            ->where('roles.name', 'judge')
            ->where('users.status', 'active')
            ->countAllResults();
        
        // Check completion status for each round
        foreach ($all_rounds as &$rnd) {
            $this->roundModel->ensureJudgeAssignments((int)$rnd['id']);
            // Check if all active judges completed this round
            $completedJudges = $db->table('users')
                ->join('roles', 'roles.id = users.role_id')
                ->join('round_judges', 'round_judges.judge_id = users.id AND round_judges.round_id = ' . (int)$rnd['id'])
                ->where('roles.name', 'judge')
                ->where('users.status', 'active')
                ->where('round_judges.judge_round_status', 'completed')
                ->countAllResults();
            
            $rnd['is_completed'] = ($totalActiveJudges > 0 && $totalActiveJudges == $completedJudges);
        }

        // Get contestants assigned to this round (fallback to active contestants if assignment missing)
        $contestants = $this->contestantModel->getContestantsForRound($roundId);
        if (empty($contestants)) {
            // Fallback: load all active contestants (e.g., first round before assignments seeded)
            $contestants = $this->contestantModel->getContestantsWithDetails();
        }

        // Get existing scores for all contestants
        $existingScoresRaw = $this->scoreModel
            ->where('judge_id', $judgeId)
            ->where('round_id', $roundId)
            ->findAll();
        
        // Organize scores by contestant and criteria
        $existing_scores = [];
        foreach ($existingScoresRaw as $score) {
            $existing_scores[$score['contestant_id']][$score['round_criteria_id']] = $score;
        }

        // Calculate statistics
        $totalContestants = count($contestants);
        
        // Get total active judges (count all active judges in the system)
        $totalJudges = $totalActiveJudges; // Use the count calculated earlier
        
        // Get completed judges count for this specific round
        $judgesCompleted = $db->table('users')
            ->join('roles', 'roles.id = users.role_id')
            ->join('round_judges', 'round_judges.judge_id = users.id AND round_judges.round_id = ' . (int)$roundId)
            ->where('roles.name', 'judge')
            ->where('users.status', 'active')
            ->where('round_judges.judge_round_status', 'completed')
            ->countAllResults();
        
        // Get to_eliminate from round settings
        $toEliminate = $round['elimination_quota'] ?? 0;
        
        // Check if current judge has completed this round
        $currentJudgeStatus = $judgeAssignment['judge_round_status'] ?? 'pending';
        $currentJudgeCompleted = ($currentJudgeStatus === 'completed');
        
        // Check if next round exists and is unlocked
        $nextRound = null;
        $nextRoundUnlocked = false;
        $nextRoundData = $this->roundModel
            ->where('round_number >', $round['round_number'])
            ->orderBy('round_number', 'ASC')
            ->first();
        
        if ($nextRoundData) {
            $nextRound = $nextRoundData;
            // Next round is unlocked ONLY if:
            // 1. There are active judges in the system (totalJudges > 0)
            // 2. ALL active judges have completed this round (totalJudges == judgesCompleted)
            // 3. At least one judge has completed (judgesCompleted > 0)
            // 4. Admin has set next round status to 'active' (not pending/locked)
            // 5. Next round is not locked by admin
            $nextRoundUnlocked = (
                $totalJudges > 0 && 
                $judgesCompleted > 0 && 
                $totalJudges == $judgesCompleted &&
                $nextRoundData['status'] === 'active' &&
                (!isset($nextRoundData['is_locked']) || $nextRoundData['is_locked'] == 0)
            );
        }
        
        // Check if this is the final round and all judges completed
        $isFinalRound = !$nextRoundData;
        $allJudgesCompleted = (
            $totalActiveJudges > 0 && 
            $judgesCompleted > 0 && 
            $totalActiveJudges == $judgesCompleted
        );
        $topContestants = [];
        
        if ($isFinalRound && $allJudgesCompleted) {
            // Get top 3 contestants by total score
            $topContestants = $this->scoreModel->getTopContestantsByRound($roundId, 3);
        }
        
        // Get all judges assigned to this round with their details
        $judgesList = $db->table('round_judges')
            ->select('users.id, users.full_name as name, users.email, round_judges.completed_at, round_judges.judge_round_status')
            ->join('users', 'users.id = round_judges.judge_id')
            ->where('round_judges.round_id', $roundId)
            ->orderBy('users.full_name', 'ASC')
            ->get()
            ->getResultArray();
        
        // Get rankings if all judges haven't completed yet but some scores exist
        $rankings = [];
        if ($judgesCompleted > 0) {
            $rankings = $this->scoreModel->getRoundRankings($roundId);
        }

        $data = [
            'title' => 'Score Contestants',
            'round' => $round,
            'all_rounds' => $all_rounds,
            'contestants' => $contestants,
            'existing_scores' => $existing_scores,
            'current_judge_completed' => $currentJudgeCompleted,
            'current_judge_status' => $currentJudgeStatus,
            'next_round' => $nextRound,
            'next_round_unlocked' => $nextRoundUnlocked,
            'is_final_round' => $isFinalRound,
            'all_judges_completed' => $allJudgesCompleted,
            'top_contestants' => $topContestants,
            'judges_list' => $judgesList,
            'rankings' => $rankings,
            'stats' => [
                'total_contestants' => $totalContestants,
                'total_judges' => $totalJudges,
                'judges_completed' => $judgesCompleted,
                'to_eliminate' => $toEliminate,
            ],
        ];

        return view('judge/score_round', $data);
    }

    /**
     * View round rankings/results
     */
    public function roundResults($roundId)
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);

        $judgeId = session()->get('user_id') ?? 1;
        $db = \Config\Database::connect();

        $round = $this->roundModel->getRoundWithDetails($roundId);
        if (!$round) {
            return redirect()->to(base_url('judge/select-round'))
                ->with('error', 'Round not found.');
        }

        $this->roundModel->ensureJudgeAssignments($roundId);

        // Ensure judge is assigned to this round
        $judgeAssignment = $db->table('round_judges')
            ->where('round_id', $roundId)
            ->where('judge_id', $judgeId)
            ->get()
            ->getRowArray();

        if (!$judgeAssignment) {
            $db->table('round_judges')->insert([
                'round_id' => $roundId,
                'judge_id' => $judgeId,
                'assigned' => 1,
                'judge_round_status' => 'pending',
                'completed_at' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $rankings = $this->scoreModel->getRoundRankings($roundId);
        $topContestants = $this->scoreModel->getTopContestantsByRound($roundId, 3);

        $totalJudges = $db->table('users')
            ->join('roles', 'roles.id = users.role_id')
            ->where('roles.name', 'judge')
            ->where('users.status', 'active')
            ->countAllResults();

        $judgesCompleted = $db->table('users')
            ->join('roles', 'roles.id = users.role_id')
            ->join('round_judges', 'round_judges.judge_id = users.id AND round_judges.round_id = ' . (int)$roundId)
            ->where('roles.name', 'judge')
            ->where('users.status', 'active')
            ->where('round_judges.judge_round_status', 'completed')
            ->countAllResults();

        $data = [
            'title' => 'Round Rankings',
            'round' => $round,
            'rankings' => $rankings,
            'top_contestants' => $topContestants,
            'stats' => [
                'total_judges' => $totalJudges,
                'judges_completed' => $judgesCompleted,
            ],
        ];

        return view('judge/round_results', $data);
    }

    /**
     * Show scoring form for a specific contestant
     */
    public function scoreContestant($roundId, $contestantId)
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);

        $judgeId = session()->get('user_id') ?? 1;

        $round = $this->roundModel->getRoundWithDetails($roundId);
        $contestant = $this->contestantModel->find($contestantId);

        if (!$round || !$contestant) {
            return redirect()->back()->with('error', 'Invalid round or contestant.');
        }

        if ($this->isJudgeRoundLocked((int)$roundId, $judgeId)) {
            return redirect()->to(base_url("judge/score-round/{$roundId}"))
                ->with('error', 'You have already marked this round as complete. Scoring is locked.');
        }

        // Get existing scores if any
        $existingScores = $this->scoreModel->getJudgeScoresForRound($judgeId, $roundId);
        $scoresArray = [];
        foreach ($existingScores as $score) {
            if ($score['contestant_id'] == $contestantId) {
                $scoresArray[$score['round_criteria_id']] = $score;
            }
        }

        $data = [
            'title' => 'Score Contestant',
            'round' => $round,
            'contestant' => $contestant,
            'existing_scores' => $scoresArray,
        ];

        return view('judge/score_contestant', $data);
    }

    /**
     * Save scores for a contestant
     */
    public function submitScores()
    {
        $judgeId = session()->get('user_id') ?? 1;
        $roundId = $this->request->getPost('round_id');
        $contestantId = $this->request->getPost('contestant_id');
        $scores = $this->request->getPost('scores'); // Array of criteria_id => score

        if ($this->isJudgeRoundLocked((int)$roundId, $judgeId)) {
            return redirect()->to(base_url("judge/score-round/{$roundId}"))
                ->with('error', 'This round has already been marked as complete for you. You can only view results.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $assignmentCount = $db->table('round_contestants')
                ->where('round_id', $roundId)
                ->countAllResults();
            if ($assignmentCount > 0) {
                $isAssignedContestant = $db->table('round_contestants')
                    ->where('round_id', $roundId)
                    ->where('contestant_id', $contestantId)
                    ->where('state !=', 'eliminated')
                    ->countAllResults();

                if ($isAssignedContestant === 0) {
                    throw new \Exception('You are not allowed to score this contestant for this round.');
                }
            }

            foreach ($scores as $criteriaId => $scoreValue) {
                // Check if score already exists
                $existing = $this->scoreModel
                    ->where('judge_id', $judgeId)
                    ->where('contestant_id', $contestantId)
                    ->where('round_id', $roundId)
                    ->where('round_criteria_id', $criteriaId)
                    ->first();

                $scoreData = [
                    'judge_id' => $judgeId,
                    'contestant_id' => $contestantId,
                    'round_id' => $roundId,
                    'round_criteria_id' => $criteriaId,
                    'score' => $scoreValue,
                    'remarks' => $this->request->getPost("remarks_{$criteriaId}") ?? '',
                ];

                if ($existing) {
                    $this->scoreModel->update($existing['id'], $scoreData);
                } else {
                    $this->scoreModel->insert($scoreData);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Failed to save scores');
            }

            return redirect()->to(base_url("judge/score-round/{$roundId}"))
                           ->with('success', 'Scores submitted successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to submit scores: ' . $e->getMessage());
        }
    }

    /**
     * Auto-save a single score via AJAX
     */
    public function autoSaveScore()
    {
        $judgeId = session()->get('user_id') ?? 1;
        $roundId = $this->request->getPost('round_id');
        $contestantId = $this->request->getPost('contestant_id');
        $criteriaId = $this->request->getPost('criteria_id');
        $scoreValue = $this->request->getPost('score');

        try {
            if ($this->isJudgeRoundLocked((int)$roundId, $judgeId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This round is locked. You already marked it as complete.'
                ]);
            }

            $db = \Config\Database::connect();

            $assignmentCount = $db->table('round_contestants')
                ->where('round_id', $roundId)
                ->countAllResults();
            if ($assignmentCount > 0) {
                $isAssignedContestant = $db->table('round_contestants')
                    ->where('round_id', $roundId)
                    ->where('contestant_id', $contestantId)
                    ->where('state !=', 'eliminated')
                    ->countAllResults();

                if ($isAssignedContestant === 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Contestant is not assigned to this round.'
                    ]);
                }
            }
            
            // Check if score already exists
            $existing = $this->scoreModel
                ->where('judge_id', $judgeId)
                ->where('contestant_id', $contestantId)
                ->where('round_criteria_id', $criteriaId)
                ->first();

            $scoreData = [
                'judge_id' => $judgeId,
                'contestant_id' => $contestantId,
                'round_id' => $roundId,
                'round_criteria_id' => $criteriaId,
                'score' => $scoreValue,
                'remarks' => '',
            ];

            if ($existing) {
                $this->scoreModel->update($existing['id'], $scoreData);
            } else {
                $this->scoreModel->insert($scoreData);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Score saved'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Save all scores for all contestants at once
     */
    public function submitAllScores()
    {
        $judgeId = session()->get('user_id') ?? 1;
        $roundId = $this->request->getPost('round_id');
        $scores = $this->request->getPost('scores'); // Array of [contestant_id][criteria_id] => score

        if ($this->isJudgeRoundLocked((int)$roundId, $judgeId)) {
            return redirect()->to(base_url("judge/score-round/{$roundId}"))
                ->with('error', 'This round is locked because you already marked it as complete.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Loop through all contestants and their scores
            foreach ($scores as $contestantId => $criteriaScores) {
                $assignmentCount = $db->table('round_contestants')
                    ->where('round_id', $roundId)
                    ->countAllResults();
                if ($assignmentCount > 0) {
                    $isAssignedContestant = $db->table('round_contestants')
                        ->where('round_id', $roundId)
                        ->where('contestant_id', $contestantId)
                        ->where('state !=', 'eliminated')
                        ->countAllResults();

                    if ($isAssignedContestant === 0) {
                        throw new \Exception('One or more contestants are not assigned to this round.');
                    }
                }

                foreach ($criteriaScores as $criteriaId => $scoreValue) {
                    // Check if score already exists
                    $existing = $this->scoreModel
                        ->where('judge_id', $judgeId)
                        ->where('contestant_id', $contestantId)
                        ->where('round_id', $roundId)
                        ->where('round_criteria_id', $criteriaId)
                        ->first();

                    $scoreData = [
                        'judge_id' => $judgeId,
                        'contestant_id' => $contestantId,
                        'round_id' => $roundId,
                        'round_criteria_id' => $criteriaId,
                        'score' => $scoreValue,
                        'remarks' => '',
                    ];

                    if ($existing) {
                        $this->scoreModel->update($existing['id'], $scoreData);
                    } else {
                        $this->scoreModel->insert($scoreData);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Failed to save scores');
            }

            return redirect()->to(base_url("judge/score-round/{$roundId}"))
                           ->with('success', 'Scores saved successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to save scores: ' . $e->getMessage());
        }
    }

    /**
     * Mark judge as complete for a round
     */
    public function markComplete($roundId)
    {
        $judgeId = session()->get('user_id') ?? 1;
        $db = \Config\Database::connect();
        
        $this->roundModel->ensureJudgeAssignments($roundId);

        try {
            // Ensure judge has scored all contestants/criteria before completing
            $criteriaCount = $db->table('round_criteria')
                ->where('round_id', $roundId)
                ->countAllResults();

            $contestantCount = $db->table('round_contestants')
                ->where('round_id', $roundId)
                ->where('state !=', 'eliminated')
                ->countAllResults();

            if ($contestantCount === 0) {
                $contestantCount = $this->contestantModel->where('status', 'active')->countAllResults();
            }

            $expectedScores = $criteriaCount * $contestantCount;
            $actualScores = $this->scoreModel
                ->where('judge_id', $judgeId)
                ->where('round_id', $roundId)
                ->countAllResults();

            if ($expectedScores > 0 && $actualScores < $expectedScores) {
                $remaining = $expectedScores - $actualScores;
                return redirect()->to(base_url("judge/score-round/{$roundId}"))
                    ->with('error', "You still have {$remaining} score(s) to complete before marking this round as complete.");
            }

            // Mark judge as completed for this round
            $roundJudge = $db->table('round_judges')
                ->where('round_id', $roundId)
                ->where('judge_id', $judgeId)
                ->get()
                ->getRowArray();
            
            if ($roundJudge) {
                if (($roundJudge['judge_round_status'] ?? 'pending') === 'completed') {
                    return redirect()->to(base_url("judge/score-round/{$roundId}"))
                        ->with('info', 'You already marked this round as complete.');
                }

                $db->table('round_judges')
                    ->where('id', $roundJudge['id'])
                    ->update([
                        'completed_at' => date('Y-m-d H:i:s'),
                        'judge_round_status' => 'completed',
                    ]);
                
                // Count active judges ASSIGNED to this round
                // Only judges present in round_judges should be considered for completion
                $assignedActiveJudges = $db->table('round_judges')
                    ->join('users', 'users.id = round_judges.judge_id')
                    ->join('roles', 'roles.id = users.role_id')
                    ->where('round_judges.round_id', (int)$roundId)
                    ->where('roles.name', 'judge')
                    ->where('users.status', 'active')
                    ->countAllResults();
                
                // Count how many assigned active judges have completed this round
                $completedJudges = $db->table('round_judges')
                    ->join('users', 'users.id = round_judges.judge_id')
                    ->join('roles', 'roles.id = users.role_id')
                    ->where('round_judges.round_id', (int)$roundId)
                    ->where('roles.name', 'judge')
                    ->where('users.status', 'active')
                    ->where('round_judges.judge_round_status', 'completed')
                    ->countAllResults();
                
                // If all active judges completed, automatically mark round as completed
                if ($assignedActiveJudges > 0 && $assignedActiveJudges == $completedJudges) {
                    $this->roundModel->update($roundId, ['status' => 'completed']);
                    $this->finalizeRound($roundId);
                    
                    return redirect()->to(base_url("judge/score-round/{$roundId}"))
                                   ->with('success', 'You have completed scoring for this round. All judges have finished - rankings have been generated and the round is now completed!');
                }
                
                return redirect()->to(base_url("judge/score-round/{$roundId}"))
                               ->with('success', "Your scoring for this round is now marked as complete! Waiting for other judges... ({$completedJudges}/{$assignedActiveJudges} completed)");
            }
            
            return redirect()->to(base_url("judge/score-round/{$roundId}"))
                           ->with('error', 'Failed to mark as complete.');
                           
        } catch (\Exception $e) {
            return redirect()->to(base_url("judge/score-round/{$roundId}"))
                           ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * View contestants list
     */
    public function contestants()
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);
        
        $judgeId = session()->get('user_id') ?? 1;
        
        // Get all active contestants with details and contacts
        $contestants = $this->contestantModel->getContestantsWithDetails();
        
        // Get statistics
        $totalContestants = count($contestants);
        $scoresSubmitted = $this->scoreModel->where('judge_id', $judgeId)->countAllResults();
        
        $data = [
            'title' => 'View Contestants',
            'contestants' => $contestants,
            'total_contestants' => $totalContestants,
            'scores_submitted' => $scoresSubmitted,
        ];

        return view('judge/contestants', $data);
    }

    /**
     * Submit scores for a contestant
     * @return string
     */
    public function submitScore(): string
    {
        // Set session data for role-based template
        session()->set([
            'user_role' => 'judge',
            'user_name' => 'Judge User'
        ]);
        
        $data = [
            'title' => 'Submit Score',
            'user' => [
                'name' => 'Judge User',
                'role' => 'Judge'
            ]
        ];

        return view('judge/submit_score', $data);
    }

    /**
     * View scoring history
     * @return string
     */
    public function history(): string
    {
        // Set session data for role-based template
        session()->set([
            'user_role' => 'judge',
            'user_name' => 'Judge User'
        ]);
        
        $data = [
            'title' => 'Scoring History',
            'user' => [
                'name' => 'Judge User',
                'role' => 'Judge'
            ]
        ];

        return view('judge/history', $data);
    }

    /**
     * View overall rankings
     */
    public function rankings()
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);

        // Get all completed rounds
        $completedRounds = $this->roundModel->where('status', 'completed')->findAll();
        
        // Get all rounds for individual round rankings
        $allRounds = $this->roundModel->orderBy('round_number', 'ASC')->findAll();
        
        // Calculate overall rankings across completed rounds
        $db = \Config\Database::connect();
        $contestantIds = [];
        
        if (!empty($completedRounds)) {
            $roundIds = array_column($completedRounds, 'id');
            $contestantIds = $db->table('scores')
                ->select('contestant_id')
                ->whereIn('round_id', $roundIds)
                ->groupBy('contestant_id')
                ->get()
                ->getResultArray();
        }
        
        $overallRankings = [];

        foreach ($contestantIds as $row) {
            $contestantId = $row['contestant_id'];
            
            // Get contestant details
            $contestant = $this->contestantModel->find($contestantId);
            if (!$contestant) {
                continue;
            }
            
            $totalScore = 0;
            $roundCount = 0;

            foreach ($completedRounds as $round) {
                $rankings = $this->scoreModel->getRoundRankings($round['id']);
                
                foreach ($rankings as $ranking) {
                    if ($ranking['contestant_id'] == $contestantId) {
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
                    'total_score' => round($totalScore / $roundCount, 2),
                    'rounds_completed' => $roundCount,
                ];
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
            'overall_rankings' => $overallRankings,
            'all_rounds' => $allRounds,
            'completed_rounds' => $completedRounds,
            'total_completed_rounds' => count($completedRounds),
        ];

        return view('judge/rankings', $data);
    }

    /**
     * Finalize round once all judges have completed scoring.
     * Generates rankings, updates elimination status, and marks contestants for advancement.
     */
    private function finalizeRound(int $roundId): void
    {
        $db = \Config\Database::connect();
        $round = $this->roundModel->find($roundId);
        if (!$round) {
            return;
        }

        $rankings = $this->scoreModel->getRoundRankings($roundId);
        if (empty($rankings)) {
            return;
        }

        // Ensure round_contestants records exist
        $existingContestants = $db->table('round_contestants')
            ->where('round_id', $roundId)
            ->get()
            ->getResultArray();
        $existingMap = [];
        foreach ($existingContestants as $row) {
            $existingMap[$row['contestant_id']] = $row;
        }

        foreach ($rankings as $ranking) {
            if (!isset($existingMap[$ranking['contestant_id']])) {
                $db->table('round_contestants')->insert([
                    'round_id' => $roundId,
                    'contestant_id' => $ranking['contestant_id'],
                    'state' => 'active',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Refresh map after potential inserts
        $existingContestants = $db->table('round_contestants')
            ->where('round_id', $roundId)
            ->get()
            ->getResultArray();
        $existingMap = [];
        foreach ($existingContestants as $row) {
            $existingMap[$row['contestant_id']] = $row;
        }

        $isElimination = !empty($round['is_elimination']);
        $eliminationQuota = (int)($round['elimination_quota'] ?? 0);
        $totalRanked = count($rankings);
        $cutoffIndex = $isElimination && $eliminationQuota > 0 ? max($totalRanked - $eliminationQuota, 0) : $totalRanked;

        foreach ($rankings as $index => $ranking) {
            $contestantId = $ranking['contestant_id'];
            $state = ($index < $cutoffIndex) ? 'advanced' : 'eliminated';
            if (!$isElimination || $eliminationQuota <= 0) {
                $state = 'advanced';
            }

            $db->table('round_contestants')
                ->where('round_id', $roundId)
                ->where('contestant_id', $contestantId)
                ->update(['state' => $state, 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }

    /**
     * Check if the given judge has already completed the round.
     */
    private function isJudgeRoundLocked(int $roundId, int $judgeId): bool
    {
        $assignment = \Config\Database::connect()->table('round_judges')
            ->where('round_id', $roundId)
            ->where('judge_id', $judgeId)
            ->get()
            ->getRowArray();

        return $assignment && ($assignment['judge_round_status'] ?? 'pending') === 'completed';
    }
}
