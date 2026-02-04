<?php

namespace App\Models;

use CodeIgniter\Model;

class BerthOccupancyModel extends Model
{
    protected $table = 'berth_occupancy';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'terminal_id',
        'berth_name',
        'report_date',
        'occupancy_rate',
        'vessel_count'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getBerthOccupancy($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $builder = $this->db->table($this->table . ' b');
        $builder->select('b.*, t.terminal_code, t.terminal_name');
        $builder->join('terminals t', 't.id = b.terminal_id');
        $builder->where('b.report_date', $date);
        $builder->orderBy('b.occupancy_rate', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getAverageOccupancy($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $builder = $this->db->table($this->table);
        $builder->selectAvg('occupancy_rate', 'avg_occupancy');
        $builder->selectSum('vessel_count', 'total_vessels');
        $builder->where('report_date', $date);
        
        return $builder->get()->getRowArray();
    }

    public function getOccupancyByTerminal($terminalId, $date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        return $this->where('terminal_id', $terminalId)
                    ->where('report_date', $date)
                    ->findAll();
    }
}
