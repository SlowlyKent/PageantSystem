<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Round Judges Table
 * Tracks judge assignments and completion per round.
 */
class CreateRoundJudgesTable extends Migration
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
                'null'       => false,
                'comment'    => 'Reference to rounds.id',
            ],
            'judge_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Reference to users.id (judges)',
            ],
            'assigned' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
                'null'       => false,
                'comment'    => 'Whether the judge is assigned to this round',
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When the judge finished scoring for this round',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('round_id', 'rounds', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('judge_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['round_id', 'judge_id']);
        $this->forge->createTable('round_judges');
    }

    public function down()
    {
        $this->forge->dropTable('round_judges');
    }
}
