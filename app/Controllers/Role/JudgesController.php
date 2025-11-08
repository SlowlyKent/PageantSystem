<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

/**
 * Judges Controller
 * Manages judge accounts (CRUD operations)
 * ADMIN ONLY
 */
class JudgesController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    /**
     * Display all judges
     */
    public function index()
    {
        // Set session for template
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get judge role
        $judgeRole = $this->roleModel->getRoleByName('judge');
        
        // Get all judges (users with judge role)
        $judges = $this->userModel
            ->select('users.*, roles.display_name as role_display_name')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.role_id', $judgeRole['id'])
            ->orderBy('users.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'  => 'Judges Management',
            'judges' => $judges,
        ];

        return view('admin/judges/index', $data);
    }

    /**
     * Show form to add new judge
     */
    public function create()
    {
        // Set session
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $data = [
            'title' => 'Add New Judge',
        ];

        return view('admin/judges/create', $data);
    }

    /**
     * Save new judge to database
     */
    public function store()
    {
        // Validation rules
        $rules = [
            'username'  => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'full_name' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get judge role ID
        $judgeRole = $this->roleModel->getRoleByName('judge');

        // Prepare data
        $data = [
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'), // Will be hashed by model
            'full_name' => $this->request->getPost('full_name'),
            'role_id'   => $judgeRole['id'],
            'status'    => $this->request->getPost('status') ?? 'active',
        ];

        // Save to database
        if ($this->userModel->insert($data)) {
            return redirect()->to('/admin/judges')->with('success', 'Judge added successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add judge. Please try again.');
        }
    }

    /**
     * Show judge details
     */
    public function view($id)
    {
        // Set session
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get judge with role info
        $judge = $this->userModel->getUserWithRole($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        $data = [
            'title' => 'Judge Details',
            'judge' => $judge,
        ];

        return view('admin/judges/view', $data);
    }

    /**
     * Show form to edit judge
     */
    public function edit($id)
    {
        // Set session
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $judge = $this->userModel->find($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        $data = [
            'title' => 'Edit Judge',
            'judge' => $judge,
        ];

        return view('admin/judges/edit', $data);
    }

    /**
     * Update judge information
     */
    public function update($id)
    {
        $judge = $this->userModel->find($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        // Validation rules
        $rules = [
            'username'  => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'full_name' => 'required|min_length[3]|max_length[255]',
        ];

        // If password is provided, validate it
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'status'    => $this->request->getPost('status'),
        ];

        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        // Update database
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/admin/judges')->with('success', 'Judge updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update judge.');
        }
    }

    /**
     * Delete judge
     */
    public function delete($id)
    {
        $judge = $this->userModel->find($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        // Delete from database
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/judges')->with('success', 'Judge deleted successfully!');
        } else {
            return redirect()->to('/admin/judges')->with('error', 'Failed to delete judge.');
        }
    }
}
