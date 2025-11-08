<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Round Segments Table
 * Each round can have 1 or 2 segments
 */
class CreateRoundSegmentsTable extends Migration
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
            'round_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'segment_number' => [
                'type'       => 'INT',
                'constraint' => 1,
                'comment'    => 'Segment order (1 or 2)',
            ],
            'segment_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Segment name (e.g., Evening Gown, Talent)',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'weight_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 50.00,
                'comment'    => 'Percentage weight of this segment (e.g., 50% for 2 segments, 100% for 1 segment)',
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
        $this->forge->addForeignKey('round_id', 'rounds', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('round_segments');
    }

    public function down()
    {
        $this->forge->dropTable('round_segments');
    }
}
