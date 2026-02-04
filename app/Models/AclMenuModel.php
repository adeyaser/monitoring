<?php

namespace App\Models;

use CodeIgniter\Model;

class AclMenuModel extends Model
{
    protected $table = 'acl_menus';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'menu_name',
        'menu_code',
        'menu_url',
        'menu_icon',
        'parent_id',
        'menu_order',
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
        'menu_name' => 'required|min_length[2]|max_length[100]',
        'menu_code' => 'required|min_length[2]|max_length[50]|alpha_dash',
    ];

    public function getActiveMenus()
    {
        return $this->where('is_active', 1)
                    ->orderBy('menu_order', 'ASC')
                    ->findAll();
    }

    public function getMenuTree()
    {
        $menus = $this->where('is_active', 1)
                      ->orderBy('menu_order', 'ASC')
                      ->findAll();
        
        return $this->buildTree($menus);
    }

    private function buildTree(array $elements, $parentId = null)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function getParentMenus()
    {
        return $this->where('parent_id', null)
                    ->where('is_active', 1)
                    ->orderBy('menu_order', 'ASC')
                    ->findAll();
    }

    public function getMenusForGroup($groupId)
    {
        $builder = $this->db->table($this->table . ' m');
        $builder->select('m.*, gm.can_view, gm.can_create, gm.can_update, gm.can_delete');
        $builder->join('acl_group_menus gm', 'gm.menu_id = m.id AND gm.group_id = ' . $groupId, 'left');
        $builder->where('m.is_active', 1);
        $builder->orderBy('m.menu_order', 'ASC');
        
        $menus = $builder->get()->getResultArray();
        return $this->buildTree($menus);
    }
}
