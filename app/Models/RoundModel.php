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
        'segment_count',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'round_number' => 'required|numeric|is_unique[rounds.round_number,id,{id}]',
        'round_name'   => 'required|min_length[3]|max_length[100]',
        'segment_count' => 'required|in_list[1,2]',
    ];

    /**
     * Get round with all segments and criteria
     */
    public function getRoundWithDetails($id)
    {
        $db = \Config\Database::connect();
        
        $round = $this->find($id);
        if (!$round) {
            return null;
        }

        // Get segments
        $segments = $db->table('round_segments')
            ->where('round_id', $id)
            ->orderBy('segment_number', 'ASC')
            ->get()
            ->getResultArray();

        // Get criteria for each segment
        foreach ($segments as &$segment) {
            $segment['criteria'] = $db->table('criteria')
                ->where('segment_id', $segment['id'])
                ->orderBy('order', 'ASC')
                ->get()
                ->getResultArray();
        }

        $round['segments'] = $segments;
        return $round;
    }

    /**
     * Get all rounds with segment count
     */
    public function getAllWithSegmentCount()
    {
        return $this->select('rounds.*, 
                             COUNT(round_segments.id) as actual_segment_count')
            ->join('round_segments', 'round_segments.round_id = rounds.id', 'left')
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
}
