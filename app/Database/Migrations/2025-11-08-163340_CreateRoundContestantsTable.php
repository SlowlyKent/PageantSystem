<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Round Contestants Table
 * Tracks contestant participation and elimination status per round.
 */
class CreateRoundContestantsTable extends Migration
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
            'contestant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Reference to contestants.id',
            ],
            'state' => [
                'type'       => "ENUM('active','eliminated','advanced')",
                'default'    => 'active',
                'null'       => false,
                'comment'    => 'Contestant state in this round',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('round_id', 'rounds', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('contestant_id', 'contestants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['round_id', 'contestant_id']);
        $this->forge->createTable('round_contestants');
    }

    public function down()
    {
        $this->forge->dropTable('round_contestants');
    }
}
