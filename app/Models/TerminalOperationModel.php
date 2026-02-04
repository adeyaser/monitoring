<?php

namespace App\Models;

use CodeIgniter\Model;

class TerminalOperationModel extends Model
{
    protected $table = 'terminal_operations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'terminal_id',
        'report_date',
        'yor_standar',
        'yor_eksisting',
        'container_cy',
        'container_longstay_3',
        'container_longstay_30',
        'kapasitas_harian_cy',
        'jumlah_kapal',
        'bongkar_muat',
        'gatepass_total',
        'gatepass_sudah',
        'gatepass_belum',
        'param_receiving_delivery',
        'status_yor'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getOperationsWithTerminal($date = null)
    {
        $builder = $this->db->table($this->table . ' o');
        $builder->select('o.*, t.terminal_code, t.terminal_name, t.regional');
        $builder->join('terminals t', 't.id = o.terminal_id');
        
        if ($date) {
            $builder->where('o.report_date', $date);
        } else {
            $builder->where('o.report_date', date('Y-m-d'));
        }
        
        $builder->orderBy('t.id', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getYorStats($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $builder = $this->db->table($this->table);
        $builder->selectAvg('yor_eksisting', 'avg_yor');
        $builder->selectMax('yor_eksisting', 'max_yor');
        $builder->selectMin('yor_eksisting', 'min_yor');
        $builder->select("
            SUM(CASE WHEN status_yor = 'AMAN' THEN 1 ELSE 0 END) as count_aman,
            SUM(CASE WHEN status_yor = 'WARNING' THEN 1 ELSE 0 END) as count_warning,
            SUM(CASE WHEN status_yor = 'CRITICAL' THEN 1 ELSE 0 END) as count_critical
        ");
        $builder->where('report_date', $date);
        
        return $builder->get()->getRowArray();
    }

    public function getTotalContainers($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $builder = $this->db->table($this->table);
        $builder->selectSum('container_cy', 'total_container');
        $builder->selectSum('container_longstay_3', 'total_longstay_3');
        $builder->selectSum('container_longstay_30', 'total_longstay_30');
        $builder->selectSum('jumlah_kapal', 'total_kapal');
        $builder->selectSum('bongkar_muat', 'total_bongkar_muat');
        $builder->selectSum('gatepass_total', 'total_gatepass');
        $builder->where('report_date', $date);
        
        return $builder->get()->getRowArray();
    }

    public function getByTerminalAndDate($terminalId, $date)
    {
        return $this->where('terminal_id', $terminalId)
                    ->where('report_date', $date)
                    ->first();
    }
}
