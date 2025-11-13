<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\RoundModel;
use App\Models\RoundCriteriaModel;

class RoundsCriteriaController extends BaseController
{
    protected $roundModel;
    protected $roundCriteriaModel;

    public function __construct()
    {
        $this->roundModel = new RoundModel();
        $this->roundCriteriaModel = new RoundCriteriaModel();
    }

    /**
     * Display all rounds
     */
    public function index()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $rounds = $this->roundModel->getAllWithCriteriaCount();

        $data = [
            'title' => 'Rounds & Criteria',
            'rounds' => $rounds,
        ];

        return view('admin/rounds_criteria/index', $data);
    }

    /**
     * Show form to create new round
     */
    public function create()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $nextRoundNumber = $this->roundModel->getNextRoundNumber();

        $data = [
            'title' => 'Add New Round',
            'next_round_number' => $nextRoundNumber,
        ];

        return view('admin/rounds_criteria/create', $data);
    }

    /**
     * Store new round with criteria only (no segments)
     */
    public function store()
    {
        // Criteria-only
        $criteriaNames = $this->request->getPost('criteria_name') ?? [];
        $criteriaPercentages = $this->request->getPost('criteria_percentage') ?? [];
        $criteriaMaxScores = $this->request->getPost('criteria_max_score') ?? [];
        $criteriaDescriptions = $this->request->getPost('criteria_description') ?? [];

        if (empty($criteriaNames) || !is_array($criteriaNames)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You must add at least one criteria for the round.');
        }

        // Validate criteria weights add up to 100%
        $totalCriteriaWeight = 0.0;
        $validationErrors = [];

        foreach ($criteriaPercentages as $p) {
            $totalCriteriaWeight += (float)$p;
        }

        if (abs($totalCriteriaWeight - 100) > 0.01) {
            $validationErrors[] = "Total criteria weights must equal 100%. Currently: {$totalCriteriaWeight}%";
        }

        // Validate elimination quota when enabled
        $isElimination = $this->request->getPost('is_elimination') ? 1 : 0;
        $eliminationQuota = (int)($this->request->getPost('elimination_quota') ?? 0);
        if ($isElimination && $eliminationQuota <= 0) {
            $validationErrors[] = 'Please set how many contestants to eliminate for this round.';
        }

        if (!empty($validationErrors)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $validationErrors));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get round max score
            $roundMaxScore = (float)$this->request->getPost('max_score');
            
            // Save round
            $roundData = [
                'round_number'   => $this->request->getPost('round_number'),
                'round_name'     => $this->request->getPost('round_name'),
                'description'    => $this->request->getPost('round_description'),
                'max_score'      => $roundMaxScore,
                'status'         => 'pending',
                'round_order'    => $this->request->getPost('round_number'), // Use round_number as default order
                'is_elimination' => $this->request->getPost('is_elimination') ? 1 : 0,
                'is_final'       => $this->request->getPost('is_final') ? 1 : 0,
                'elimination_quota' => $this->request->getPost('elimination_quota') ?: null,
            ];

            $roundId = $this->roundModel->insert($roundData);

            if (!$roundId) {
                throw new \Exception('Failed to create round');
            }

            // Save round criteria
            foreach ($criteriaNames as $index => $name) {
                if (empty($name)) {
                    continue;
                }
                $percentage = (float)($criteriaPercentages[$index] ?? 0);
                $max = (int)($criteriaMaxScores[$index] ?? 0);
                $desc = $criteriaDescriptions[$index] ?? '';

                $this->roundCriteriaModel->insert([
                    'round_id'      => $roundId,
                    'criteria_name' => $name,
                    'description'   => $desc,
                    'max_score'     => $max > 0 ? $max : 100,
                    'percentage'    => $percentage,
                    'order'         => $index + 1,
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('success', 'Round created successfully with criteria.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create round: ' . $e->getMessage());
        }
    }

    /**
     * View round details
     */
    public function view($id)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $round = $this->roundModel->getRoundWithDetails($id);

        if (!$round) {
            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('error', 'Round not found.');
        }

        $data = [
            'title' => 'Round Details',
            'round' => $round,
            // for new UI, criteria are inside $round already
        ];

        return view('admin/rounds_criteria/view', $data);
    }

    /**
     * Delete round
     */
    public function delete($id)
    {
        $round = $this->roundModel->find($id);

        if (!$round) {
            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('error', 'Round not found.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete scores associated with this round
            $db->table('scores')->where('round_id', $id)->delete();
            
            // Delete round judges assignments
            $db->table('round_judges')->where('round_id', $id)->delete();
            
            // Delete round contestants states
            $db->table('round_contestants')->where('round_id', $id)->delete();
            
            // Delete criteria (new structure)
            $db->table('round_criteria')->where('round_id', $id)->delete();

            // Finally delete the round
            $this->roundModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed while deleting round data.');
            }

            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('success', 'Round and all related judging data were deleted successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                             ->with('error', 'Failed to delete round: ' . $e->getMessage());
        }
    }

    /**
     * Edit round (name, order, elimination/final flags, quota)
     */
    public function edit($id)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $round = $this->roundModel->find($id);
        if (!$round) {
            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('error', 'Round not found.');
        }

        // Ensure all fields exist with defaults
        $round['is_locked'] = $round['is_locked'] ?? 0;
        $round['is_elimination'] = $round['is_elimination'] ?? 0;
        $round['is_final'] = $round['is_final'] ?? 0;
        $round['elimination_quota'] = $round['elimination_quota'] ?? null;
        $round['round_order'] = $round['round_order'] ?? 1;
        $round['description'] = $round['description'] ?? '';

        $data = [
            'title' => 'Edit Round',
            'round' => $round,
        ];

        return view('admin/edit', $data);
    }

    /**
     * Update round
     */
    public function update($id)
    {
        $data = [
            'round_name'      => $this->request->getPost('round_name'),
            'description'     => $this->request->getPost('description'),
            'round_order'     => $this->request->getPost('round_order'),
            'is_elimination'  => $this->request->getPost('is_elimination') ? 1 : 0,
            'is_final'        => $this->request->getPost('is_final') ? 1 : 0,
            'elimination_quota' => $this->request->getPost('elimination_quota') ?: null,
        ];

        if ($this->roundModel->update($id, $data)) {
            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('success', 'Round updated successfully!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update round.');
        }
    }

    /**
     * Mark round as completed (admin confirmation after reviewing scores)
     */
    public function markCompleted($id)
    {
        if ($this->roundModel->update($id, ['status' => 'completed'])) {
            return redirect()->back()->with('success', 'Round marked as completed.');
        }
        return redirect()->back()->with('error', 'Failed to mark round as completed.');
    }
    
    /**
     * Activate round (make it accessible to judges)
     */
    public function activate($id)
    {
        $db = \Config\Database::connect();
        
        // Get current round to check if it was completed
        $round = $this->roundModel->find($id);
        if (!$round) {
            return redirect()->back()->with('error', 'Round not found.');
        }
        $wasCompleted = $round['status'] === 'completed';

        // Determine previous round (used for seeding eligible contestants)
        $previousRound = $this->roundModel
            ->where('round_number <', $round['round_number'])
            ->orderBy('round_number', 'DESC')
            ->first();

        // Activate the round
        if ($this->roundModel->update($id, ['status' => 'active', 'is_locked' => 0])) {
            // Seed contestants for first-time activation
            if (!$wasCompleted) {
                $existingEntries = $db->table('round_contestants')
                    ->where('round_id', $id)
                    ->countAllResults();

                if ($existingEntries == 0) {
                    if ($previousRound) {
                        // Use contestants who advanced in previous round
                        $eligibleContestants = $db->table('round_contestants')
                            ->select('contestant_id')
                            ->where('round_id', $previousRound['id'])
                            ->where('state !=', 'eliminated')
                            ->get()
                            ->getResultArray();
                    } else {
                        // First round: all active contestants
                        $eligibleContestants = $db->table('contestants')
                            ->select('id AS contestant_id')
                            ->where('status', 'active')
                            ->get()
                            ->getResultArray();
                    }

                    $eligibleCount = count($eligibleContestants);
                    if ($eligibleCount === 0) {
                        return redirect()->back()->with('error', 'No contestants available to seed this round.');
                    }

                    // Validate elimination quota
                    if (!empty($round['is_elimination']) && (int)$round['elimination_quota'] > 0 && (int)$round['elimination_quota'] >= $eligibleCount) {
                        return redirect()->back()->with('error', 'Elimination quota cannot be greater than or equal to the number of contestants in this round.');
                    }

                    foreach ($eligibleContestants as $contestant) {
                        $db->table('round_contestants')->insert([
                            'round_id' => $id,
                            'contestant_id' => $contestant['contestant_id'],
                            'state' => 'active',
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }

            // If re-activating a completed round, keep judge completion statuses
            // Judges who completed remain completed, others can continue scoring
            if ($wasCompleted) {
                return redirect()->back()->with('success', 'Round re-activated. Judges who have completed remain completed, others can continue scoring.');
            }
            
            return redirect()->back()->with('success', 'Round activated. Judges can now access it.');
        }
        return redirect()->back()->with('error', 'Failed to activate round.');
    }
    
    /**
     * Lock round (admin confirmation)
     */
    public function lock($id)
    {
        if ($this->roundModel->lockRound($id)) {
            return redirect()->back()->with('success', 'Round locked. Scoring is now disabled.');
        }
        return redirect()->back()->with('error', 'Failed to lock round.');
    }

    /**
     * Reset judge completions for a round
     */
    public function resetJudgeCompletions($id)
    {
        $db = \Config\Database::connect();
        
        // Reset all judge completion timestamps for this round
        $db->table('round_judges')
            ->where('round_id', $id)
            ->update(['completed_at' => null, 'updated_at' => date('Y-m-d H:i:s')]);
        
        // Also set round status back to active so judges can access it
        $this->roundModel->update($id, ['status' => 'active']);
        
        return redirect()->back()->with('success', 'Judge completions have been reset. Judges can now re-score this round.');
    }
    
    /**
     * Unlock round (if final results not declared)
     */
    public function unlock($id)
    {
        if ($this->roundModel->unlockRound($id)) {
            return redirect()->back()->with('success', 'Round unlocked. Judges can score again.');
        }
        return redirect()->back()->with('error', 'Failed to unlock round.');
    }

    /**
     * Trigger elimination: advance top N to next round
     */
    public function eliminate($id)
    {
        $db = \Config\Database::connect();
        $scoreModel = new \App\Models\ScoreModel();
        $roundModel = new \App\Models\RoundModel();

        $round = $roundModel->find($id);
        if (!$round || !$round['is_elimination']) {
            return redirect()->back()->with('error', 'Invalid round or not an elimination round.');
        }

        $quota = $this->request->getPost('elimination_quota') ?? $round['elimination_quota'];
        if (!$quota) {
            return redirect()->back()->with('error', 'Elimination quota not set.');
    }

        // Get rankings for this round
        $rankings = $scoreModel->getRoundRankings($id);
        $topIds = array_slice(array_column($rankings, 'contestant_id'), 0, (int)$quota);

        // Get next round by order
        $nextRound = $db->table('rounds')
            ->where('round_order >', $round['round_order'])
            ->orderBy('round_order', 'ASC')
            ->get()
            ->getRowArray();

        if (!$nextRound) {
            return redirect()->back()->with('error', 'No next round found to advance contestants.');
        }

        // Advance top N to next round (set state = active)
        foreach ($topIds as $cid) {
            $db->table('round_contestants')
                ->where('round_id', $nextRound['id'])
                ->where('contestant_id', $cid)
                ->update(['state' => 'active', 'updated_at' => date('Y-m-d H:i:s')]);
        }

        // Mark others as eliminated in current round
        $allIds = array_column($rankings, 'contestant_id');
        $eliminated = array_diff($allIds, $topIds);
        foreach ($eliminated as $cid) {
            $db->table('round_contestants')
                ->where('round_id', $id)
                ->where('contestant_id', $cid)
                ->update(['state' => 'eliminated', 'updated_at' => date('Y-m-d H:i:s')]);
        }

        // Optionally lock the round after elimination
        $roundModel->lockRound($id);

        return redirect()->back()->with('success', "Top {$quota} advanced to {$nextRound['round_name']}. Others eliminated.");
    }
}
