<?php

namespace App\Controllers;

use App\Models\VesselAlongsideModel;
use App\Models\ThroughputStatsModel;
use App\Models\YardOccupancyModel;
use App\Models\BerthOccupancyModel;
use App\Models\TrafficStatsModel;
use App\Models\BSHPerformanceModel;
use App\Models\BookingDistributionModel;

class Dashboard extends BaseController
{
    protected $vesselModel;
    protected $throughputModel;
    protected $yorModel;
    protected $borModel;
    protected $trafficModel;
    protected $bshModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->vesselModel      = new VesselAlongsideModel();
        $this->throughputModel  = new ThroughputStatsModel();
        $this->yorModel         = new YardOccupancyModel();
        $this->borModel         = new BerthOccupancyModel();
        $this->trafficModel     = new TrafficStatsModel();
        $this->bshModel         = new BSHPerformanceModel();
        $this->bookingModel     = new BookingDistributionModel();
    }

    public function index()
    {
        // Fetch Real Database Data
        $vessels        = $this->vesselModel->findAll();
        $throughput     = $this->throughputModel->first(); 
        $yor            = $this->yorModel->orderBy('date', 'DESC')->first();
        $bor            = $this->borModel->orderBy('date', 'DESC')->first();
        $bsh            = $this->bshModel->orderBy('period_month', 'DESC')->first();
        $booking        = $this->bookingModel->orderBy('id', 'ASC')->findAll(); 
        
        // Handle Empty Data (Defaults)
        if (!$throughput) $throughput = ['inter_box' => 0, 'dom_box' => 0, 'ship_calls' => 0];
        if (!$yor) $yor = ['used_percentage' => 0, 'inter_used_pct' => 0, 'dom_used_pct' => 0, 'capacity_teus' => 0, 'current_teus' => 0];
        if (!$bor) $bor = ['bor_percentage' => 0, 'jict_pct' => 0, 'koja_pct' => 0];
        if (!$bsh) $bsh = ['inter_bsh_actual' => 0, 'inter_bsh_target' => 0, 'dom_bsh_actual' => 0, 'dom_bsh_target' => 0];

        $data = array_merge($this->getViewData(), [
            'title'         => 'Dashboard Monitoring',
            // Pass Data to View
            'vessels'       => $vessels,
            'throughput'    => $throughput,
            'yor'           => $yor,
            'bor'           => $bor,
            'bsh'           => $bsh,
            'bookingDist'   => $booking,
            
            // Legacy/Scalar variables
            'avgYor'        => $yor['used_percentage'],
            'avgBor'        => $bor['bor_percentage'],
            'currentDate'   => date('d F Y'),
            'currentTime'   => date('H:i:s')
        ]);

        return view('dashboard/index', $data);
    }
}
