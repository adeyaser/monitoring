<ul class="nav-menu">
    <?php if (isset($menus) && is_array($menus)): ?>
        <?php foreach ($menus as $menu): ?>
            <?php if (isset($menu['can_view']) && $menu['can_view']): ?>
            <li class="nav-item <?= isset($menu['children']) ? 'has-submenu' : '' ?>">
                <?php if (isset($menu['children']) && !empty($menu['children'])): ?>
                    <a href="#submenu-<?= esc($menu['menu_code']) ?>" class="nav-link" data-bs-toggle="collapse">
                        <i class="bi <?= esc($menu['menu_icon'] ?? 'bi-circle') ?>"></i>
                        <span><?= esc($menu['menu_name']) ?></span>
                        <i class="bi bi-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu collapse" id="submenu-<?= esc($menu['menu_code']) ?>">
                        <?php foreach ($menu['children'] as $child): ?>
                            <?php if (isset($child['can_view']) && $child['can_view']): ?>
                            <li class="nav-item">
                                <a href="<?= base_url($child['menu_url']) ?>" class="nav-link">
                                    <i class="bi <?= esc($child['menu_icon'] ?? 'bi-circle') ?>"></i>
                                    <span><?= esc($child['menu_name']) ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <a href="<?= base_url($menu['menu_url']) ?>" class="nav-link">
                        <i class="bi <?= esc($menu['menu_icon'] ?? 'bi-circle') ?>"></i>
                        <span><?= esc($menu['menu_name']) ?></span>
                    </a>
                <?php endif; ?>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Fallback menu jika tidak ada data -->
        <li class="nav-item">
            <a href="<?= base_url('/') ?>" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
    <?php endif; ?>
</ul>
