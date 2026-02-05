<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDashboardTables extends Migration
{
    public function up()
    {
        // 1. Vessels Alongside Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'vessel_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'voyage_in' => ['type' => 'VARCHAR', 'constraint' => 50],
            'voyage_out' => ['type' => 'VARCHAR', 'constraint' => 50],
            'service_code' => ['type' => 'VARCHAR', 'constraint' => 20],
            'agent' => ['type' => 'VARCHAR', 'constraint' => 100],
            'berthing_time' => ['type' => 'DATETIME'],
            'etd' => ['type' => 'DATETIME'],
            'loa' => ['type' => 'FLOAT'],
            'grt' => ['type' => 'FLOAT'],
            'bch_et' => ['type' => 'FLOAT'], // BCH Effective Time
            'bch_awt' => ['type' => 'FLOAT'], // BCH All Working Time
            'bsh_et' => ['type' => 'FLOAT'],
            'bsh_awt' => ['type' => 'FLOAT'],
            'moves_total' => ['type' => 'INT'],
            'moves_completed' => ['type' => 'INT'],
            'remaining_moves' => ['type' => 'INT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('vessels_alongside', true);

        // 2. Throughput Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'period_date' => ['type' => 'DATE'], // Monthly/Daily
            'inter_teus' => ['type' => 'INT', 'default' => 0],
            'dom_teus' => ['type' => 'INT', 'default' => 0],
            'inter_box' => ['type' => 'INT', 'default' => 0],
            'dom_box' => ['type' => 'INT', 'default' => 0],
            'ship_calls' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('throughput_stats', true);

        // 3. YOR (Yard Occupancy)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'date' => ['type' => 'DATE'],
            'used_percentage' => ['type' => 'FLOAT'], // 82.47
            'free_percentage' => ['type' => 'FLOAT'],
            'inter_used_pct' => ['type' => 'FLOAT'],
            'dom_used_pct' => ['type' => 'FLOAT'],
            'capacity_teus' => ['type' => 'INT'],
            'current_teus' => ['type' => 'INT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('yard_occupancy', true);

        // 4. BOR (Berth Occupancy)
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

        // 5. Traffic / TRT
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'stats_date' => ['type' => 'DATE'],
            'avg_trt_minutes' => ['type' => 'FLOAT'],
            'rec_inter_vol' => ['type' => 'FLOAT'],
            'rec_dom_vol' => ['type' => 'FLOAT'],
            'del_inter_vol' => ['type' => 'FLOAT'],
            'del_dom_vol' => ['type' => 'FLOAT'],
            'longest_truck_gate_1' => ['type' => 'INT'], 
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('traffic_stats', true);

        // 6. BSH Performance
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'period_month' => ['type' => 'VARCHAR', 'constraint' => 7], // 2026-01
            'inter_bsh_actual' => ['type' => 'FLOAT'],
            'inter_bsh_target' => ['type' => 'FLOAT'],
            'dom_bsh_actual' => ['type' => 'FLOAT'],
            'dom_bsh_target' => ['type' => 'FLOAT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('bsh_performance', true);

        // 7. Booking Distribution
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'time_slot' => ['type' => 'VARCHAR', 'constraint' => 20], // 00-03, etc
            'export_full' => ['type' => 'INT'],
            'import_full' => ['type' => 'INT'],
            'export_empty' => ['type' => 'INT'],
            'import_empty' => ['type' => 'INT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('booking_distribution', true);
    }

    public function down()
    {
        $this->forge->dropTable('booking_distribution', true);
        $this->forge->dropTable('bsh_performance', true);
        $this->forge->dropTable('traffic_stats', true);
        $this->forge->dropTable('berth_occupancy', true);
        $this->forge->dropTable('yard_occupancy', true);
        $this->forge->dropTable('throughput_stats', true);
        $this->forge->dropTable('vessels_alongside', true);
    }
}
