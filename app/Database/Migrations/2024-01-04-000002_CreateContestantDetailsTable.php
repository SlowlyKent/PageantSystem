<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Contestant Details Table (Normalized)
 * Contains physical attributes and personal information
 * One-to-One relationship with contestants table
 */
class CreateContestantDetailsTable extends Migration
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
            'contestant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'age' => [
                'type'       => 'INT',
                'constraint' => 3,
                'comment'    => 'Calculated from birthdate',
            ],
            'height' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'comment'    => 'Height in cm or feet',
            ],
            'weight' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'comment'    => 'Weight in kg or lbs',
            ],
            'advocacy' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Contestant\'s platform or cause',
            ],
            'talent' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Special skills or talents',
            ],
            'hobbies' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Interests and hobbies',
            ],
            'education' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Educational background',
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
        $this->forge->addForeignKey('contestant_id', 'contestants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contestant_details');
    }

    public function down()
    {
        $this->forge->dropTable('contestant_details');
    }
}
