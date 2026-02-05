<?php

namespace App\Models;

use CodeIgniter\Model;

class ThroughputStatsModel extends Model
{
    protected $table            = 'throughput_stats';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['period_date', 'inter_teus', 'dom_teus', 'inter_box', 'dom_box', 'ship_calls'];
}
