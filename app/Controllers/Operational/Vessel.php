<?php

namespace App\Controllers\Operational;

use App\Controllers\BaseController;
use App\Models\VesselScheduleModel;
use App\Models\TerminalModel;

class Vessel extends BaseController
{
    protected $vesselModel;
    protected $terminalModel;

    public function __construct()
    {
        $this->vesselModel = new VesselScheduleModel();
        $this->terminalModel = new TerminalModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('oper_vessel');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Vessel Schedule',
            'pageTitle' => 'Vessel Schedule',
            'vessels' => $this->vesselModel->getActiveVessels(),
            'vesselStats' => $this->vesselModel->getVesselStats(),
            'terminals' => $this->terminalModel->getActiveTerminals()
        ]);

        return view('operational/vessel/index', $data);
    }

    public function create()
    {
        $permCheck = $this->requirePermission('oper_vessel', 'can_create');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Tambah Vessel Schedule',
            'pageTitle' => 'Tambah Vessel Schedule',
            'terminals' => $this->terminalModel->getActiveTerminals()
        ]);

        return view('operational/vessel/form', $data);
    }

    public function store()
    {
        $permCheck = $this->requirePermission('oper_vessel', 'can_create');
        if ($permCheck) return $permCheck;

        $data = [
            'terminal_id' => $this->request->getPost('terminal_id'),
            'vessel_name' => $this->request->getPost('vessel_name'),
            'vessel_code' => $this->request->getPost('vessel_code'),
            'arrival_date' => $this->request->getPost('arrival_date'),
            'departure_date' => $this->request->getPost('departure_date'),
            'berth_position' => $this->request->getPost('berth_position'),
            'status' => $this->request->getPost('status') ?? 'SCHEDULED',
            'cargo_type' => $this->request->getPost('cargo_type')
        ];

        $this->vesselModel->insert($data);

        return redirect()->to('/operational/vessel')->with('success', 'Vessel schedule berhasil ditambahkan');
    }

    public function edit($id)
    {
        $permCheck = $this->requirePermission('oper_vessel', 'can_update');
        if ($permCheck) return $permCheck;

        $vessel = $this->vesselModel->find($id);
        if (!$vessel) {
            return redirect()->to('/operational/vessel')->with('error', 'Data tidak ditemukan');
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Edit Vessel Schedule',
            'pageTitle' => 'Edit Vessel Schedule',
            'vessel' => $vessel,
            'terminals' => $this->terminalModel->getActiveTerminals()
        ]);

        return view('operational/vessel/form', $data);
    }

    public function update($id)
    {
        $permCheck = $this->requirePermission('oper_vessel', 'can_update');
        if ($permCheck) return $permCheck;

        $vessel = $this->vesselModel->find($id);
        if (!$vessel) {
            return redirect()->to('/operational/vessel')->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'terminal_id' => $this->request->getPost('terminal_id'),
            'vessel_name' => $this->request->getPost('vessel_name'),
            'vessel_code' => $this->request->getPost('vessel_code'),
            'arrival_date' => $this->request->getPost('arrival_date'),
            'departure_date' => $this->request->getPost('departure_date'),
            'berth_position' => $this->request->getPost('berth_position'),
            'status' => $this->request->getPost('status'),
            'cargo_type' => $this->request->getPost('cargo_type')
        ];

        $this->vesselModel->update($id, $data);

        return redirect()->to('/operational/vessel')->with('success', 'Vessel schedule berhasil diperbarui');
    }

    public function delete($id)
    {
        $permCheck = $this->requirePermission('oper_vessel', 'can_delete');
        if ($permCheck) return $permCheck;

        $vessel = $this->vesselModel->find($id);
        if (!$vessel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $this->vesselModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Vessel schedule berhasil dihapus']);
    }
}
