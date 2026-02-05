<?php

namespace App\Models;

use CodeIgniter\Model;

class BSHPerformanceModel extends Model
{
    protected $table            = 'bsh_performance';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['period_month', 'inter_bsh_actual', 'inter_bsh_target', 'dom_bsh_actual', 'dom_bsh_target'];
}
