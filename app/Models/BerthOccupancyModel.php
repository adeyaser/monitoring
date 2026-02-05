<?php

namespace App\Models;

use CodeIgniter\Model;

class BerthOccupancyModel extends Model
{
    protected $table            = 'berth_occupancy';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['date', 'bor_percentage', 'jict_pct', 'koja_pct'];
}
