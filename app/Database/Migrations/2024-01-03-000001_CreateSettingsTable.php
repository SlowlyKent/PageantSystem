<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_group' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'general',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');
        
        // Insert default settings
        $defaultSettings = [
            // General Settings
            [
                'setting_key'   => 'system_name',
                'setting_value' => 'Pageant System',
                'setting_group' => 'general',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key'   => 'logo',
                'setting_value' => '',
                'setting_group' => 'general',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            
            // Theme Settings
            [
                'setting_key'   => 'theme_preset',
                'setting_value' => 'classic',
                'setting_group' => 'theme',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key'   => 'primary_color',
                'setting_value' => '#667eea',
                'setting_group' => 'theme',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key'   => 'accent_color',
                'setting_value' => '#764ba2',
                'setting_group' => 'theme',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key'   => 'text_color',
                'setting_value' => '#333333',
                'setting_group' => 'theme',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key'   => 'background_type',
                'setting_value' => 'gradient',
                'setting_group' => 'theme',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key'   => 'background_color',
                'setting_value' => '#f8f9fa',
                'setting_group' => 'theme',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key'   => 'background_image',
                'setting_value' => '',
                'setting_group' => 'theme',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];
        
        $this->db->table('settings')->insertBatch($defaultSettings);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
