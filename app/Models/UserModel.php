<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'full_name',
        'role_id',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'full_name' => 'required|min_length[3]|max_length[255]',
        'role_id'  => 'required|is_natural_no_zero|is_not_unique[roles.id]',
        'status'   => 'in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'    => 'Username is required',
            'min_length'  => 'Username must be at least 3 characters',
            'is_unique'   => 'Username already exists',
        ],
        'email' => [
            'required'    => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique'   => 'Email already exists',
        ],
        'password' => [
            'required'   => 'Password is required',
            'min_length' => 'Password must be at least 6 characters',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash password before saving
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Verify user credentials
     * Returns user with role information
     */
    public function verifyCredentials(string $username, string $password)
    {
        $user = $this->select('users.*, roles.name as role_name, roles.display_name as role_display_name')
                     ->join('roles', 'roles.id = users.role_id')
                     ->where('username', $username)
                     ->orWhere('email', $username)
                     ->first();

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        if ($user['status'] !== 'active') {
            return false;
        }

        return $user;
    }

    /**
     * Get user with role information
     */
    public function getUserWithRole($userId)
    {
        return $this->select('users.*, roles.name as role_name, roles.display_name as role_display_name')
                    ->join('roles', 'roles.id = users.role_id')
                    ->where('users.id', $userId)
                    ->first();
    }

    /**
     * Get all users with their roles
     */
    public function getAllWithRoles()
    {
        return $this->select('users.*, roles.name as role_name, roles.display_name as role_display_name')
                    ->join('roles', 'roles.id = users.role_id')
                    ->findAll();
    }
}
