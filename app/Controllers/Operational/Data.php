<?php

namespace App\Controllers\Operational;

use App\Controllers\BaseController;
use App\Models\TerminalOperationModel;
use App\Models\TerminalModel;

class Data extends BaseController
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
        $permCheck = $this->requirePermission('oper_data');
        if ($permCheck) return $permCheck;

        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $data = array_merge($this->getViewData(), [
            'title' => 'Data Operasional',
            'pageTitle' => 'Data Operasional',
            'operations' => $this->operationModel->getOperationsWithTerminal($date),
            'selectedDate' => $date
        ]);

        return view('operational/data/index', $data);
    }

    public function create()
    {
        $permCheck = $this->requirePermission('oper_data', 'can_create');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Input Data Operasional',
            'pageTitle' => 'Input Data Operasional',
            'terminals' => $this->terminalModel->getActiveTerminals()
        ]);

        return view('operational/data/form', $data);
    }

    public function store()
    {
        $permCheck = $this->requirePermission('oper_data', 'can_create');
        if ($permCheck) return $permCheck;

        $terminalId = $this->request->getPost('terminal_id');
        $reportDate = $this->request->getPost('report_date');

        // Check if data already exists
        $existing = $this->operationModel->getByTerminalAndDate($terminalId, $reportDate);
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Data untuk terminal dan tanggal ini sudah ada');
        }

        $data = $this->getOperationData();
        $this->operationModel->insert($data);

        return redirect()->to('/operational/data')->with('success', 'Data operasional berhasil disimpan');
    }

    public function edit($id)
    {
        $permCheck = $this->requirePermission('oper_data', 'can_update');
        if ($permCheck) return $permCheck;

        $operation = $this->operationModel->find($id);
        if (!$operation) {
            return redirect()->to('/operational/data')->with('error', 'Data tidak ditemukan');
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Edit Data Operasional',
            'pageTitle' => 'Edit Data Operasional',
            'operation' => $operation,
            'terminals' => $this->terminalModel->getActiveTerminals()
        ]);

        return view('operational/data/form', $data);
    }

    public function update($id)
    {
        $permCheck = $this->requirePermission('oper_data', 'can_update');
        if ($permCheck) return $permCheck;

        $operation = $this->operationModel->find($id);
        if (!$operation) {
            return redirect()->to('/operational/data')->with('error', 'Data tidak ditemukan');
        }

        $data = $this->getOperationData();
        unset($data['terminal_id']); // Don't update terminal
        unset($data['report_date']); // Don't update date

        $this->operationModel->update($id, $data);

        return redirect()->to('/operational/data')->with('success', 'Data operasional berhasil diperbarui');
    }

    public function delete($id)
    {
        $permCheck = $this->requirePermission('oper_data', 'can_delete');
        if ($permCheck) return $permCheck;

        $operation = $this->operationModel->find($id);
        if (!$operation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $this->operationModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    private function getOperationData()
    {
        // Calculate YOR eksisting
        $containerCy = (int) $this->request->getPost('container_cy');
        $kapasitasHarian = (int) $this->request->getPost('kapasitas_harian_cy');
        $yorEksisting = $kapasitasHarian > 0 ? ($containerCy / $kapasitasHarian) * 100 : null;

        // Determine status
        $status = 'AMAN';
        if ($yorEksisting !== null) {
            if ($yorEksisting >= 85) {
                $status = 'CRITICAL';
            } elseif ($yorEksisting >= 70) {
                $status = 'WARNING';
            }
        }

        return [
            'terminal_id' => $this->request->getPost('terminal_id'),
            'report_date' => $this->request->getPost('report_date'),
            'yor_standar' => $this->request->getPost('yor_standar') ?? 65,
            'yor_eksisting' => $yorEksisting,
            'container_cy' => $containerCy,
            'container_longstay_3' => $this->request->getPost('container_longstay_3') ?? 0,
            'container_longstay_30' => $this->request->getPost('container_longstay_30') ?? 0,
            'kapasitas_harian_cy' => $kapasitasHarian,
            'jumlah_kapal' => $this->request->getPost('jumlah_kapal') ?? 0,
            'bongkar_muat' => $this->request->getPost('bongkar_muat') ?? 0,
            'gatepass_total' => $this->request->getPost('gatepass_total') ?? 0,
            'gatepass_sudah' => $this->request->getPost('gatepass_sudah') ?? 0,
            'gatepass_belum' => $this->request->getPost('gatepass_belum') ?? 0,
            'param_receiving_delivery' => $this->request->getPost('param_receiving_delivery') ?? 0,
            'status_yor' => $status
        ];
    }

    public function apiList()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $operations = $this->operationModel->getOperationsWithTerminal($date);
        return $this->response->setJSON(['success' => true, 'data' => $operations]);
    }
    public function import()
    {
        // Simple permission check (can be refined)
        // $permCheck = $this->requirePermission('oper_data', 'can_create');
        
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
                
                // date logic: Use row date if available, otherwise default
                $rowDate = $date;
                if (!empty($row['Tanggal'])) {
                     // Try to parse excel date if needed, assuming YYYY-MM-DD or readable format
                     $parsed = strtotime($row['Tanggal']);
                     if ($parsed) $rowDate = date('Y-m-d', $parsed);
                }

                // Prepared Data
                $dataToSave = [
                    'date' => $rowDate,
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
                $existing = $this->operationModel->where(['date' => $rowDate, 'terminal_id' => $terminal['id']])->first();
                
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
}
