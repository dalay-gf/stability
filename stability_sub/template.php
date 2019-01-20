<?php

// Коэффициент для расцета цен, рекомендуемых для розницы.
define('GF_RETAIL_PRICE_COEFFICIENT', 2.5);

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

include 'node-preprocess.inc';
include 'preprocess_views_view_fields.inc';

