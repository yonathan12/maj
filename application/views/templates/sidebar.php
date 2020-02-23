<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url(); ?>user">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-money-check-alt"></i>
    </div>
    <div class="sidebar-brand-text mx-3">MAJ</div>
  </a>

  <hr class="sidebar-divider">
  <?php
  $role_id = $this->session->userdata('role_id');
  $queryMenu = "SELECT user_menu.Id, user_menu.menu
                    FROM user_menu JOIN user_access_menu
                    ON user_menu.Id = user_access_menu.menu_id
                    WHERE user_access_menu.role_id = $role_id
                    ORDER BY user_menu.menu ASC
                    ";
  $menu = $this->db->query($queryMenu)->result_array();
  ?>

  <?php foreach ($menu as $m) : ?>
    <div class="sidebar-heading">
      <?= $m['menu']; ?>
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?= $m['Id']; ?>" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span><?= $m['menu']; ?></span>
      </a>
      <div id="collapse<?= $m['Id']; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header"><?= $m['menu']; ?></h6>
          <?php
          $menuId = $m['Id'];
          $querySubMenu = "SELECT *
                          FROM user_sub_menu JOIN user_menu
                          ON user_sub_menu.menu_id = user_menu.Id
                          WHERE user_sub_menu.menu_id = $menuId
                          AND user_sub_menu.is_active = 1 
                          ORDER BY user_sub_menu.title ASC
          ";
          $subMenu = $this->db->query($querySubMenu)->result_array();
          ?>
          <?php foreach ($subMenu as $sm) : ?>
            <a class="collapse-item" href="<?= base_url($sm['url']); ?>"> <i class="<?= $sm['icon']; ?>"></i> <?= $sm['title']; ?> </a>
          <?php endforeach; ?>
        </div>
      </div>
    </li>
    <hr class="sidebar-divider mt-3">
  <?php endforeach; ?>

  <li class="nav-item">
    <a class="nav-link pb-0" href="<?= base_url(); ?>auth/logout">
      <i class="fas fa-fw fa-fw fa-sign-out-alt"></i>
      <span>Logout</span></a>
  </li>
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>
</ul>