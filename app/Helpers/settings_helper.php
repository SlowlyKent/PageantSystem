<?php

/**
 * Settings Helper
 * Makes settings easily accessible in views
 */

if (!function_exists('get_setting')) {
    /**
     * Get a single setting value
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if not found
     * @return mixed Setting value
     */
    function get_setting($key, $default = null)
    {
        $settingsModel = new \App\Models\SettingsModel();
        return $settingsModel->getSetting($key, $default);
    }
}

if (!function_exists('get_all_settings')) {
    /**
     * Get all settings as an array
     * 
     * @return array All settings
     */
    function get_all_settings()
    {
        $settingsModel = new \App\Models\SettingsModel();
        return $settingsModel->getAllSettings();
    }
}

if (!function_exists('system_name')) {
    /**
     * Get system name (shortcut function)
     * 
     * @return string System name
     */
    function system_name()
    {
        return get_setting('system_name', 'Pageant System');
    }
}

if (!function_exists('system_logo')) {
    /**
     * Get system logo URL
     * 
     * @return string|null Logo URL or null
     */
    function system_logo()
    {
        $logo = get_setting('logo');
        return $logo ? base_url('uploads/settings/' . $logo) : null;
    }
}
