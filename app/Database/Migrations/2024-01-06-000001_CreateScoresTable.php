<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Scores Table
 * Stores individual scores from judges for each contestant per criteria
 */
class CreateScoresTable extends Migration
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
            'judge_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'User ID of the judge',
            ],
            'contestant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'round_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'segment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'criteria_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'comment'    => 'Score given by judge (e.g., 85.50)',
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Optional judge remarks/comments',
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
        $this->forge->addForeignKey('judge_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('contestant_id', 'contestants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('round_id', 'rounds', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('segment_id', 'round_segments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('criteria_id', 'criteria', 'id', 'CASCADE', 'CASCADE');
        
        // Unique constraint: one score per judge per contestant per criteria
        $this->forge->addUniqueKey(['judge_id', 'contestant_id', 'criteria_id']);
        
        $this->forge->createTable('scores');
    }

    public function down()
    {
        $this->forge->dropTable('scores');
    }
}
