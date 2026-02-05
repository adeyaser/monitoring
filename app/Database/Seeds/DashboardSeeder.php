<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DashboardSeeder extends Seeder
{
    public function run()
    {
        // Truncate tables to prevent duplicates
        $this->db->table('vessels_alongside')->emptyTable();
        $this->db->table('throughput_stats')->emptyTable();
        $this->db->table('yard_occupancy')->emptyTable();
        $this->db->table('berth_occupancy')->emptyTable(); // Now exists correctly
        $this->db->table('bsh_performance')->emptyTable();
        $this->db->table('booking_distribution')->emptyTable();

        // 1. Vessels Alongside Data
        $vessels = [
            [
                'vessel_name' => 'MSC FALCON III',
                'voyage_in' => 'HC601R', 
                'voyage_out' => 'HC602R',
                'service_code' => 'KP1',
                'agent' => 'MSC',
                'berthing_time' => '2026-02-04 10:30:00',
                'etd' => '2026-02-05 14:00:00',
                'loa' => 294,
                'grt' => 85000,
                'bch_et' => 24.5,
                'bch_awt' => 18.2,
                'bsh_et' => 35.0,
                'bsh_awt' => 30.5,
                'moves_total' => 1500,
                'moves_completed' => 850,
                'remaining_moves' => 650,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'vessel_name' => 'SINAR SUNDA',
                'voyage_in' => '115S', 
                'voyage_out' => '116N',
                'service_code' => 'CS1',
                'agent' => 'SAMUDRA',
                'berthing_time' => '2026-02-05 02:00:00',
                'etd' => '2026-02-06 08:00:00',
                'loa' => 210,
                'grt' => 32000,
                'bch_et' => 18.0,
                'bch_awt' => 12.5,
                'bsh_et' => 28.0,
                'bsh_awt' => 22.0,
                'moves_total' => 800,
                'moves_completed' => 200,
                'remaining_moves' => 600,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('vessels_alongside')->insertBatch($vessels);

        // 2. Throughput Data (January 2026)
        $throughput = [
            [
                'period_date' => '2026-01-01',
                'inter_teus' => 56000,
                'dom_teus' => 12000,
                'inter_box' => 37732, // From Image
                'dom_box' => 6528,    // From Image
                'ship_calls' => 205,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('throughput_stats')->insertBatch($throughput);

        // 3. YOR (Yard Occupancy)
        $yor = [
            [
                'date' => date('Y-m-d'),
                'used_percentage' => 82.47,
                'free_percentage' => 17.53,
                'inter_used_pct' => 81.29,
                'dom_used_pct' => 94.25,
                'capacity_teus' => 65000,
                'current_teus' => 54000,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('yard_occupancy')->insertBatch($yor);

        // 4. BOR (Berth Occupancy)
        $bor = [
            [
                'date' => date('Y-m-d'),
                'bor_percentage' => 65.40,
                'jict_pct' => 68.20,
                'koja_pct' => 55.10,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('berth_occupancy')->insertBatch($bor);

        // 5. BSH Performance
        $bsh = [
            [
                'period_month' => '2026-01',
                'inter_bsh_actual' => 31,
                'inter_bsh_target' => 42,
                'dom_bsh_actual' => 17,
                'dom_bsh_target' => 19,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('bsh_performance')->insertBatch($bsh);

        // 6. Booking Distribution (Dummy Data based on Image)
        $booking = [
            ['time_slot' => '00-03', 'export_full' => 113, 'import_full' => 43, 'export_empty' => 2, 'import_empty' => 0],
            ['time_slot' => '03-06', 'export_full' => 75, 'import_full' => 31, 'export_empty' => 1, 'import_empty' => 0],
            ['time_slot' => '06-09', 'export_full' => 120, 'import_full' => 80, 'export_empty' => 1, 'import_empty' => 10],
            ['time_slot' => '09-12', 'export_full' => 130, 'import_full' => 170, 'export_empty' => 23, 'import_empty' => 18],
            ['time_slot' => '12-15', 'export_full' => 49, 'import_full' => 114, 'export_empty' => 21, 'import_empty' => 18],
            ['time_slot' => '15-18', 'export_full' => 0, 'import_full' => 0, 'export_empty' => 0, 'import_empty' => 0],
            ['time_slot' => '18-21', 'export_full' => 0, 'import_full' => 0, 'export_empty' => 0, 'import_empty' => 0],
            ['time_slot' => '21-24', 'export_full' => 0, 'import_full' => 1, 'export_empty' => 0, 'import_empty' => 0],
        ];
        
        // Add Created At
        foreach($booking as &$row) { $row['created_at'] = date('Y-m-d H:i:s'); }
        
        $this->db->table('booking_distribution')->insertBatch($booking);
    }
}
