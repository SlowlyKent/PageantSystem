<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoundCriteriaTable extends Migration
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
            ],
            'criteria_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_score' => [
                'type'       => 'INT',
                'constraint' => 4,
                'default'    => 100,
                'null'       => false,
            ],
            'percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => false,
            ],
            'order' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 1,
                'null'       => false,
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
        $this->forge->createTable('round_criteria', true);
    }

    public function down()
    {
        $this->forge->dropTable('round_criteria', true);
    }
}


