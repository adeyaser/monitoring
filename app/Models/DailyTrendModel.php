<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyTrendModel extends Model
{
    protected $table = 'daily_trends';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'terminal_id',
        'report_date',
        'trend_type',
        'value'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getTrends($type, $days = 14, $terminalId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('report_date, value');
        $builder->where('trend_type', $type);
        $builder->where('report_date >=', date('Y-m-d', strtotime("-{$days} days")));
        
        if ($terminalId) {
            $builder->where('terminal_id', $terminalId);
        } else {
            $builder->where('terminal_id IS NULL');
        }
        
        $builder->orderBy('report_date', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getAllTrends($days = 14)
    {
        $types = ['THROUGHPUT', 'YOR', 'VESSEL', 'PRODUCTIVITY'];
        $result = [];
        
        foreach ($types as $type) {
            $result[$type] = $this->getTrends($type, $days);
        }
        
        return $result;
    }

    public function getChartData($days = 14)
    {
        $trends = $this->getAllTrends($days);
        $labels = [];
        $datasets = [];
        
        // Get labels from THROUGHPUT data
        if (!empty($trends['THROUGHPUT'])) {
            foreach ($trends['THROUGHPUT'] as $item) {
                $labels[] = date('d M', strtotime($item['report_date']));
            }
        }
        
        $colors = [
            'THROUGHPUT' => '#0066cc',
            'YOR' => '#28a745',
            'VESSEL' => '#ffc107',
            'PRODUCTIVITY' => '#dc3545'
        ];
        
        foreach ($trends as $type => $data) {
            $values = array_column($data, 'value');
            $datasets[] = [
                'label' => ucfirst(strtolower($type)),
                'data' => $values,
                'borderColor' => $colors[$type],
                'backgroundColor' => $colors[$type] . '33',
                'fill' => false,
                'tension' => 0.4
            ];
        }
        
        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }
}
