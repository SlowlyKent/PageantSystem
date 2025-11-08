<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Contestants Table (Normalized - Core Information)
 * Contains only essential contestant identification and basic info
 */
class CreateContestantsTable extends Migration
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
            'contestant_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
                'comment'    => 'Unique contestant identifier (e.g., C001)',
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'middle_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'birthdate' => [
                'type'    => 'DATE',
                'comment' => 'Used to calculate age automatically',
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'profile_picture' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Filename of profile photo',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'disqualified'],
                'default'    => 'active',
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
        $this->forge->createTable('contestants');
    }

    public function down()
    {
        $this->forge->dropTable('contestants');
    }
}
