<?php

namespace App\Models;

use CodeIgniter\Model;

class VesselAlongsideModel extends Model
{
    protected $table            = 'vessels_alongside';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'vessel_name', 'voyage_in', 'voyage_out', 'service_code', 'agent',
        'berthing_time', 'etd', 'loa', 'grt', 'bch_et', 'bch_awt', 
        'bsh_et', 'bsh_awt', 'moves_total', 'moves_completed', 'remaining_moves'
    ];
}
