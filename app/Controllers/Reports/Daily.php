<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;
use App\Models\TerminalOperationModel;
use App\Models\TerminalModel;

class Daily extends BaseController
{
    protected $operationModel;
    protected $terminalModel;

    public function __construct()
    {
        $this->operationModel = new TerminalOperationModel();
        $this->terminalModel = new TerminalModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('report_daily');
        if ($permCheck) return $permCheck;

        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $terminalId = $this->request->getGet('terminal_id');

        $operations = $this->operationModel->getOperationsWithTerminal($date);
        
        if ($terminalId) {
            $operations = array_filter($operations, function($op) use ($terminalId) {
                return $op['terminal_id'] == $terminalId;
            });
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Laporan Harian',
            'pageTitle' => 'Laporan Harian',
            'operations' => $operations,
            'terminals' => $this->terminalModel->getActiveTerminals(),
            'selectedDate' => $date,
            'selectedTerminal' => $terminalId
        ]);

        return view('reports/daily', $data);
    }

    public function export()
    {
        $permCheck = $this->requirePermission('report_daily');
        if ($permCheck) return $permCheck;

        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $operations = $this->operationModel->getOperationsWithTerminal($date);

        // Generate CSV
        $filename = 'laporan_harian_' . $date . '.csv';
        
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['No', 'Terminal', 'YOR Standar', 'YOR Eksisting', 'Container CY', 'Longstay > 3', 'Longstay > 30', 'Kapasitas Harian', 'Jumlah Kapal', 'Bongkar/Muat', 'Gatepass', 'Status']);
        
        // Data
        $no = 1;
        foreach ($operations as $op) {
            fputcsv($output, [
                $no++,
                $op['terminal_name'] ?? '',
                $op['yor_standar'],
                $op['yor_eksisting'] ?? '-',
                $op['container_cy'],
                $op['container_longstay_3'],
                $op['container_longstay_30'],
                $op['kapasitas_harian_cy'],
                $op['jumlah_kapal'],
                $op['bongkar_muat'],
                $op['gatepass_total'],
                $op['status_yor']
            ]);
        }
        
        fclose($output);
        
        return $this->response;
    }

    public function import()
    {
        // Simple permission check (can be refined)
        // $permCheck = $this->requirePermission('report_daily_edit');
        
        $json = $this->request->getJSON();
        
        if (!$json || empty($json->data)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
        }
        
        $date = $json->date ?? date('Y-m-d');
        $importData = $json->data;
        
        // Transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $count = 0;
            foreach ($importData as $row) {
                // Convert stdClass to array just in case
                $row = (array)$row;
                
                // Map Data from Excel Header to DB Columns
                $terminalName = $row['Terminal'] ?? null;
                if (!$terminalName) continue;
                
                $terminal = $this->terminalModel->where('terminal_name', $terminalName)->first();
                if (!$terminal) continue;
                
                // Helper to clean number string
                $cleanNum = function($val) {
                    return intval(str_replace([',', '.'], '', $val ?? 0));
                };
                
                // Prepared Data
                $dataToSave = [
                    'date' => $date,
                    'terminal_id' => $terminal['id'],
                    'yor_standar' => floatval(str_replace('%', '', $row['YOR Standar'] ?? 0)),
                    'yor_eksisting' => isset($row['YOR Eksisting']) && $row['YOR Eksisting'] !== '-' ? floatval(str_replace('%', '', $row['YOR Eksisting'])) : null,
                    'container_cy' => $cleanNum($row['Container CY'] ?? 0),
                    'container_longstay_3' => $cleanNum($row['Longstay > 3'] ?? 0),
                    'container_longstay_30' => $cleanNum($row['Longstay > 30'] ?? 0),
                    'kapasitas_harian_cy' => $cleanNum($row['Kapasitas Harian'] ?? 0),
                    'jumlah_kapal' => intval($row['Jumlah Kapal'] ?? 0),
                    'bongkar_muat' => $cleanNum($row['Bongkar/Muat'] ?? 0),
                    'gatepass_total' => $cleanNum($row['Gatepass'] ?? 0),
                    'status_yor' => $row['Status'] ?? 'AMAN',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                // Check existing
                $existing = $this->operationModel->where(['date' => $date, 'terminal_id' => $terminal['id']])->first();
                
                if ($existing) {
                    $this->operationModel->update($existing['id'], $dataToSave);
                } else {
                    $dataToSave['created_at'] = date('Y-m-d H:i:s');
                    $this->operationModel->insert($dataToSave);
                }
                $count++;
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Database error during transaction']);
            }
            
            return $this->response->setJSON(['success' => true, 'message' => "Berhasil mengimport {$count} data terminal"]);
            
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function downloadTemplate()
    {
        $filename = 'template_import_harian.csv';
        
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header sesuai format Import
        fputcsv($output, ['No', 'Terminal', 'YOR Standar', 'YOR Eksisting', 'Container CY', 'Longstay > 3', 'Longstay > 30', 'Kapasitas Harian', 'Jumlah Kapal', 'Bongkar/Muat', 'Gatepass', 'Status']);
        
        // Contoh Data Dummy (1 Baris)
        fputcsv($output, [
            1,
            'JICT',
            '65%',
            '45%',
            '15500',
            '0',
            '0',
            '25000',
            '5',
            '1200',
            '800',
            'AMAN'
        ]);
        
        fclose($output);
        return $this->response;
    }
}
