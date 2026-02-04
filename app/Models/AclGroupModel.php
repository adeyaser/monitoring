<?php

namespace App\Models;

use CodeIgniter\Model;

class AclGroupModel extends Model
{
    protected $table = 'acl_groups';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'group_name',
        'group_code',
        'description',
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
        'group_name' => 'required|min_length[3]|max_length[100]',
        'group_code' => 'required|min_length[3]|max_length[50]|alpha_dash',
    ];

    protected $validationMessages = [
        'group_name' => [
            'required' => 'Nama group harus diisi',
            'min_length' => 'Nama group minimal 3 karakter',
        ],
        'group_code' => [
            'required' => 'Kode group harus diisi',
            'alpha_dash' => 'Kode group hanya boleh huruf, angka, dan dash',
        ],
    ];

    public function getActiveGroups()
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function getGroupWithUserCount()
    {
        $builder = $this->db->table($this->table . ' g');
        $builder->select('g.*, COUNT(u.id) as user_count');
        $builder->join('acl_users u', 'u.group_id = g.id', 'left');
        $builder->groupBy('g.id');
        return $builder->get()->getResultArray();
    }
}
