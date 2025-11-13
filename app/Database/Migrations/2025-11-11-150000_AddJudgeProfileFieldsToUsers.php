<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJudgeProfileFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'judge_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'full_name',
            ],
            'judge_organization' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'judge_title',
            ],
            'judge_achievements' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'judge_organization',
            ],
            'judge_biography' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'judge_achievements',
            ],
            'judge_intro_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'judge_biography',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'judge_title',
            'judge_organization',
            'judge_achievements',
            'judge_biography',
            'judge_intro_notes',
        ]);
    }
}

