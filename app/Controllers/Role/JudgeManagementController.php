<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

/**
 * JudgeManagementController
 * Manages judge accounts (CRUD operations) for admins.
 */
class JudgeManagementController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    /**
     * Display all judges.
     */
    public function index()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $judgeRole = $this->roleModel->getRoleByName('judge');

        $judges = $this->userModel
            ->select('users.*, roles.display_name as role_display_name')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.role_id', $judgeRole['id'])
            ->orderBy('users.created_at', 'DESC')
            ->findAll();

        return view('admin/judges/index', [
            'title'  => 'Judges Management',
            'judges' => $judges,
        ]);
    }

    /**
     * Show form to add new judge.
     */
    public function create()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        return view('admin/judges/create', [
            'title' => 'Add New Judge',
        ]);
    }

    /**
     * Save new judge to database.
     */
    public function store()
    {
        $rules = [
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'full_name' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $judgeRole = $this->roleModel->getRoleByName('judge');

        $data = [
            'username'            => $this->request->getPost('email'),
            'email'               => $this->request->getPost('email'),
            'password'            => $this->request->getPost('password'),
            'full_name'           => $this->request->getPost('full_name'),
            'role_id'             => $judgeRole['id'],
            'status'              => $this->request->getPost('status') ?? 'active',
            'judge_title'         => $this->request->getPost('judge_title'),
            'judge_organization'  => $this->request->getPost('judge_organization'),
            'judge_achievements'  => $this->request->getPost('judge_achievements'),
            'judge_biography'     => $this->request->getPost('judge_biography'),
            'judge_intro_notes'   => $this->request->getPost('judge_intro_notes'),
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/admin/judges')->with('success', 'Judge added successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add judge. Please try again.');
    }

    /**
     * Show judge details.
     */
    public function view($id)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $judge = $this->userModel->getUserWithRole($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        return view('admin/judges/view', [
            'title' => 'Judge Details',
            'judge' => $judge,
        ]);
    }

    /**
     * Show form to edit judge.
     */
    public function edit($id)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $judge = $this->userModel->find($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        return view('admin/judges/edit', [
            'title' => 'Edit Judge',
            'judge' => $judge,
        ]);
    }

    /**
     * Update judge information.
     */
    public function update($id)
    {
        $judge = $this->userModel->find($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        $rules = [
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'full_name' => 'required|min_length[3]|max_length[255]',
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'            => $this->request->getPost('email'),
            'email'               => $this->request->getPost('email'),
            'full_name'           => $this->request->getPost('full_name'),
            'status'              => $this->request->getPost('status'),
            'judge_title'         => $this->request->getPost('judge_title'),
            'judge_organization'  => $this->request->getPost('judge_organization'),
            'judge_achievements'  => $this->request->getPost('judge_achievements'),
            'judge_biography'     => $this->request->getPost('judge_biography'),
            'judge_intro_notes'   => $this->request->getPost('judge_intro_notes'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->userModel->setValidationRules([
            'username'  => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'full_name' => 'required|min_length[3]|max_length[255]',
            'status'    => 'in_list[active,inactive]',
            'password'  => 'permit_empty|min_length[6]',
        ]);

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/admin/judges')->with('success', 'Judge updated successfully!');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors() ?: ['Failed to update judge. Please try again.']);
    }

    /**
     * Delete judge.
     */
    public function delete($id)
    {
        $judge = $this->userModel->find($id);

        if (!$judge) {
            return redirect()->to('/admin/judges')->with('error', 'Judge not found.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/judges')->with('success', 'Judge deleted successfully!');
        }

        return redirect()->to('/admin/judges')->with('error', 'Failed to delete judge.');
    }
}


