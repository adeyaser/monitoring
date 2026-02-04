<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\TerminalModel;

class Terminal extends BaseController
{
    protected $terminalModel;

    public function __construct()
    {
        $this->terminalModel = new TerminalModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('master_terminal');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Data Terminal',
            'pageTitle' => 'Data Terminal',
            'terminals' => $this->terminalModel->findAll()
        ]);

        return view('master/terminal/index', $data);
    }

    public function create()
    {
        $permCheck = $this->requirePermission('master_terminal', 'can_create');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Tambah Terminal',
            'pageTitle' => 'Tambah Terminal'
        ]);

        return view('master/terminal/form', $data);
    }

    public function store()
    {
        $permCheck = $this->requirePermission('master_terminal', 'can_create');
        if ($permCheck) return $permCheck;

        $rules = [
            'terminal_code' => 'required|min_length[2]|is_unique[terminals.terminal_code]',
            'terminal_name' => 'required|min_length[2]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'terminal_code' => strtoupper($this->request->getPost('terminal_code')),
            'terminal_name' => $this->request->getPost('terminal_name'),
            'regional' => $this->request->getPost('regional'),
            'address' => $this->request->getPost('address'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $this->terminalModel->insert($data);

        return redirect()->to('/master/terminal')->with('success', 'Terminal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $permCheck = $this->requirePermission('master_terminal', 'can_update');
        if ($permCheck) return $permCheck;

        $terminal = $this->terminalModel->find($id);
        if (!$terminal) {
            return redirect()->to('/master/terminal')->with('error', 'Terminal tidak ditemukan');
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Edit Terminal',
            'pageTitle' => 'Edit Terminal',
            'terminal' => $terminal
        ]);

        return view('master/terminal/form', $data);
    }

    public function update($id)
    {
        $permCheck = $this->requirePermission('master_terminal', 'can_update');
        if ($permCheck) return $permCheck;

        $terminal = $this->terminalModel->find($id);
        if (!$terminal) {
            return redirect()->to('/master/terminal')->with('error', 'Terminal tidak ditemukan');
        }

        $rules = [
            'terminal_code' => "required|min_length[2]|is_unique[terminals.terminal_code,id,{$id}]",
            'terminal_name' => 'required|min_length[2]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'terminal_code' => strtoupper($this->request->getPost('terminal_code')),
            'terminal_name' => $this->request->getPost('terminal_name'),
            'regional' => $this->request->getPost('regional'),
            'address' => $this->request->getPost('address'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $this->terminalModel->update($id, $data);

        return redirect()->to('/master/terminal')->with('success', 'Terminal berhasil diperbarui');
    }

    public function delete($id)
    {
        $permCheck = $this->requirePermission('master_terminal', 'can_delete');
        if ($permCheck) return $permCheck;

        $terminal = $this->terminalModel->find($id);
        if (!$terminal) {
            return $this->response->setJSON(['success' => false, 'message' => 'Terminal tidak ditemukan']);
        }

        $this->terminalModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Terminal berhasil dihapus']);
    }

    public function apiList()
    {
        $terminals = $this->terminalModel->getActiveTerminals();
        return $this->response->setJSON(['success' => true, 'data' => $terminals]);
    }
}
