<?php

// Коэффициент для расцета цен, рекомендуемых для розницы.
define('GF_RETAIL_PRICE_COEFFICIENT', [
  'ru' => 2.5,
  'cn' => 3,
]);

function stability_sub_preprocess_views_view_fields(&$vars) {
  $view = &$vars['view'];
  if ($view->name == 'products') {

    (user_has_role(10)) ? $seller_limited_access = TRUE : $seller_limited_access = FALSE;
    (user_has_role(12)) ? $is_gross = TRUE : $is_gross = FALSE;
    (user_has_role(13)) ? $is_publicator = TRUE : $is_publicator = FALSE;
    (user_has_role(5)) ? $is_manager = TRUE : $is_manager = FALSE;
    (user_has_role(7)) ? $is_wholesaler = TRUE : $is_wholesaler = FALSE;
    (user_has_role(8)) ? $is_creator = TRUE : $is_creator = FALSE;
    if (user_has_role(18)) {
      $is_man_sadovod = TRUE;
      $extra_10 = TRUE;
    } else {
      $is_man_sadovod = FALSE;
    }
    if (user_has_role(19)) {
      $is_opt_sadovod = TRUE;
      $extra_10 = TRUE;
    } else {
      $is_opt_sadovod = FALSE;
    }


    $RU_CODE = "Russia";
    $CN_CODE = "China";

    if (!(isset($_SESSION['gf_stock_region'])) or $is_opt_sadovod or $is_man_sadovod) {
      $_SESSION['gf_stock_region'] = $RU_CODE;
    }

    /**
     * Курс Юаня к Рублю
     */
    //$yuan_to_rub_rate = variable_get('gf_stock_yuan_exchange_rate');

    $vars['seller_limited_access'] = $seller_limited_access;
    $vars['is_gross'] = $is_gross;
    $vars['is_wholesaler'] = $is_wholesaler;
    $vars['is_publicator'] = $is_publicator;
    $vars['is_manager'] = $is_manager;
    $vars['is_creator'] = $is_creator;
    $vars['is_man_sadovod'] = $is_man_sadovod;
    $vars['is_opt_sadovod'] = $is_opt_sadovod;
    $vars['extra_10'] = $extra_10;
    $vars['RU_CODE'] = $RU_CODE;
    $vars['CN_CODE'] = $CN_CODE;
  }
}

/**
 * Replacement for theme_webform_element().
 */
function stability_sub_webform_element($variables) {
  $variables['element'] += array(
    '#title_display' => 'before',
  );
  $element = $variables['element'];
  if (isset($element['#format']) && $element['#format'] == 'html') {
    $type = 'display';
  }
  else {
    $type = (isset($element['#type']) && !in_array($element['#type'], array('markup', 'textfield', 'webform_email', 'webform_number'))) ? $element['#type'] : $element['#webform_component']['type'];
  }
  $nested_level = $element['#parents'][0] == 'submitted' ? 1 : 0;
  $parents = str_replace('_', '-', implode('--', array_slice($element['#parents'], $nested_level)));
  if ($variables['element']['#webform_component']['nid'] && $variables['element']['#webform_component']['nid'] == 53470 && $variables['element']['#title'] && $variables['element']['#title'] == 'Note') {
    $wrapper_classes = array(
      'form-item',
      'webform-component',
      'webform-component' . $type,
      $element['#wrapper_attributes']['class'][0],
    );
  } else {
    $wrapper_classes = array(
     'form-item',
     'webform-component',
     'webform-component-' . $type,
    );
  }
  if (isset($element['#container_class'])) {
    $wrapper_classes[] = $element['#container_class'];
  }
  if (isset($element['#title_display']) && strcmp($element['#title_display'], 'inline') === 0) {
    $wrapper_classes[] = 'webform-container-inline';
  }
  $output = '<div class="' . implode(' ', $wrapper_classes) . '" id="webform-component-' . $parents . '">' . "\n";
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . _webform_filter_xss($element['#field_prefix']) . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . _webform_filter_xss($element['#field_suffix']) . '</span>' : '';
  switch ($element['#title_display']) {
    case 'inline':
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;
    case 'none':
    case 'attribute':
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }
  if (!empty($element['#description'])) {
    $output .= ' <div class="description">' . $element['#description'] . "</div>\n";
  }
  $output .= "</div>\n";
  return $output;
}
  
  /**
 * Implements hook_preprocess_node()/
 */
