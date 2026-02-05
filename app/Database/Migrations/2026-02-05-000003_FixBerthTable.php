<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixBerthTable extends Migration
{
    public function up()
    {
        // Drop old table if exists
        $this->forge->dropTable('berth_occupancy', true);

        // Recreate with new schema
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'date' => ['type' => 'DATE'],
            'bor_percentage' => ['type' => 'FLOAT'],
            'jict_pct' => ['type' => 'FLOAT'],
            'koja_pct' => ['type' => 'FLOAT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('berth_occupancy', true);
    }

    public function down()
    {
        $this->forge->dropTable('berth_occupancy', true);
    }
}
