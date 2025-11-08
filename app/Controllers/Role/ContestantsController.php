<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\ContestantModel;

/**
 * Contestants Controller
 * Manages contestant information (CRUD operations)
 * ADMIN ONLY
 */
class ContestantsController extends BaseController
{
    protected $contestantModel;

    public function __construct()
    {
        $this->contestantModel = new ContestantModel();
    }

    /**
     * Display all contestants
     */
    public function index()
    {
        // Set session for template
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get all contestants with their details (from joined tables)
        $contestants = $this->contestantModel->getAllWithDetails();

        $data = [
            'title'       => 'Contestants Management',
            'contestants' => $contestants,
        ];

        return view('admin/contestants/index', $data);
    }

    /**
     * Show form to add new contestant
     */
    public function create()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Generate next contestant number
        $nextNumber = $this->contestantModel->generateContestantNumber();

        $data = [
            'title'             => 'Add New Contestant',
            'contestant_number' => $nextNumber,
        ];

        return view('admin/contestants/create', $data);
    }

    /**
     * Save new contestant to database (Normalized - saves to 3 tables)
     */
    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Start transaction

        try {
            // Step 1: Save to contestants table (core info)
            $contestantData = [
                'contestant_number' => $this->request->getPost('contestant_number'),
                'first_name'        => $this->request->getPost('first_name'),
                'middle_name'       => $this->request->getPost('middle_name'),
                'last_name'         => $this->request->getPost('last_name'),
                'birthdate'         => $this->request->getPost('birthdate'),
                'gender'            => $this->request->getPost('gender'),
                'status'            => $this->request->getPost('status') ?? 'active',
            ];

            // Handle profile picture upload
            $profilePic = $this->request->getFile('profile_picture');
            if ($profilePic && $profilePic->isValid() && !$profilePic->hasMoved()) {
                $newName = 'contestant_' . time() . '.' . $profilePic->getExtension();
                $profilePic->move(FCPATH . 'uploads/contestants', $newName);
                $contestantData['profile_picture'] = $newName;
            }

            // Insert contestant
            $contestantId = $this->contestantModel->insert($contestantData);

            if (!$contestantId) {
                throw new \Exception('Failed to insert contestant');
            }

            // Step 2: Save to contestant_details table
            $age = $this->contestantModel->calculateAge($this->request->getPost('birthdate'));
            $detailsData = [
                'contestant_id' => $contestantId,
                'age'           => $age,
                'height'        => $this->request->getPost('height'),
                'weight'        => $this->request->getPost('weight'),
                'advocacy'      => $this->request->getPost('advocacy'),
                'talent'        => $this->request->getPost('talent'),
                'hobbies'       => $this->request->getPost('hobbies'),
                'education'     => $this->request->getPost('education'),
            ];

            $db->table('contestant_details')->insert($detailsData);

            // Step 3: Save to contestant_contacts table
            $contactsData = [
                'contestant_id'  => $contestantId,
                'address'        => $this->request->getPost('address'),
                'city'           => $this->request->getPost('city'),
                'province'       => $this->request->getPost('province'),
                'contact_number' => $this->request->getPost('contact_number'),
                'email'          => $this->request->getPost('email'),
            ];

            $db->table('contestant_contacts')->insert($contactsData);

            $db->transComplete(); // Complete transaction

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/admin/contestants')->with('success', 'Contestant added successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Failed to add contestant: ' . $e->getMessage());
        }
    }

    /**
     * Show contestant details
     */
    public function view($id)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get contestant with all related data from joined tables
        $contestant = $this->contestantModel->getContestantWithDetails($id);

        if (!$contestant) {
            return redirect()->to('/admin/contestants')->with('error', 'Contestant not found.');
        }

        $data = [
            'title'      => 'Contestant Details',
            'contestant' => $contestant,
        ];

        return view('admin/contestants/view', $data);
    }

    /**
     * Show form to edit contestant
     */
    public function edit($id)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get contestant with all related data
        $contestant = $this->contestantModel->getContestantWithDetails($id);

        if (!$contestant) {
            return redirect()->to('/admin/contestants')->with('error', 'Contestant not found.');
        }

        $data = [
            'title'      => 'Edit Contestant',
            'contestant' => $contestant,
        ];

        return view('admin/contestants/edit', $data);
    }

    /**
     * Update contestant information (Normalized - updates 3 tables)
     */
    public function update($id)
    {
        $contestant = $this->contestantModel->find($id);

        if (!$contestant) {
            return redirect()->to('/admin/contestants')->with('error', 'Contestant not found.');
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Start transaction

        try {
            // Step 1: Update contestants table (core info)
            $contestantData = [
                'contestant_number' => $this->request->getPost('contestant_number'),
                'first_name'        => $this->request->getPost('first_name'),
                'middle_name'       => $this->request->getPost('middle_name'),
                'last_name'         => $this->request->getPost('last_name'),
                'birthdate'         => $this->request->getPost('birthdate'),
                'gender'            => $this->request->getPost('gender'),
                'status'            => $this->request->getPost('status'),
            ];

            // Handle new profile picture upload
            $profilePic = $this->request->getFile('profile_picture');
            if ($profilePic && $profilePic->isValid() && !$profilePic->hasMoved()) {
                // Delete old picture if exists
                if ($contestant['profile_picture']) {
                    $oldPath = FCPATH . 'uploads/contestants/' . $contestant['profile_picture'];
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                
                // Upload new picture
                $newName = 'contestant_' . time() . '.' . $profilePic->getExtension();
                $profilePic->move(FCPATH . 'uploads/contestants', $newName);
                $contestantData['profile_picture'] = $newName;
            }

            // Update contestants table
            $this->contestantModel->update($id, $contestantData);

            // Step 2: Update contestant_details table
            $age = $this->contestantModel->calculateAge($this->request->getPost('birthdate'));
            $detailsData = [
                'age'       => $age,
                'height'    => $this->request->getPost('height'),
                'weight'    => $this->request->getPost('weight'),
                'advocacy'  => $this->request->getPost('advocacy'),
                'talent'    => $this->request->getPost('talent'),
                'hobbies'   => $this->request->getPost('hobbies'),
                'education' => $this->request->getPost('education'),
            ];

            $db->table('contestant_details')
               ->where('contestant_id', $id)
               ->update($detailsData);

            // Step 3: Update contestant_contacts table
            $contactsData = [
                'address'        => $this->request->getPost('address'),
                'city'           => $this->request->getPost('city'),
                'province'       => $this->request->getPost('province'),
                'contact_number' => $this->request->getPost('contact_number'),
                'email'          => $this->request->getPost('email'),
            ];

            $db->table('contestant_contacts')
               ->where('contestant_id', $id)
               ->update($contactsData);

            $db->transComplete(); // Complete transaction

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/admin/contestants')->with('success', 'Contestant updated successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Failed to update contestant: ' . $e->getMessage());
        }
    }

    /**
     * Delete contestant
     */
    public function delete($id)
    {
        $contestant = $this->contestantModel->find($id);

        if (!$contestant) {
            return redirect()->to('/admin/contestants')->with('error', 'Contestant not found.');
        }

        // Delete profile picture if exists
        if ($contestant['profile_picture']) {
            $picPath = FCPATH . 'uploads/contestants/' . $contestant['profile_picture'];
            if (file_exists($picPath)) {
                @unlink($picPath);
            }
        }

        // Delete from database
        if ($this->contestantModel->delete($id)) {
            return redirect()->to('/admin/contestants')->with('success', 'Contestant deleted successfully!');
        } else {
            return redirect()->to('/admin/contestants')->with('error', 'Failed to delete contestant.');
        }
    }

    /**
     * Remove profile picture only
     */
    public function removePhoto($id)
    {
        $contestant = $this->contestantModel->find($id);

        if (!$contestant) {
            return $this->response->setJSON(['success' => false, 'message' => 'Contestant not found']);
        }

        if ($contestant['profile_picture']) {
            // Delete file
            $picPath = FCPATH . 'uploads/contestants/' . $contestant['profile_picture'];
            if (file_exists($picPath)) {
                @unlink($picPath);
            }
            
            // Update database
            $this->contestantModel->update($id, ['profile_picture' => null]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Photo removed successfully']);
    }
}