function stability_sub_preprocess_node(&$variables) {
  global $user;

  $extra_10 = FALSE;
  (user_has_role(18)) ? $is_man_sadovod = $extra_10 = TRUE : $is_man_sadovod = FALSE;
  (user_has_role(19)) ? $is_opt_sadovod = $extra_10 = TRUE : $is_opt_sadovod = FALSE;

  $node = $variables['node'];

  $current_region = $_SESSION['gf_stock_region'] ?? FALSE;

  if ((!$current_region) || $current_region == GF_STOCK_REGION_ALL 
    || $is_opt_sadovod || $is_man_sadovod) {
    $current_region = GF_STOCK_REGION_RU;
  }

  $variables['seller_limited_access'] = user_has_role(10);
  $variables['is_gross'] = user_has_role(12);
  $variables['is_publicator'] = user_has_role(13);
  $variables['is_manager'] = user_has_role(5);
  $variables['is_creator'] = user_has_role(8);
  $variables['is_man_sadovod'] = $is_man_sadovod;
  $variables['is_opt_sadovod'] = $is_opt_sadovod;
  $variables['RU_CODE'] = GF_STOCK_REGION_RU;
  $variables['CN_CODE'] = GF_STOCK_REGION_CN;
  $variables['current_region'] = $current_region;
  $variables['extra_10'] = $extra_10;

  $variables['original_price'] = [
    GF_STOCK_REGION_RU => isset($node->gf_region_prices_original['руб']) ? 
      round($node->gf_region_prices_original['руб']) : 0,
    GF_STOCK_REGION_CN => isset($node->gf_region_prices_original['юан']) ? 
      round($node->gf_region_prices_original['юан']) : 0,
  ];

  $ruble_sign = '<i class="fa fa-rub" aria-hidden="true"></i>';
  $variables['original_currency_symbol'] = [
    GF_STOCK_REGION_CN => '<i class="fa fa-cny" aria-hidden="true"></i>',
    GF_STOCK_REGION_RU => $ruble_sign,
  ];

  if ($current_region == GF_STOCK_REGION_RU) {
    $variables['current_code'] = GF_STOCK_REGION_RU; 
    $variables['other_code'] = GF_STOCK_REGION_CN; 
    $variables['other_short_code'] = GF_STOCK_REGION_CN;
  } 
  else {
    $variables['current_code'] = GF_STOCK_REGION_CN; 
    $variables['other_code'] = GF_STOCK_REGION_RU; 
    $variables['other_short_code'] = GF_STOCK_REGION_RU;
  }



  $field_adaptive = user_load($user->uid)->field_adaptive_design;

  $current_region_price = $node->gf_region_prices[$current_region] ?? 0;
  $default_currency_code = variable_get('uc_currency_code', UC_CURRENCY_DEFAULT_CURRENCY);
  $code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;

  $variables['symbol'] = str_replace('руб.', $ruble_sign, currency_api_get_symbol($code));
  $variables['display_price_base'] = round($current_region_price);
  $variables['adaptive_enabled'] = isset($field_adaptive[LANGUAGE_NONE]) ?
    $field_adaptive[LANGUAGE_NONE][0]['value'] : FALSE;





  $retail_price_coefficient = ($current_region == GF_STOCK_REGION_RU) ? 
    GF_RETAIL_PRICE_COEFFICIENT['ru'] : GF_RETAIL_PRICE_COEFFICIENT['cn'];

  $variables['retail_price'] = $variables['display_price_base'] * $retail_price_coefficient;
  $variables['order_price'] = $node->gf_region_prices[$current_region] ?? 0;




  // if ($node->nid == 96965) {
  //   // Подключаем скрип передачи данных в Roistat на 
  //   // странице invite.
  //   drupal_add_js(drupal_get_path('theme', 'stability_sub') . '/js/roistat/roistat_invite.js');
  // }
}

function stability_sub_process_page(&$variables) {
  // if (arg(0) == 'user' && arg(1) == 'register' ) {
  //   // Подключаем передачу данных на Roistat для 
  //   // страницы регистрации.
  //   drupal_add_js(drupal_get_path('theme', 'stability_sub') . '/js/roistat/roistat_registration.js');
  // }

  global $user;
  $variables['login_account_links'] = '';
  if (theme_get_setting('login_account_links') || module_exists('uc_cart')) {
    $output = '';
    if(theme_get_setting('login_account_links')) {
      $output .= '<span class="login">
        <i class="fa fa-lock"></i> ' . l(($user->uid ? t('My Account') : t('Sign In')), 'user') . '
      </span>';
      $output .= $user->uid ? '<span class="logout"><i class="fa fa-sign-out"></i> ' . l(t('Logout'), 'user/logout') . '</span>' : '';
      $output .= !$user->uid ? '<span class="register"><i class="fa fa-pencil-square-o"></i>' . t('Not a Member?'). ' ' . l(t('Sign Up'), 'user/register') . '</span>' : '';
    }
    if(module_exists('uc_cart')) {
      $output .= '<span class="cart">
        <i class="fa fa-shopping-cart"></i> ' . l(t('Shopping Cart'), 'cart') . '
      </span>';
    }
    $variables['login_account_links'] = '
      <div class="">
        ' . $output . '
      </div>';

  }

  $header_top_menu_tree = module_exists('i18n_menu') ? i18n_menu_translated_tree('menu-header-top-menu') : menu_tree('menu-header-top-menu');
  $variables['header_top_menu_tree'] = drupal_render($header_top_menu_tree);
  // Process Slideshow Sub Header
  if(theme_get_setting('sub_header') == 5 || (arg(2) == 'sub-header'  && arg(3) == '5')) {
    drupal_add_js(drupal_get_path('theme', 'stability_sub') . '/vendor/jquery.glide.min.js');
  }
  if(theme_get_setting('retina')) {
    drupal_add_js(drupal_get_path('theme', 'stability_sub') . '/vendor/jquery.retina.js');
  }
  drupal_add_js(array('stability_sub' => array('flickr_id' => theme_get_setting('flickr_id'), 'logo_sticky' => theme_get_setting('logo_sticky'))), 'setting');
}




