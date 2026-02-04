<?php

namespace App\Models;

use CodeIgniter\Model;

class TerminalModel extends Model
{
    protected $table = 'terminals';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'terminal_code',
        'terminal_name',
        'regional',
        'address',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'terminal_code' => 'required|min_length[2]|max_length[50]',
        'terminal_name' => 'required|min_length[2]|max_length[200]',
    ];

    public function getActiveTerminals()
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function getTerminalByCode($code)
    {
        return $this->where('terminal_code', $code)->first();
    }

    public function getTerminalsWithLatestOperation()
    {
        $builder = $this->db->table($this->table . ' t');
        $builder->select('t.*, to.*');
        $builder->join('terminal_operations to', 'to.terminal_id = t.id AND to.report_date = CURDATE()', 'left');
        $builder->where('t.is_active', 1);
        $builder->orderBy('t.id', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
