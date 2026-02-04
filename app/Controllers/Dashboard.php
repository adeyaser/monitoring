<?php

namespace App\Controllers;

use App\Models\TerminalModel;
use App\Models\TerminalOperationModel;
use App\Models\ContainerThroughputModel;
use App\Models\DailyTrendModel;
use App\Models\BerthOccupancyModel;
use App\Models\VesselScheduleModel;

class Dashboard extends BaseController
{
    protected $terminalModel;
    protected $operationModel;
    protected $throughputModel;
    protected $trendModel;
    protected $berthModel;
    protected $vesselModel;

    public function __construct()
    {
        $this->terminalModel = new TerminalModel();
        $this->operationModel = new TerminalOperationModel();
        $this->throughputModel = new ContainerThroughputModel();
        $this->trendModel = new DailyTrendModel();
        $this->berthModel = new BerthOccupancyModel();
        $this->vesselModel = new VesselScheduleModel();
    }

    public function index()
    {
        // Check login
        $loginCheck = $this->requireLogin();
        if ($loginCheck) {
            return $loginCheck;
        }

        // Get today's data
        $throughput = $this->throughputModel->getOverallThroughput();
        $yorStats = $this->operationModel->getYorStats();
        $totalContainers = $this->operationModel->getTotalContainers();
        $berthAvg = $this->berthModel->getAverageOccupancy();
        $vesselStats = $this->vesselModel->getVesselStats();

        // Calculate average YOR (simulate if no data)
        $avgYor = $yorStats['avg_yor'] ?? 63.95;
        $avgBor = $berthAvg['avg_occupancy'] ?? 82.47;

        $data = array_merge($this->getViewData(), [
            'title' => 'Dashboard - Operational Information System',
            'pageTitle' => 'Dashboard',
            'throughput' => $throughput,
            'yorStats' => $yorStats,
            'totalContainers' => $totalContainers,
            'avgYor' => round($avgYor, 2),
            'avgBor' => round($avgBor, 2),
            'vesselStats' => $vesselStats,
            'currentDate' => date('d F Y'),
            'currentTime' => date('H:i:s')
        ]);

        return view('dashboard/index', $data);
    }

    // API Endpoints for AJAX calls
    public function getStats()
    {
        $yorStats = $this->operationModel->getYorStats();
        $totalContainers = $this->operationModel->getTotalContainers();
        $throughput = $this->throughputModel->getOverallThroughput();
        $berthAvg = $this->berthModel->getAverageOccupancy();
        $vesselStats = $this->vesselModel->getVesselStats();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'yor' => $yorStats,
                'containers' => $totalContainers,
                'throughput' => $throughput,
                'berth' => $berthAvg,
                'vessels' => $vesselStats
            ]
        ]);
    }

    public function getTrends()
    {
        $chartData = $this->trendModel->getChartData(14);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $chartData
        ]);
    }

    public function getTerminals()
    {
        $operations = $this->operationModel->getOperationsWithTerminal();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $operations
        ]);
    }

    public function getThroughput()
    {
        $throughputByTerminal = $this->throughputModel->getThroughputByTerminal();
        $overall = $this->throughputModel->getOverallThroughput();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'byTerminal' => $throughputByTerminal,
                'overall' => $overall
            ]
        ]);
    }

    public function getBerthOccupancy()
    {
        $occupancy = $this->berthModel->getBerthOccupancy();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $occupancy
        ]);
    }

    public function getVessels()
    {
        $vessels = $this->vesselModel->getActiveVessels();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $vessels
        ]);
    }
}
