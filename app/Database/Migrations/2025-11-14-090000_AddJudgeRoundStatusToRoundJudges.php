<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJudgeRoundStatusToRoundJudges extends Migration
{
    public function up()
    {
        $this->forge->addColumn('round_judges', [
            'judge_round_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
                'after'      => 'assigned',
                'comment'    => 'pending|completed state per judge per round',
            ],
        ]);

        // Ensure existing records get a compatible status value
        $db = \Config\Database::connect();
        $builder = $db->table('round_judges');

        // Completed rows should be marked completed, others pending
        $builder
            ->set('judge_round_status', 'completed')
            ->where('completed_at IS NOT NULL', null, false)
            ->update();

        $builder
            ->set('judge_round_status', 'pending')
            ->where('completed_at IS NULL', null, false)
            ->update();
    }

    public function down()
    {
        $this->forge->dropColumn('round_judges', 'judge_round_status');
    }
}

