<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateRoundsTable extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('segment_count', 'rounds')) {
            $this->forge->dropColumn('rounds', 'segment_count');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('segment_count', 'rounds')) {
            $this->forge->addColumn('rounds', [
                'segment_count' => [
                    'type'       => 'INT',
                    'constraint' => 2,
                    'default'    => 1,
                    'null'       => false,
                    'after'      => 'description',
                ],
            ]);
        }
    }
}


