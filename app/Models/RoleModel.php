<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'display_name',
        'description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'         => 'required|min_length[3]|max_length[50]|is_unique[roles.name,id,{id}]',
        'display_name' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Role name is required',
            'is_unique'   => 'This role already exists',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get role by name
     */
    public function getRoleByName(string $name)
    {
        return $this->where('name', $name)->first();
    }

    /**
     * Get all active roles for dropdown
     */
    public function getRolesForDropdown()
    {
        return $this->findAll();
    }
}
