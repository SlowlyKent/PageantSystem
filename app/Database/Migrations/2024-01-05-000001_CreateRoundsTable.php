<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Rounds Table
 * Stores pageant rounds (e.g., Round 1, Round 2, Finals)
 */
class CreateRoundsTable extends Migration
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
            'round_number' => [
                'type'       => 'INT',
                'constraint' => 3,
                'comment'    => 'Round sequence (1, 2, 3, etc.)',
            ],
            'round_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Round name (e.g., Preliminary, Semi-Finals, Finals)',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Round description',
            ],
            'segment_count' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => 'Number of segments (1 or 2)',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'completed'],
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
        $this->forge->addUniqueKey('round_number');
        $this->forge->createTable('rounds');
    }

    public function down()
    {
        $this->forge->dropTable('rounds');
    }
}
