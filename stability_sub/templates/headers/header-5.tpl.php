<header class="header header-centered header-menu-fullw header-top-clean">
  <div class="header-top">
    <div class="container">

      <div class="header-top-left">
        <?php /*print $header_top_menu_tree; */?>
        <?php
        $block = module_invoke('lang_dropdown', 'block_view', 'language');
        print render($block['content']);
        ?>
      </div>
      <!--<div class="header-top-center">
      </div>-->
      <div class="header-top-right">
      <?php print $login_account_links; ?>
      </div>

    </div>
  </div>

  <div class="header-main">
    <div class="container">

      <!-- Logo -->
      <div class="logo">
        <?php if($logo): ?>
        <a href="<?php print $front_page; ?>"><img src="<?php print $logo; ?>" alt="<?php print $site_name . '<span class="registred">&#174;</span>'; ?>"></a>
        <?php else: ?>
        <h1><a href="<?php print $front_page; ?>"><?php print $site_name . '<span class="registred">&#174;</span>'; ?></a></h1>
        <?php endif; ?>
        <p class="tagline"><?php print $site_slogan; ?></p>
      </div>
      <!-- Logo / End -->

      <button type="button" class="navbar-toggle">
        <i class="fa fa-bars"></i>
      </button>
    </div>
  </div>

  <nav class="nav-main">
  <ul data-breakpoint="992" class="flexnav">
   <?php
      if(module_exists('tb_megamenu')) {
        print theme('tb_megamenu', array('menu_name' => 'main-menu'));
      }
      else {
        $main_menu_tree = module_exists('i18n_menu') ? i18n_menu_translated_tree(variable_get('menu_main_links_source', 'main-menu')) : menu_tree(variable_get('menu_main_links_source', 'main-menu'));
        print drupal_render($main_menu_tree);
      }
    ?>
  </ul>
  </nav>

</header>