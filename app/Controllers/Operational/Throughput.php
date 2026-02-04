<?php

namespace App\Controllers\Operational;

use App\Controllers\BaseController;
use App\Models\ContainerThroughputModel;
use App\Models\TerminalModel;

class Throughput extends BaseController
{
    protected $throughputModel;
    protected $terminalModel;

    public function __construct()
    {
        $this->throughputModel = new ContainerThroughputModel();
        $this->terminalModel = new TerminalModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('oper_throughput');
        if ($permCheck) return $permCheck;

        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $data = array_merge($this->getViewData(), [
            'title' => 'Data Throughput',
            'pageTitle' => 'Data Throughput',
            'throughputData' => $this->throughputModel->getThroughputByTerminal($date),
            'overall' => $this->throughputModel->getOverallThroughput(),
            'terminals' => $this->terminalModel->getActiveTerminals(),
            'selectedDate' => $date
        ]);

        return view('operational/throughput/index', $data);
    }

    public function create()
    {
        $permCheck = $this->requirePermission('oper_throughput', 'can_create');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Input Throughput',
            'pageTitle' => 'Input Throughput',
            'terminals' => $this->terminalModel->getActiveTerminals()
        ]);

        return view('operational/throughput/form', $data);
    }

    public function store()
    {
        $permCheck = $this->requirePermission('oper_throughput', 'can_create');
        if ($permCheck) return $permCheck;

        $data = [
            'terminal_id' => $this->request->getPost('terminal_id'),
            'report_date' => $this->request->getPost('report_date'),
            'throughput_teus' => $this->request->getPost('throughput_teus') ?? 0,
            'export_box' => $this->request->getPost('export_box') ?? 0,
            'import_box' => $this->request->getPost('import_box') ?? 0,
            'transship_box' => $this->request->getPost('transship_box') ?? 0
        ];

        $this->throughputModel->insert($data);

        return redirect()->to('/operational/throughput')->with('success', 'Data throughput berhasil disimpan');
    }
}
