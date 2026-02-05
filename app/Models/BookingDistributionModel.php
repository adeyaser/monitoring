<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingDistributionModel extends Model
{
    protected $table            = 'booking_distribution';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['time_slot', 'export_full', 'import_full', 'export_empty', 'import_empty'];
}
