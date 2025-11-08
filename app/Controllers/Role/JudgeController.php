<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\RoundModel;
use App\Models\ContestantModel;
use App\Models\ScoreModel;

/**
 * Judge Controller
 * Handles all judge dashboard and scoring functions
 */
class JudgeController extends BaseController
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
     * Display judge dashboard
     */
    public function dashboard()
    {
        session()->set([
            'user_role' => 'judge',
            'user_name' => session()->get('user_name') ?? 'Judge User'
        ]);

        $judgeId = session()->get('user_id') ?? 1; // Get from session
        
        // Get active rounds
        $rounds = $this->roundModel->where('status', 'active')->findAll();
        
        // Get statistics
        $totalContestants = $this->contestantModel->where('status', 'active')->countAllResults();
        $totalRounds = count($rounds);
        
        $data = [
            'title' => 'Judge Dashboard',
            'rounds' => $rounds,
            'total_contestants' => $totalContestants,
            'total_rounds' => $totalRounds,
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

        $rounds = $this->roundModel->where('status', 'active')->findAll();

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
        
        $round = $this->roundModel->getRoundWithDetails($roundId);
        if (!$round) {
            return redirect()->to(base_url('judge/select-round'))
                           ->with('error', 'Round not found.');
        }

        // Get active contestants
        $contestants = $this->contestantModel->where('status', 'active')->findAll();

        // Check which contestants have been scored
        foreach ($contestants as &$contestant) {
            $contestant['scored'] = $this->scoreModel->hasJudgeCompletedScoring(
                $judgeId,
                $contestant['id'],
                $roundId
            );
        }

        $data = [
            'title' => 'Score Contestants',
            'round' => $round,
            'contestants' => $contestants,
        ];

        return view('judge/score_round', $data);
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

        // Get existing scores if any
        $existingScores = $this->scoreModel->getJudgeScoresForRound($judgeId, $roundId);
        $scoresArray = [];
        foreach ($existingScores as $score) {
            if ($score['contestant_id'] == $contestantId) {
                $scoresArray[$score['criteria_id']] = $score;
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

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            foreach ($scores as $criteriaId => $scoreValue) {
                // Get segment_id for this criteria
                $criteria = $db->table('criteria')->where('id', $criteriaId)->get()->getRowArray();
                
                if ($criteria) {
                    // Check if score already exists
                    $existing = $this->scoreModel
                        ->where('judge_id', $judgeId)
                        ->where('contestant_id', $contestantId)
                        ->where('criteria_id', $criteriaId)
                        ->first();

                    $scoreData = [
                        'judge_id' => $judgeId,
                        'contestant_id' => $contestantId,
                        'round_id' => $roundId,
                        'segment_id' => $criteria['segment_id'],
                        'criteria_id' => $criteriaId,
                        'score' => $scoreValue,
                        'remarks' => $this->request->getPost("remarks_{$criteriaId}") ?? '',
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
                           ->with('success', 'Scores submitted successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to submit scores: ' . $e->getMessage());
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
        
        // Get active rounds
        $rounds = $this->roundModel->where('status', 'active')->findAll();
        
        // Get statistics
        $totalContestants = $this->contestantModel->where('status', 'active')->countAllResults();
        $scoresSubmitted = $this->scoreModel->where('judge_id', $judgeId)->countAllResults();
        
        $data = [
            'title' => 'Score Contestants',
            'rounds' => $rounds,
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
}
