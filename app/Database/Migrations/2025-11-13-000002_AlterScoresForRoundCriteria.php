<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterScoresForRoundCriteria extends Migration
{
    public function up()
    {
        $forge = $this->forge;

        // Add new column round_criteria_id if it does not exist
        if (!$this->db->fieldExists('round_criteria_id', 'scores')) {
            $forge->addColumn('scores', [
                'round_criteria_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'round_id',
                ],
            ]);
        }

        // Drop legacy foreign keys if present
        $legacyForeignKeys = [
            'scores_segment_id_foreign',
            'scores_criteria_id_foreign',
            'scores_round_criteria_fk',
        ];
        foreach ($legacyForeignKeys as $foreignKey) {
            try {
                $forge->dropForeignKey('scores', $foreignKey);
            } catch (\Throwable $e) {
                // Ignore if key does not exist
            }
        }

        // Drop obsolete columns
        if ($this->db->fieldExists('segment_id', 'scores')) {
            $forge->dropColumn('scores', 'segment_id');
        }
        if ($this->db->fieldExists('criteria_id', 'scores')) {
            $forge->dropColumn('scores', 'criteria_id');
        }

        // Ensure round_criteria_id is required moving forward
        $forge->modifyColumn('scores', [
            'round_criteria_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

        // Add foreign key for new column
        $this->db->query('ALTER TABLE `scores` ADD CONSTRAINT `scores_round_criteria_fk` FOREIGN KEY (`round_criteria_id`) REFERENCES `round_criteria`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');

        // Drop legacy tables no longer used
        foreach (['criteria', 'round_segments'] as $table) {
            if ($this->db->tableExists($table)) {
                $forge->dropTable($table, true);
            }
        }
    }

    public function down()
    {
        $forge = $this->forge;

        // Recreate legacy tables
        if (!$this->db->tableExists('round_segments')) {
            $forge->addField([
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
                'segment_number' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'null'       => false,
                ],
                'segment_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => false,
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'weight_percentage' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'default'    => 50.00,
                    'null'       => false,
                ],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('round_segments', true);
        }

        if (!$this->db->tableExists('criteria')) {
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'segment_id' => [
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
                    'constraint' => 3,
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
                    'constraint' => 2,
                    'default'    => 1,
                    'null'       => false,
                ],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('criteria', true);
        }

        // Drop FK for round criteria and column
        try {
            $forge->dropForeignKey('scores', 'scores_round_criteria_fk');
        } catch (\Throwable $e) {
            // ignore
        }
        if ($this->db->fieldExists('round_criteria_id', 'scores')) {
            $forge->dropColumn('scores', 'round_criteria_id');
        }

        // Re-add legacy columns
        if (!$this->db->fieldExists('segment_id', 'scores')) {
            $forge->addColumn('scores', [
                'segment_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => false,
                    'after'      => 'round_id',
                ],
            ]);
        }
        if (!$this->db->fieldExists('criteria_id', 'scores')) {
            $forge->addColumn('scores', [
                'criteria_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => false,
                    'after'      => 'segment_id',
                ],
            ]);
        }

        // Restore legacy foreign keys
        $this->db->query('ALTER TABLE `scores` ADD CONSTRAINT `scores_segment_id_foreign` FOREIGN KEY (`segment_id`) REFERENCES `round_segments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `scores` ADD CONSTRAINT `scores_criteria_id_foreign` FOREIGN KEY (`criteria_id`) REFERENCES `criteria`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}


