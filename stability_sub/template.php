<?php
/*function stability_sub_preprocess_views_view(&$vars) {
  $view = &$vars['view'];
  // Make sure it's the correct view
  if ($view->name == 'orders_for_production') {
    // add needed javascript
    drupal_add_js(drupal_get_path('theme', 'stability_sub') . '/js/webform_colorpicker.js');
  }
}*/


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
 * Implements hook_form_alter()/
 */
/*function stability_sub_form_alter(&$form, &$form_state, $form_id) {
    // Your webform id goes here.
    if ($form_id == 'webform_client_form_85252') {
    // Add classes to webform fields.
//     $form['submitted']['contact_fio']['#container_class'] = 'col-md-6';
//     $form['submitted']['contact_email']['#container_class'] = 'col-md-6'.
//     $form['submitted']['contact_phone']['#container_class'] = 'col-md-12 clear-both';
    }
}*/
function stability_sub_preprocess_node(&$variables) {
  (user_has_role(10)) ? $seller_limited_access = true : $seller_limited_access = false;
  (user_has_role(12)) ? $is_gross = true : $is_gross = false;
  (user_has_role(13)) ? $is_publicator = TRUE : $is_publicator = FALSE;
  (user_has_role(5)) ? $is_manager = TRUE : $is_manager = FALSE;
  (user_has_role(8)) ? $is_creator = TRUE : $is_creator = FALSE;
  (user_has_role(18)) ? $is_man_sadovod = $extra_10 = TRUE : $is_man_sadovod = FALSE;
  (user_has_role(19)) ? $is_opt_sadovod = $extra_10 = TRUE : $is_opt_sadovod = FALSE;

  $node = $variables['node'];

  $RU_CODE = "Russia";
  $CN_CODE = "China";

  if (!(isset($_SESSION['gf_stock_region'])) or $is_opt_sadovod or $is_man_sadovod) {
    $_SESSION['gf_stock_region'] = $RU_CODE;
  }

  $current_region = $_SESSION['gf_stock_region'];

  $variables['seller_limited_access'] = $seller_limited_access;
  $variables['is_gross'] = $is_gross;
  $variables['is_publicator'] = $is_publicator;
  $variables['is_manager'] = $is_manager;
  $variables['is_creator'] = $is_creator;
  $variables['is_man_sadovod'] = $is_man_sadovod;
  $variables['is_opt_sadovod'] = $is_opt_sadovod;
  $variables['RU_CODE'] = $RU_CODE;
  $variables['CN_CODE'] = $CN_CODE;
  $variables['current_region'] = $current_region;
  $variables['extra_10'] = $extra_10;
  $variables['original_price'] = [
    $RU_CODE => round($node->gf_region_prices_original['руб']),
    $CN_CODE => round($node->gf_region_prices_original['юан'])
  ];
  $variables['original_currency_symbol'] = [
    $CN_CODE => '<i class="fa fa-cny" aria-hidden="true"></i>',
    $RU_CODE => '<i class="fa fa-rub" aria-hidden="true"></i>'
  ];


  if ($current_region == $RU_CODE) {
    $variables['current_code'] = $RU_CODE; 
    $variables['other_code'] = $CN_CODE; 
    $variables['other_short_code'] = $CN_CODE;
  } 
  else {
    $variables['current_code'] = $CN_CODE; 
    $variables['other_code'] = $RU_CODE; 
    $variables['other_short_code'] = $RU_CODE;
  }
}

function stability_sub_process_page(&$variables) {
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

