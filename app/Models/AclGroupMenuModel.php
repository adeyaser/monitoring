<?php

namespace App\Models;

use CodeIgniter\Model;

class AclGroupMenuModel extends Model
{
    protected $table = 'acl_group_menus';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'group_id',
        'menu_id',
        'can_view',
        'can_create',
        'can_update',
        'can_delete'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getPermissionsByGroup($groupId)
    {
        return $this->where('group_id', $groupId)->findAll();
    }

    public function hasPermission($groupId, $menuCode, $permission = 'can_view')
    {
        $builder = $this->db->table($this->table . ' gm');
        $builder->select("gm.{$permission}");
        $builder->join('acl_menus m', 'm.id = gm.menu_id');
        $builder->where('gm.group_id', $groupId);
        $builder->where('m.menu_code', $menuCode);
        
        $result = $builder->get()->getRowArray();
        return $result && $result[$permission] == 1;
    }

    public function getGroupPermissions($groupId)
    {
        $builder = $this->db->table($this->table . ' gm');
        $builder->select('m.menu_code, gm.can_view, gm.can_create, gm.can_update, gm.can_delete');
        $builder->join('acl_menus m', 'm.id = gm.menu_id');
        $builder->where('gm.group_id', $groupId);
        
        return $builder->get()->getResultArray();
    }

    public function updatePermissions($groupId, $menuId, $permissions)
    {
        $existing = $this->where('group_id', $groupId)
                         ->where('menu_id', $menuId)
                         ->first();
        
        if ($existing) {
            return $this->update($existing['id'], $permissions);
        } else {
            return $this->insert(array_merge([
                'group_id' => $groupId,
                'menu_id' => $menuId
            ], $permissions));
        }
    }

    public function setPermissionsBulk($groupId, $permissions)
    {
        // Delete existing permissions
        $this->where('group_id', $groupId)->delete();
        
        // Insert new permissions
        if (!empty($permissions)) {
            return $this->insertBatch($permissions);
        }
        
        return true;
    }
}
