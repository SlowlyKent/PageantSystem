<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Authentication Controller
 * Handles login, logout, and authentication
 */
class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        helper('cookie');
        $this->userModel = new UserModel();
    }

    /**
     * Display login page
     */
    public function login()
    {
        // If user already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        $validation = \Config\Services::validation();
        
        // Validation rules
        $rules = [
            'username' => [
                'label' => 'Username/Email',
                'rules' => 'required',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Verify credentials
        $user = $this->userModel->verifyCredentials($username, $password);

        if (!$user) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Invalid username/email or password');
        }

        // Set session data
        $sessionData = [
            'user_id'     => $user['id'],
            'username'    => $user['username'],
            'email'       => $user['email'],
            'user_name'   => $user['full_name'],
            'user_role'   => $user['role_name'],  // Changed from 'role' to 'role_name'
            'role_id'     => $user['role_id'],
            'role_display'=> $user['role_display_name'],
            'isLoggedIn'  => true,
        ];

        session()->set($sessionData);

        // Handle "Remember Me"
        if ($remember) {
            $this->setRememberMeCookie($user['id']);
        }

        // Redirect to appropriate dashboard
        return $this->redirectToDashboard();
    }

    /**
     * Logout user
     */
    public function logout()
    {
        // Destroy session
        session()->destroy();

        // Delete remember me cookie
        delete_cookie('remember_me');

        // Redirect to login page
        return redirect()->to(base_url('login'))->with('success', 'You have been logged out successfully');
    }

    /**
     * Redirect to dashboard based on user role
     * Handles role validation and unknown roles
     */
    private function redirectToDashboard()
    {
        // Get user role from session
        $role = session()->get('user_role');

        // Route based on role
        switch ($role) {
            case 'judge':
                return redirect()->to(base_url('judge/dashboard'));
                
            case 'admin':
                return redirect()->to(base_url('admin/dashboard'));
                
            default:
                // Unknown role - destroy session and redirect to login
                session()->destroy();
                delete_cookie('remember_me');
                return redirect()->to(base_url('login'))
                               ->with('error', 'Invalid user role. Please contact the administrator.');
        }
    }

    /**
     * Set remember me cookie
     */
    private function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));
        
        // Store token in cookie (30 days)
        set_cookie([
            'name'   => 'remember_me',
            'value'  => $token,
            'expire' => 2592000, // 30 days
            'secure' => true,
            'httponly' => true,
        ]);

        // In production, store this token in database linked to user
        // For now, we're just storing the user_id (simplified)
    }
}
