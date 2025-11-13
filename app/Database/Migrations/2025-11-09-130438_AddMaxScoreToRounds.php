<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Add max_score field to rounds table
 * This field represents the maximum total score for the entire round
 */
class AddMaxScoreToRounds extends Migration
{
    public function up()
    {
        $fields = [
            'max_score' => [
                'type'       => 'DECIMAL',
                'constraint' => '6,2',
                'default'    => 100.00,
                'null'       => false,
                'after'      => 'segment_count',
                'comment'    => 'Maximum total score for the entire round (all segments combined)'
            ]
        ];
        
        $this->forge->addColumn('rounds', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('rounds', 'max_score');
    }
}