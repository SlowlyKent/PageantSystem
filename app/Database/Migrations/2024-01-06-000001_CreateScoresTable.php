<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Scores Table
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
                'null'       => false,
            ],
            'contestant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'round_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'round_criteria_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Linked criterion for this round',
            ],
            'score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => false,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('scores');
    }

    public function down()
    {
        $this->forge->dropTable('scores');
    }
}