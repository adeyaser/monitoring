<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;
use App\Models\TerminalOperationModel;
use App\Models\ContainerThroughputModel;
use App\Models\TerminalModel;

class Monthly extends BaseController
{
    protected $operationModel;
    protected $throughputModel;
    protected $terminalModel;

    public function __construct()
    {
        $this->operationModel = new TerminalOperationModel();
        $this->throughputModel = new ContainerThroughputModel();
        $this->terminalModel = new TerminalModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('report_monthly');
        if ($permCheck) return $permCheck;

        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        // Get monthly summary
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $data = array_merge($this->getViewData(), [
            'title' => 'Laporan Bulanan',
            'pageTitle' => 'Laporan Bulanan',
            'terminals' => $this->terminalModel->getActiveTerminals(),
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'months' => [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ],
            'years' => range(date('Y') - 5, date('Y'))
        ]);

        return view('reports/monthly', $data);
    }
}
