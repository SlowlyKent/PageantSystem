<?php

namespace App\Models;

use CodeIgniter\Model;

class CriteriaModel extends Model
{
    protected $table            = 'criteria';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'segment_id',
        'criteria_name',
        'description',
        'max_score',
        'percentage',
        'order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get criteria for a specific segment
     */
    public function getCriteriaBySegment($segmentId)
    {
        return $this->where('segment_id', $segmentId)
                    ->orderBy('order', 'ASC')
                    ->findAll();
    }

    /**
     * Validate criteria percentages sum to 100
     */
    public function validatePercentages($segmentId, $excludeCriteriaId = null)
    {
        $query = $this->where('segment_id', $segmentId);
        
        if ($excludeCriteriaId) {
            $query->where('id !=', $excludeCriteriaId);
        }
        
        $criteria = $query->findAll();
        $total = array_sum(array_column($criteria, 'percentage'));
        
        return $total;
    }
}
