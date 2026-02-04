<?php

namespace App\Models;

use CodeIgniter\Model;

class AclUserModel extends Model
{
    protected $table = 'acl_users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'avatar',
        'group_id',
        'last_login',
        'is_active',
        'remember_token'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|alpha_numeric_punct',
        'email' => 'required|valid_email|max_length[150]',
        'password' => 'required|min_length[6]',
        'full_name' => 'required|min_length[3]|max_length[200]',
        'group_id' => 'required|is_not_unique[acl_groups.id]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'is_unique' => 'Username sudah digunakan',
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah digunakan',
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter',
        ],
    ];

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function getUserWithGroup($id = null)
    {
        $builder = $this->db->table($this->table . ' u');
        $builder->select('u.*, g.group_name, g.group_code');
        $builder->join('acl_groups g', 'g.id = u.group_id', 'left');
        
        if ($id !== null) {
            $builder->where('u.id', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }

    public function getByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function updateLastLogin($id)
    {
        return $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
