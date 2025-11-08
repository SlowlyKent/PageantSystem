<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Contestant Contacts Table (Normalized)
 * Contains address and contact information
 * One-to-One relationship with contestants table
 * (Could be One-to-Many if you want multiple addresses per contestant)
 */
class CreateContestantContactsTable extends Migration
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
            'address' => [
                'type'    => 'TEXT',
                'comment' => 'Street address',
            ],
            'barangay' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Barangay/District',
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'province' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'zip_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'contact_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'alternate_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'comment'    => 'Secondary contact number',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'emergency_contact_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'emergency_contact_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
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
        $this->forge->createTable('contestant_contacts');
    }

    public function down()
    {
        $this->forge->dropTable('contestant_contacts');
    }
}
