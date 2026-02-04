<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <h4 class="mb-1">Hak Akses Group</h4>
        <p class="text-secondary mb-0">Atur hak akses menu untuk setiap group</p>
    </div>
    
    <!-- Group Selector -->
    <div class="dashboard-card mb-4">
        <form id="permissionForm" action="<?= base_url('acl/permissions/update') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label for="group_id" class="form-label">Pilih Group</label>
                    <select class="form-select" id="group_id" name="group_id" onchange="loadPermissions()">
                        <option value="">-- Pilih Group --</option>
                        <?php if (isset($groups)): ?>
                            <?php foreach ($groups as $group): ?>
                            <option value="<?= $group['id'] ?>"><?= esc($group['group_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary" id="saveBtn" disabled>
                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        
            <!-- Permissions Table -->
            <div id="permissionsContainer" class="mt-4" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-dark" id="permissionsTable">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" class="form-check-input" id="checkAll" onchange="toggleAll()">
                                </th>
                                <th>Menu</th>
                                <th width="100" class="text-center">View</th>
                                <th width="100" class="text-center">Create</th>
                                <th width="100" class="text-center">Update</th>
                                <th width="100" class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($menus)): ?>
                                <?php foreach ($menus as $menu): ?>
                                <tr data-menu-id="<?= $menu['id'] ?>">
                                    <td>
                                        <input type="checkbox" class="form-check-input row-check" 
                                               onchange="toggleRow(<?= $menu['id'] ?>)">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi <?= esc($menu['menu_icon'] ?? 'bi-circle') ?>"></i>
                                            <span><?= esc($menu['menu_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input perm-check" 
                                               name="permissions[<?= $menu['id'] ?>][can_view]" value="1"
                                               id="view_<?= $menu['id'] ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input perm-check" 
                                               name="permissions[<?= $menu['id'] ?>][can_create]" value="1"
                                               id="create_<?= $menu['id'] ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input perm-check" 
                                               name="permissions[<?= $menu['id'] ?>][can_update]" value="1"
                                               id="update_<?= $menu['id'] ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input perm-check" 
                                               name="permissions[<?= $menu['id'] ?>][can_delete]" value="1"
                                               id="delete_<?= $menu['id'] ?>">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.form-check-input {
    width: 1.2em;
    height: 1.2em;
    cursor: pointer;
}
.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Permissions data from PHP
const allPermissions = <?= json_encode($permissions ?? []) ?>;

function loadPermissions() {
    const groupId = document.getElementById('group_id').value;
    const container = document.getElementById('permissionsContainer');
    const saveBtn = document.getElementById('saveBtn');
    
    if (!groupId) {
        container.style.display = 'none';
        saveBtn.disabled = true;
        return;
    }
    
    container.style.display = 'block';
    saveBtn.disabled = false;
    
    // Reset all checkboxes
    document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);
    
    // Load permissions for selected group
    const groupPerms = allPermissions[groupId] || {};
    
    Object.entries(groupPerms).forEach(([menuId, perms]) => {
        if (perms.can_view) document.getElementById('view_' + menuId).checked = true;
        if (perms.can_create) document.getElementById('create_' + menuId).checked = true;
        if (perms.can_update) document.getElementById('update_' + menuId).checked = true;
        if (perms.can_delete) document.getElementById('delete_' + menuId).checked = true;
        
        // Update row checkbox
        updateRowCheck(menuId);
    });
    
    // Update check all status
    updateCheckAll();
}

function toggleAll() {
    const checkAll = document.getElementById('checkAll').checked;
    document.querySelectorAll('.perm-check').forEach(cb => cb.checked = checkAll);
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll);
}

function toggleRow(menuId) {
    const rowCheck = document.querySelector(`tr[data-menu-id="${menuId}"] .row-check`).checked;
    const row = document.querySelector(`tr[data-menu-id="${menuId}"]`);
    row.querySelectorAll('.perm-check').forEach(cb => cb.checked = rowCheck);
    updateCheckAll();
}

function updateRowCheck(menuId) {
    const row = document.querySelector(`tr[data-menu-id="${menuId}"]`);
    const permChecks = row.querySelectorAll('.perm-check');
    const allChecked = Array.from(permChecks).every(cb => cb.checked);
    row.querySelector('.row-check').checked = allChecked;
}

function updateCheckAll() {
    const allRows = document.querySelectorAll('.row-check');
    const allChecked = Array.from(allRows).every(cb => cb.checked);
    document.getElementById('checkAll').checked = allChecked;
}

// Add event listeners to permission checkboxes
document.querySelectorAll('.perm-check').forEach(cb => {
    cb.addEventListener('change', function() {
        const row = this.closest('tr');
        const menuId = row.dataset.menuId;
        updateRowCheck(menuId);
        updateCheckAll();
    });
});
</script>
<?= $this->endSection() ?>
