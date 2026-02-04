<?php

namespace App\Models;

use CodeIgniter\Model;

class VesselScheduleModel extends Model
{
    protected $table = 'vessel_schedules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'terminal_id',
        'vessel_name',
        'vessel_code',
        'arrival_date',
        'departure_date',
        'berth_position',
        'status',
        'cargo_type'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveVessels()
    {
        $builder = $this->db->table($this->table . ' v');
        $builder->select('v.*, t.terminal_code, t.terminal_name');
        $builder->join('terminals t', 't.id = v.terminal_id');
        $builder->whereIn('v.status', ['SCHEDULED', 'BERTHING', 'LOADING', 'UNLOADING']);
        $builder->orderBy('v.arrival_date', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getVesselsByTerminal($terminalId)
    {
        return $this->where('terminal_id', $terminalId)
                    ->whereIn('status', ['SCHEDULED', 'BERTHING', 'LOADING', 'UNLOADING'])
                    ->orderBy('arrival_date', 'ASC')
                    ->findAll();
    }

    public function getVesselStats()
    {
        $builder = $this->db->table($this->table);
        $builder->select("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'SCHEDULED' THEN 1 ELSE 0 END) as scheduled,
            SUM(CASE WHEN status = 'BERTHING' THEN 1 ELSE 0 END) as berthing,
            SUM(CASE WHEN status = 'LOADING' THEN 1 ELSE 0 END) as loading,
            SUM(CASE WHEN status = 'UNLOADING' THEN 1 ELSE 0 END) as unloading
        ");
        $builder->whereIn('status', ['SCHEDULED', 'BERTHING', 'LOADING', 'UNLOADING']);
        
        return $builder->get()->getRowArray();
    }

    public function getTodayArrivals()
    {
        return $this->where('DATE(arrival_date)', date('Y-m-d'))
                    ->findAll();
    }
}
