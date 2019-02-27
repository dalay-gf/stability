<?php
if ($node && $node->type == 'product') {drupal_set_title(t('Product') . ' ' . $node->model);};
$current_stock_region = isset($_SESSION['gf_stock_region']) ? $_SESSION['gf_stock_region'] : 'ru';
?>

<header class="header header-default">
  <div class="header-top">
    <div class="container">

      <div class="header-top-left">
        <?php
        $block = module_invoke('locale', 'block_view', 'language');
        print render($block['content']);
        ?>
        <?php
        (user_has_role(10)) ? $seller_limited_access = true : $seller_limited_access = false;
        (user_has_role(12)) ? $is_gross = true : $is_gross = false;

        if ($logged_in == true and $seller_limited_access <> true) {
          $currency_switcher_form = drupal_get_form('uc_currency_switcher_form');
          echo str_replace(array('?go=','?go'),'',drupal_render($currency_switcher_form));
        };
        ?>
        <?php if ($logged_in) : ?>
          <?php $user_fields = user_load($variables['user']->uid);?>
          <?php $mc_currency = mc_currency_load('rmb');
          $mc_currency = $mc_currency->info['currcode'];
          $uid = $user->uid;
          $mc_balance = transaction_totals($uid, $mc_currency);
          $default_currency_code = variable_get('uc_currency_code', UC_CURRENCY_DEFAULT_CURRENCY);
          $code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;
          $symbol = currency_api_get_symbol($code); ?>
          <span class="header-user-wallet"><?php $anchor_text = t('Your balance') . ': ' . $mc_balance->balance . '元'; print l($anchor_text, '/user/' . $user->uid . '/wallet', array('html' => TRUE));?></span>
        <?php endif; ?>
      </div>
      <div class="header-top-right">
        <?php if ($logged_in) :?>
          <?php
          $unread_pm = privatemsg_unread_count($variables['user']->uid);
          ($unread_pm > 0) ? $new_messages = true: $new_messages = false;
          ?>
          <a class="messages-link" href="/<?php print $variables['user']->language;?>/user/<?php print $variables['user']->uid;?>/messages">
            <span>
              <i class="fa <?php if ($new_messages) {print 'fa-envelope blinking';} else {print 'fa-envelope-open-o';};?>" data-title="<?php ($new_messages) ? print t('You have ' . $unread_pm . ' unread messages') : print t('Your messages');?>"></i>
            </span>
          </a>
        <?php endif;?>
        <?php if ($logged_in and (user_has_role(7) or user_has_role(3))) : ?>
          <?php
          $deposit_field = field_get_items('user', $user_fields, 'field_deposit', $langcode = NULL);
          $deposit_size = $deposit_field[0]['value'];?>
          <span class="deposit-value"><i class="fa fa-credit-card-alt" data-title="<?php print t('Your deposit balance') . ': $' . $deposit_size;?>"></i></span>
        <?php endif;?>
        <?php print $login_account_links; ?>
        <?php if ($logged_in) : ?>
          <span class="production-list"><i class="fa fa-cogs"></i>
            <?php
            $anchor_text = t('Sewing orders');
            print l($anchor_text, 'user/' . $user->uid . '/sewing', array('html' => true));
            ?>
          </span>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <div class="header-main">
    <div class="container">

      <!-- Logo -->
      <div class="logo">
        <?php if($logo): ?>
          <a href="<?php print $front_page; ?>"><img src="<?php print $logo; ?>" alt="<?php print $site_name; ?>"></a>
        <?php else: ?>
          <h1><a href="<?php print $front_page; ?>"><?php print $site_name; ?></a></h1>
        <?php endif; ?>
        <p class="tagline"><?php print $site_slogan; ?></p>
      </div>
      <!-- Logo / End -->

      <button type="button" class="navbar-toggle">
        <i class="fa fa-bars"></i>
      </button>

      <!-- Navigation -->
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
           <?php // Добавляем иконку корзинки со счетчиком товаров в корзине.
            if (user_is_logged_in()): ?>
          <li id="cart-icon-menu-item">
          <a title="<?php print t('There are @count products in your cart', ['@count'=>$uc_cart_qty_counter]); ?>"href="<?php print url('/cart'); ?>">
              <i class="fa fa fa-shopping-cart"></i>
              <span id="<?php print GF_AJAX_CART_ID_COUNTER_CONTAINER; ?>"><?php print $uc_cart_qty_counter; ?></span>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- Navigation / End -->

    </div>
  </div>
</header>
