<?php

namespace App\Models;

use CodeIgniter\Model;

class YardOccupancyModel extends Model
{
    protected $table            = 'yard_occupancy';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['date', 'used_percentage', 'free_percentage', 'inter_used_pct', 'dom_used_pct', 'capacity_teus', 'current_teus'];
}
