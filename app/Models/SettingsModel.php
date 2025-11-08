<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Settings Model
 * Manages system settings with key-value pairs
 * BEGINNER-FRIENDLY: Simple methods to get and set settings
 */
class SettingsModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'setting_key',
        'setting_value',
        'setting_group'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get a single setting value by key
     * USAGE: $value = $model->getSetting('system_name');
     */
    public function getSetting(string $key, $default = null)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        return $setting ? $setting['setting_value'] : $default;
    }

    /**
     * Get all settings as key-value array
     * USAGE: $settings = $model->getAllSettings();
     * RESULT: ['system_name' => 'Pageant System', 'logo' => 'logo.png', ...]
     */
    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    }

    /**
     * Get settings by group
     * USAGE: $themeSettings = $model->getSettingsByGroup('theme');
     */
    public function getSettingsByGroup(string $group)
    {
        $settings = $this->where('setting_group', $group)->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    }

    /**
     * Set or update a setting value
     * USAGE: $model->setSetting('system_name', 'My Pageant');
     */
    public function setSetting(string $key, $value)
    {
        // Check if setting exists
        $existing = $this->where('setting_key', $key)->first();
        
        if ($existing) {
            // Update existing setting
            return $this->update($existing['id'], ['setting_value' => $value]);
        } else {
            // Create new setting
            return $this->insert([
                'setting_key'   => $key,
                'setting_value' => $value,
            ]);
        }
    }

    /**
     * Update multiple settings at once
     * USAGE: $model->updateMultiple(['system_name' => 'New Name', 'logo' => 'new.png'])
     */
    public function updateMultiple(array $settings)
    {
        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value);
        }
        
        return true;
    }
}
