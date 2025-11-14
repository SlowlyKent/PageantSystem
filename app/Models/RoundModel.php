<?php

namespace App\Models;

use CodeIgniter\Model;

class RoundModel extends Model
{
    protected $table            = 'rounds';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'round_number',
        'round_name',
        'description',
        'max_score',
        'status',
        'round_order',
        'is_elimination',
        'is_final',
        'is_locked',
        'elimination_quota'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'round_number' => 'required|numeric|is_unique[rounds.round_number,id,{id}]',
        'round_name'   => 'required|min_length[3]|max_length[100]'
    ];

    /**
     * Get round with all criteria (no segments)
     */
    public function getRoundWithDetails($id)
    {
        $db = \Config\Database::connect();
        
        $round = $this->find($id);
        if (!$round) {
            return null;
        }

        // Get criteria directly tied to round
        $criteria = $db->table('round_criteria')
            ->where('round_id', $id)
            ->orderBy('order', 'ASC')
            ->get()
            ->getResultArray();

        $round['criteria'] = $criteria;
        return $round;
    }

    /**
     * Get all rounds with criteria count
     */
    public function getAllWithCriteriaCount()
    {
        return $this->select('rounds.*, 
                             COUNT(round_criteria.id) as criteria_count')
            ->join('round_criteria', 'round_criteria.round_id = rounds.id', 'left')
            ->groupBy('rounds.id')
            ->orderBy('rounds.round_number', 'ASC')
            ->findAll();
    }

    /**
     * Generate next round number
     */
    public function getNextRoundNumber()
    {
        $lastRound = $this->orderBy('round_number', 'DESC')->first();
        return $lastRound ? $lastRound['round_number'] + 1 : 1;
    }

    /**
     * Get rounds ordered by round_order
     */
    public function getAllOrdered()
    {
        return $this->orderBy('round_order', 'ASC')->findAll();
    }

    /**
     * Lock a round
     */
    public function lockRound($id)
    {
        return $this->update($id, ['is_locked' => 1, 'status' => 'completed']);
    }

    /**
     * Unlock a round (only if final results not declared)
     */
    public function unlockRound($id)
    {
        return $this->update($id, ['is_locked' => 0, 'status' => 'active']);
    }

    /**
     * Ensure every active judge has an assignment row for the given round.
     */
    public function ensureJudgeAssignments(int $roundId): void
    {
        $round = $this->find($roundId);
        if (!$round) {
            return;
        }

        $db = \Config\Database::connect();

        $activeJudges = $db->table('users')
            ->select('users.id')
            ->join('roles', 'roles.id = users.role_id')
            ->where('roles.name', 'judge')
            ->where('users.status', 'active')
            ->get()
            ->getResultArray();

        if (empty($activeJudges)) {
            return;
        }

        $existingAssignments = $db->table('round_judges')
            ->select('judge_id')
            ->where('round_id', $roundId)
            ->get()
            ->getResultArray();

        $existingMap = array_column($existingAssignments, 'judge_id');

        $now = date('Y-m-d H:i:s');

        foreach ($activeJudges as $judge) {
            if (!in_array($judge['id'], $existingMap, true)) {
                $db->table('round_judges')->insert([
                    'round_id'           => $roundId,
                    'judge_id'           => $judge['id'],
                    'assigned'           => 1,
                    'judge_round_status' => 'pending',
                    'completed_at'       => null,
                    'updated_at'         => $now,
                ]);
            }
        }
    }
}
