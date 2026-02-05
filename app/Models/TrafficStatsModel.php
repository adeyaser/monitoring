<?php

namespace App\Models;

use CodeIgniter\Model;

class TrafficStatsModel extends Model
{
    protected $table            = 'traffic_stats';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['stats_date', 'avg_trt_minutes', 'rec_inter_vol', 'rec_dom_vol', 'del_inter_vol', 'del_dom_vol', 'longest_truck_gate_1'];
}
