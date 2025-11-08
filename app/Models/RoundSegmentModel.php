<?php

namespace App\Models;

use CodeIgniter\Model;

class RoundSegmentModel extends Model
{
    protected $table            = 'round_segments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'round_id',
        'segment_number',
        'segment_name',
        'description',
        'weight_percentage'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get segments for a specific round
     */
    public function getSegmentsByRound($roundId)
    {
        return $this->where('round_id', $roundId)
                    ->orderBy('segment_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get segment with all criteria
     */
    public function getSegmentWithCriteria($segmentId)
    {
        $db = \Config\Database::connect();
        
        $segment = $this->find($segmentId);
        if (!$segment) {
            return null;
        }

        $segment['criteria'] = $db->table('criteria')
            ->where('segment_id', $segmentId)
            ->orderBy('order', 'ASC')
            ->get()
            ->getResultArray();

        return $segment;
    }
}
