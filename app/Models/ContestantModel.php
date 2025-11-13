<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Contestant Model (Normalized)
 * Manages contestant data with related tables
 */
class ContestantModel extends Model
{
    protected $table            = 'contestants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'contestant_number',
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'gender',
        'profile_picture',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'contestant_number' => 'required|is_unique[contestants.contestant_number,id,{id}]',
        'first_name'        => 'required|min_length[2]|max_length[100]',
        'last_name'         => 'required|min_length[2]|max_length[100]',
        'birthdate'         => 'required|valid_date',
        'gender'            => 'required|in_list[Male,Female]',
    ];

    protected $validationMessages = [
        'contestant_number' => [
            'required'    => 'Contestant number is required',
            'is_unique'   => 'This contestant number already exists',
        ],
        'first_name' => [
            'required' => 'First name is required',
        ],
        'last_name' => [
            'required' => 'Last name is required',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get contestant with all related data
     * Joins details and contacts tables
     */
    public function getContestantWithDetails($id)
    {
        return $this->select('contestants.*, 
                             contestant_details.age,
                             contestant_details.height,
                             contestant_details.weight,
                             contestant_details.advocacy,
                             contestant_details.talent,
                             contestant_details.hobbies,
                             contestant_details.education,
                             contestant_contacts.address,
                             contestant_contacts.barangay,
                             contestant_contacts.city,
                             contestant_contacts.province,
                             contestant_contacts.zip_code,
                             contestant_contacts.contact_number,
                             contestant_contacts.alternate_number,
                             contestant_contacts.email,
                             contestant_contacts.emergency_contact_name,
                             contestant_contacts.emergency_contact_number')
            ->join('contestant_details', 'contestant_details.contestant_id = contestants.id', 'left')
            ->join('contestant_contacts', 'contestant_contacts.contestant_id = contestants.id', 'left')
            ->where('contestants.id', $id)
            ->first();
    }

    /**
     * Get contestants assigned to a specific round (excluding eliminated)
     */
    public function getContestantsForRound(int $roundId): array
    {
        $db = \Config\Database::connect();

        $contestants = $db->table('round_contestants')
            ->select('contestants.*, 
                contestant_details.age,
                contestant_details.height,
                contestant_details.weight,
                contestant_details.advocacy,
                contestant_details.talent,
                contestant_details.hobbies,
                contestant_details.education,
                contestant_contacts.address,
                contestant_contacts.city,
                contestant_contacts.province,
                contestant_contacts.contact_number,
                contestant_contacts.email,
                round_contestants.state')
            ->join('contestants', 'contestants.id = round_contestants.contestant_id')
            ->join('contestant_details', 'contestant_details.contestant_id = contestants.id', 'left')
            ->join('contestant_contacts', 'contestant_contacts.contestant_id = contestants.id', 'left')
            ->where('round_contestants.round_id', $roundId)
            ->where('round_contestants.state !=', 'eliminated')
            ->orderBy('contestants.contestant_number', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($contestants as &$contestant) {
            if (!empty($contestant['profile_picture'])) {
                $contestant['photo_url'] = base_url('uploads/contestants/' . $contestant['profile_picture']);
            } else {
                $contestant['photo_url'] = null;
            }
        }

        return $contestants;
    }

    /**
     * Get all contestants with their details
     */
    public function getAllWithDetails()
    {
        return $this->select('contestants.*, 
                             contestant_details.age,
                             contestant_contacts.city,
                             contestant_contacts.contact_number')
            ->join('contestant_details', 'contestant_details.contestant_id = contestants.id', 'left')
            ->join('contestant_contacts', 'contestant_contacts.contestant_id = contestants.id', 'left')
            ->orderBy('contestants.contestant_number', 'ASC')
            ->findAll();
    }

    /**
     * Get all active contestants with complete details for judges
     */
    public function getContestantsWithDetails()
    {
        $contestants = $this->select('contestants.*, 
                             contestant_details.age,
                             contestant_details.height,
                             contestant_details.weight,
                             contestant_details.advocacy,
                             contestant_details.talent,
                             contestant_details.hobbies,
                             contestant_details.education,
                             contestant_contacts.address,
                             contestant_contacts.city,
                             contestant_contacts.province,
                             contestant_contacts.contact_number,
                             contestant_contacts.email')
            ->join('contestant_details', 'contestant_details.contestant_id = contestants.id', 'left')
            ->join('contestant_contacts', 'contestant_contacts.contestant_id = contestants.id', 'left')
            ->where('contestants.status', 'active')
            ->orderBy('contestants.contestant_number', 'ASC')
            ->findAll();
        
        // Add photo URL for each contestant
        foreach ($contestants as &$contestant) {
            if (!empty($contestant['profile_picture'])) {
                $contestant['photo_url'] = base_url('uploads/contestants/' . $contestant['profile_picture']);
            } else {
                $contestant['photo_url'] = null;
            }
        }
        
        return $contestants;
    }

    /**
     * Get full name of contestant
     */
    public function getFullName($contestant)
    {
        $name = $contestant['first_name'];
        if (!empty($contestant['middle_name'])) {
            $name .= ' ' . $contestant['middle_name'];
        }
        $name .= ' ' . $contestant['last_name'];
        return $name;
    }

    /**
     * Generate next contestant number
     */
    public function generateContestantNumber()
    {
        $lastContestant = $this->orderBy('id', 'DESC')->first();
        
        if (!$lastContestant) {
            return '1';
        }
        
        // Extract number from last contestant number (handle both old 'C001' and new '1' format)
        $lastNumberStr = $lastContestant['contestant_number'];
        if (strpos($lastNumberStr, 'C') === 0) {
            // Old format: C001
            $lastNumber = (int) substr($lastNumberStr, 1);
        } else {
            // New format: 1, 2, 3
            $lastNumber = (int) $lastNumberStr;
        }
        $newNumber = $lastNumber + 1;
        
        return (string) $newNumber;
    }

    /**
     * Get active contestants count
     */
    public function getActiveCount()
    {
        return $this->where('status', 'active')->countAllResults();
    }

    /**
     * Calculate age from birthdate
     */
    public function calculateAge($birthdate)
    {
        $today = new \DateTime();
        $birth = new \DateTime($birthdate);
        $age = $today->diff($birth)->y;
        return $age;
    }
}

