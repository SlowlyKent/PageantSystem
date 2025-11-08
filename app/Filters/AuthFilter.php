<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Authentication Filter
 * Protects routes that require authentication
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page');
        }

        // Check role-based access if specified
        if ($arguments) {
            $userRole = session()->get('user_role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->back()->with('error', 'You do not have permission to access this page');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
