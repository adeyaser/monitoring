<?php

namespace App\Models;

use CodeIgniter\Model;

class ContainerThroughputModel extends Model
{
    protected $table = 'container_throughput';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'terminal_id',
        'report_date',
        'throughput_teus',
        'export_box',
        'import_box',
        'transship_box'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getTotalThroughput($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $builder = $this->db->table($this->table);
        $builder->selectSum('throughput_teus', 'total_teus');
        $builder->selectSum('export_box', 'total_export');
        $builder->selectSum('import_box', 'total_import');
        $builder->selectSum('transship_box', 'total_transship');
        $builder->where('report_date', $date);
        
        return $builder->get()->getRowArray();
    }

    public function getOverallThroughput($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        return $this->where('terminal_id', null)
                    ->where('report_date', $date)
                    ->first();
    }

    public function getThroughputByTerminal($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $builder = $this->db->table($this->table . ' ct');
        $builder->select('ct.*, t.terminal_code, t.terminal_name');
        $builder->join('terminals t', 't.id = ct.terminal_id', 'left');
        $builder->where('ct.report_date', $date);
        $builder->where('ct.terminal_id IS NOT NULL');
        $builder->orderBy('ct.throughput_teus', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getYearToDateThroughput($year = null)
    {
        $year = $year ?: date('Y');
        
        $builder = $this->db->table($this->table);
        $builder->selectSum('throughput_teus', 'total_teus');
        $builder->where("YEAR(report_date)", $year);
        
        return $builder->get()->getRowArray();
    }
}