/**
 * Implements hook_mail_alter().
 */
function stability_sub_mail_alter(&$message) {
  // Stop the default drupal email that goes out to admins when a user
  // registers on the site. An alternative email is sent out via other means.
  if ($message['key'] == 'register_pending_approval_admin') {
    $message['send'] = FALSE;
  }
}



function stability_sub_lt_username_title($variables) {
  switch ($variables['form_id']) {
    case 'user_login':
      // Label text for the username field on the /user/login page.
      return t('E-mail address');
      break;

    case 'user_login_block':
      // Label text for the username field when shown in a block.
      return t('E-mail');
      break;
  }
}



function stability_sub_form_alter(&$form, &$form_state, $form_id) {
  if (!empty($form['actions']) && $form['actions']['submit']) {
    $form['actions']['submit']['#attributes'] = array('class' => array('btn-primary', 'button', 'webform-submit',  'form-submit'));
  }
};

function stability_sub_uc_cart_review_table($variables) {
  $items = $variables['items'];
  $show_subtotal = $variables['show_subtotal'];

  $subtotal = 0;

  // Set up table header.
  $header = array(
    array('data' => theme('uc_qty_label'), 'class' => array('qty')),
    array('data' => t('Products'), 'class' => array('products')),
    array('data' => t('Price'), 'class' => array('price')),
  );

  // Set up table rows.
  $display_items = uc_order_product_view_multiple($items);
  if (!empty($display_items['uc_order_product'])) {
    foreach (element_children($display_items['uc_order_product']) as $key) {
      $display_item = $display_items['uc_order_product'][$key];
      $subtotal += $display_item['total']['#price'];
      $rows[] = array(
        array('data' => $display_item['qty'], 'class' => array('qty')),
        array('data' => $display_item['product'], 'class' => array('products')),
        array('data' => $display_item['total'], 'class' => array('price')),
      );
    }
  }

  // Add the subtotal as the final row.
  if ($show_subtotal) {
    $rows[] = array(
      'data' => array(
        // One cell
        array(
          'data' => array(
            '#theme' => 'uc_price',
            '#prefix' => '<span id="subtotal-title">' . t('Subtotal:') . '</span> ',
            '#price' => $subtotal,
            '#suffix' => '<div id="total-qty"><span>'. t('Total qty:') . 
            '</span>' . uc_cart_get_total_qty() . '</div>', // добавляем инф-ю об общем кол-ве заказанного
          ),
          // Cell attributes
          'colspan' => 3,
          'class' => array('subtotal'),
        ),
      ),
      // Row attributes
      'class' => array('subtotal'),
    );
  }

  return theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('cart-review'))));
}

function stability_sub_uc_cart_checkout_review($variables) {
  $panes = $variables['panes'];
  $form = $variables['form'];

  drupal_add_css(drupal_get_path('module', 'uc_cart') . '/uc_cart.css');

  $output = '<div id="review-instructions">' . filter_xss_admin(variable_get('uc_checkout_review_instructions', uc_get_message('review_instructions'))) . '</div>';

  $output .= '<table class="order-review-table">';

  foreach ($panes as $title => $data) {
    $output .= '<tr class="pane-title-row">';
    $output .= '<td colspan="2">' . $title . '</td>';
    $output .= '</tr>';
    if (is_array($data)) {
      if ($title == t('Payment method')) {
        // Если мы в разделе отображения методов оплаты(определяем по
        // заголовку раздела), то добавляем после поля с общей суммой заказа
        // данные об общем кол-ве заказанного. Поле с данными о методе оплаты двигаем ниже.
        $data[3] = $data[2];
        $data[2] = [
          'title' => t('Total qty'),
          'data' => '<span class="total-qty">' . uc_cart_get_total_qty() . '</span>',
        ];
      }
      foreach ($data as $row) {
        if (is_array($row)) {
          if (isset($row['border'])) {
            $border = ' class="row-border-' . $row['border'] . '"';
          }
          else {
            $border = '';
          }
          $output .= '<tr' . $border . '>';
          $output .= '<td class="title-col">' . $row['title'] . ':</td>';
          $output .= '<td class="data-col">' . $row['data'] . '</td>';
          $output .= '</tr>';
        }
        else {
          $output .= '<tr><td colspan="2">' . $row . '</td></tr>';
        }
      }
    }
    else {
      $output .= '<tr><td colspan="2">' . $data . '</td></tr>';
    }
  }

  $output .= '<tr class="review-button-row">';
  $output .= '<td colspan="2">' . drupal_render($form) . '</td>';
  $output .= '</tr>';

  $output .= '</table>';

  return $output;
}
