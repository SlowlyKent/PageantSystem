<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Criteria Table
 * Judging criteria for each segment
 */
class CreateCriteriaTable extends Migration
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
            'segment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'criteria_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'comment'    => 'Criteria name (e.g., Poise, Beauty, Stage Presence)',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Criteria description/guidelines',
            ],
            'max_score' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 100,
                'comment'    => 'Maximum score for this criteria',
            ],
            'percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'comment'    => 'Percentage weight of this criteria within segment',
            ],
            'order' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
                'comment'    => 'Display order',
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
        $this->forge->addForeignKey('segment_id', 'round_segments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('criteria');
    }

    public function down()
    {
        $this->forge->dropTable('criteria');
    }
}
