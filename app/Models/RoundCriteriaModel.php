<?php

namespace App\Models;

use CodeIgniter\Model;

class RoundCriteriaModel extends Model
{
    protected $table            = 'round_criteria';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'round_id',
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
     * Get criteria for a specific round, ordered for display.
     */
    public function getCriteriaByRound(int $roundId): array
    {
        return $this->where('round_id', $roundId)
            ->orderBy('order', 'ASC')
            ->findAll();
    }
}


