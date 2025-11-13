<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Add elimination and ordering fields to rounds table
 */
class AddEliminationFieldsToRounds extends Migration
{
    public function up()
    {
        $fields = [
            'round_order' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
                'default' => 1,
                'after' => 'status',
            ],
            'is_elimination' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default' => 0,
                'null' => false,
                'after' => 'round_order',
            ],
            'is_final' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default' => 0,
                'null' => false,
                'after' => 'is_elimination',
            ],
            'is_locked' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default' => 0,
                'null' => false,
                'after' => 'is_final',
            ],
            'elimination_quota' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'is_locked',
            ],
        ];

        $this->forge->addColumn('rounds', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('rounds', ['round_order', 'is_elimination', 'is_final', 'is_locked', 'elimination_quota']);
    }
}