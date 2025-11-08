<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\SettingsModel;

/**
 * Settings Controller
 * Manages system settings and theme customization
 * ADMIN ONLY: Only administrators can change settings
 */
class SettingsController extends BaseController
{
    protected $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    /**
     * Display settings page
     */
    public function index()
    {
        // Set session for template
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        // Get all settings
        $settings = $this->settingsModel->getAllSettings();

        $data = [
            'title'    => 'System Settings',
            'settings' => $settings,
        ];

        return view('admin/settings', $data);
    }

    /**
     * Update general settings (system name, logo)
     */
    public function updateGeneral()
    {
        // Get form data
        $systemName = $this->request->getPost('system_name');

        // Update system name
        $this->settingsModel->setSetting('system_name', $systemName);

        // Handle logo upload
        $logo = $this->request->getFile('logo');
        
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            // Validate file type
            if (in_array($logo->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                // Generate unique name
                $newName = 'logo_' . time() . '.' . $logo->getExtension();
                
                // Move to public/uploads/settings folder
                $logo->move(FCPATH . 'uploads/settings', $newName);
                
                // Save filename to database
                $this->settingsModel->setSetting('logo', $newName);
                
                // Delete old logo (optional)
                $oldLogo = $this->settingsModel->getSetting('logo');
                if ($oldLogo && file_exists(FCPATH . 'uploads/settings/' . $oldLogo)) {
                    @unlink(FCPATH . 'uploads/settings/' . $oldLogo);
                }
            } else {
                return redirect()->back()->with('error', 'Invalid file type. Only JPG, PNG, and GIF are allowed.');
            }
        }

        return redirect()->to('/admin/settings')->with('success', 'General settings updated successfully!');
    }

    /**
     * Update theme settings (colors, background)
     */
    public function updateTheme()
    {
        // Get form data
        $themeData = [
            'theme_preset'      => $this->request->getPost('theme_preset'),
            'primary_color'     => $this->request->getPost('primary_color'),
            'accent_color'      => $this->request->getPost('accent_color'),
            'text_color'        => $this->request->getPost('text_color'),
            'background_type'   => $this->request->getPost('background_type'),
            'background_color'  => $this->request->getPost('background_color'),
        ];

        // Update all theme settings
        $this->settingsModel->updateMultiple($themeData);

        // Handle background image upload
        $bgImage = $this->request->getFile('background_image');
        
        if ($bgImage && $bgImage->isValid() && !$bgImage->hasMoved()) {
            if (in_array($bgImage->getExtension(), ['jpg', 'jpeg', 'png'])) {
                $newName = 'bg_' . time() . '.' . $bgImage->getExtension();
                $bgImage->move(FCPATH . 'uploads/settings', $newName);
                $this->settingsModel->setSetting('background_image', $newName);
            }
        }

        return redirect()->to('/admin/settings')->with('success', 'Theme settings updated successfully!');
    }

    /**
     * Apply theme preset
     * AJAX endpoint for live preview
     */
    public function applyPreset()
    {
        $preset = $this->request->getJSON()->preset ?? 'classic';

        // Define theme presets
        $presets = [
            'classic' => [
                'primary_color' => '#667eea',
                'accent_color'  => '#764ba2',
                'text_color'    => '#333333',
            ],
            'modern' => [
                'primary_color' => '#6366f1',
                'accent_color'  => '#ec4899',
                'text_color'    => '#1f2937',
            ],
            'youthful' => [
                'primary_color' => '#f59e0b',
                'accent_color'  => '#10b981',
                'text_color'    => '#374151',
            ],
            'elegant' => [
                'primary_color' => '#8b5cf6',
                'accent_color'  => '#d946ef',
                'text_color'    => '#1e293b',
            ],
        ];

        // Return preset colors
        if (isset($presets[$preset])) {
            return $this->response->setJSON([
                'success' => true,
                'colors'  => $presets[$preset]
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Preset not found'
        ]);
    }

    /**
     * Remove logo
     * Deletes logo file and clears database setting
     */
    public function removeLogo()
    {
        try {
            // Get current logo
            $currentLogo = $this->settingsModel->getSetting('logo');
            
            if ($currentLogo) {
                // Delete file from server
                $filePath = FCPATH . 'uploads/settings/' . $currentLogo;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                
                // Clear logo setting in database
                $this->settingsModel->setSetting('logo', '');
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Logo removed successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
