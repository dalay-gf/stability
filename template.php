<?php

function _stability_count_comments($val) {
  return isset($val['#comment']);
}

function _get_node_field($node, $field, $lang = 'en') {
  global $language;
  $var = FALSE;
  if(isset($node->{$field}[$lang]) && !empty($node->{$field}[$lang])) {
      $var = $node->{$field}[$lang];
  } elseif(isset($node->{$field}[$language->language]) && !empty($node->{$field}[$language->language])) {
      $var = $node->{$field}[$language->language];
  } elseif(isset($node->{$field}['und']) && !empty($node->{$field}['und'])) {
      $var = $node->{$field}['und'];
  } elseif(isset($node->{$field}) && !empty($node->{$field})) {
      $var = $node->{$field};
  }
  return $var;
}

/**
 * Implementation of hook_preprocess_html().
 */
function stability_preprocess_html(&$variables) {
  //drupal_add_css('http://fonts.googleapis.com/css?family=Istok+Web|Cuprum|Russo+One|Lora&subset=latin,cyrillic', array('type' => 'external'));
  drupal_add_css(drupal_get_path('theme', 'stability') . '/css/fonts/font-awesome/css/font-awesome.min.css');
  //drupal_add_js(drupal_get_path('theme', 'stability') . '/vendor/jquery.gmap3.min.js');
  //drupal_add_js(drupal_get_path('theme', 'stability') . '/js/contact.js');
//    drupal_add_js(array('enjoyit_flat_theme' => array(
//        'gmap_lat' => theme_get_setting('gmap_lat'),
//        'gmap_lng' => theme_get_setting('gmap_lng'),
//        'gmap_zoom' => theme_get_setting('gmap_zoom'),
//        'maptypeid' => theme_get_setting('maptypeid')
//      )
//   ), 'setting');

  if($_GET['q'] == 'home/one-page' || variable_get('site_frontpage', '') == 'home/one-page') {
    $variables['classes_array'][] = 'one-page';
    $variables['attributes_array']['data-target'] = '.header';
    $variables['attributes_array']['data-spy'] = 'scroll';
    $variables['attributes_array']['data-offset'] = '200'; 
  }
  // Add required skin
  drupal_add_css(drupal_get_path('theme', 'stability') . '/css/skins/' . theme_get_setting('skin') . '.css', array('group' => CSS_THEME, 'weight' => 2, 'every_page' => TRUE));
  // Layout setting
  if(theme_get_setting('layout') == 'boxed' || ($_GET['q'] == 'home/v10' && strpos($_SERVER['HTTP_HOST'], 'nikadevs') !== FALSE)) {
    $variables['classes_array'][] = 'boxed';
    // Pattern background for boxed view
    $variables['classes_array'][] = 'pattern-' . str_replace('_', '-', theme_get_setting('pattern'));
  }
  if(!theme_get_setting('sticky_header')) {
    $variables['classes_array'][] = 'no-sticky';
  }
}

/**
 * Implementation of hook_preprocess_page().
 */
function stability_process_page(&$variables) {
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
      <div class="header-top-right">
        ' . $output . '
      </div>';
  }

  $header_top_menu_tree = module_exists('i18n_menu') ? i18n_menu_translated_tree('menu-header-top-menu') : menu_tree('menu-header-top-menu');
  $variables['header_top_menu_tree'] = drupal_render($header_top_menu_tree);
  // Process Slideshow Sub Header
  if(theme_get_setting('sub_header') == 5 || (arg(2) == 'sub-header'  && arg(3) == '5')) {
    drupal_add_js(drupal_get_path('theme', 'stability') . '/vendor/jquery.glide.min.js');
  }
  if(theme_get_setting('retina')) {
    drupal_add_js(drupal_get_path('theme', 'stability') . '/vendor/jquery.retina.js');
  }
  drupal_add_js(array('stability' => array('flickr_id' => theme_get_setting('flickr_id'), 'logo_sticky' => theme_get_setting('logo_sticky'))), 'setting');
}

/**
 * Overrides theme_menu_tree().
 */
function stability_menu_tree__main_menu(&$variables) {
  return $variables['tree'];
}

/**
 * Overrides theme_menu_tree().
 */
function stability_menu_tree__menu_header_top_menu(&$variables) {
  return '<ul>' . $variables['tree'] . '</ul>';
}

/**
 * Implementation of hook_css_alter().
 */
function stability_css_alter(&$css) {
  // Disable standart css from ubercart
  unset($css[drupal_get_path('module', 'system') . '/system.menus.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.theme.css']);
  unset($css[drupal_get_path('module', 'search') . '/search.css']);
  unset($css[drupal_get_path('module', 'forum') . '/forum.css']);
  unset($css[drupal_get_path('module', 'tb_megamenu') . '/css/bootstrap.css']);
  unset($css[drupal_get_path('module', 'tb_megamenu') . '/css/base.css']);
  unset($css[drupal_get_path('module', 'tb_megamenu') . '/fonts/font-awesome/css/font-awesome.css']);
  if(isset($css['sites/all/themes/stability/stability_sub/css/custom.css'])) {
    $css['sites/all/themes/stability/stability_sub/css/custom.css']['weight'] = 2.1;
  }
}

function stability_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    unset($element['#below']['#theme_wrappers']);
    $sub_menu = '<ul>' . drupal_render($element['#below']) . '</ul>';
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
    $element['#attributes']['class'][] = 'active';
  }
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Implementation of hook_js_alter()
 */
function stability_js_alter(&$javascript) {
  if(isset($javascript['misc/jquery.js'])) {
    $javascript['misc/jquery.js']['data'] = drupal_get_path('theme', 'stability') . '/vendor/jquery-1.11.0.min.js';
  }
}

/**
 * Update breadcrumbs
*/
function stability_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {

    if (!drupal_is_front_page() && !empty($breadcrumb)) {
      $node_title = filter_xss(menu_get_active_title(), array());
      $breadcrumb[] = t($node_title);
    }
    if (count($breadcrumb) == 1) {
      $breadcrumb = array();
    }

    return strip_tags(theme('item_list', array('items' => $breadcrumb, 'attributes' => array('class' => array('breadcrumb')))), '<ul><li><a>');
  }
}

/**
 * Update status messages
*/
function stability_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  $types = array(
    'status' => 'success',
    'error' => 'danger',
    'warning' => 'warning',
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"alert alert-dismissable alert-" . $types[$type] . "\">\n<button type='button' class='close' data-dismiss='alert'>×</button>";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}

/**
 * Overrides theme_item_list().
 */
function stability_item_list($vars) {
  if (isset($vars['attributes']['class']) && is_array($vars['attributes']['class']) && in_array('pager', $vars['attributes']['class'])) {
    foreach($vars['items'] as $i => $item) {
      if(in_array('pager-first', $item['class']) || in_array('pager-last', $item['class'])) {
        $vars['items'][$i]['class'][] = 'hidden-pager';
      }
      if(in_array('pager-current', $item['class'])) {
        $vars['items'][$i]['data'] = '<a href = "#" class = "btn btn-sm btn-primary">' . $item['data'] . '</a>';
      }
    }
    if(array_search('pager-load-more', $vars['attributes']['class']) === FALSE && ($default_class = array_search('pager', $vars['attributes']['class'])) !== FALSE) {
      //unset($vars['attributes']['class'][$default_class]);
    }
    $styles = array(1 => 'pagination-custom list-unstyled list-inline', 2 => 'pagination pagination-lg', 3 => 'pagination', 4 => 'pagination pagination-sm');
    $vars['attributes']['class'][] = $styles[theme_get_setting('pager') ? theme_get_setting('pager') : 1];
    return '<div class="text-center">' . theme_item_list($vars) . '</div>';
  }
  return theme_item_list($vars);
}


function stability_pager_link($variables) {
  $text = $variables['text'];
  $page_new = $variables['page_new'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $attributes = $variables['attributes'];

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('next ›') => t('Go to next page'),
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }
  $replace_titles = array(
    t('‹ previous') => '«',
    t('next ›') => '»',
  );
  $text = isset($replace_titles[$text]) ? $replace_titles[$text] : $text;
  if(!theme_get_setting('pager') || theme_get_setting('pager') == 1) {
    $attributes['class'] = array('btn', 'btn-sm', 'btn-default');
  }

  $attributes['href'] = url($_GET['q'], array('query' => $query));
  return '<a' . drupal_attributes($attributes) . '>' . check_plain($text) . '</a>';
}

function stability_field($variables) {
  $output = '';
  $field_output = array();
  if (!$variables['label_hidden']) {
    $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }
  foreach ($variables['items'] as $delta => $item) {
    $field_output[] = drupal_render($item);
  }
  $output .= implode(', ', $field_output);
  return $output;
}


/**
 * Overrides theme_form_element_label().
 */
function stability_form_element_label(&$variables) {
  $element = $variables['element'];
  $skip = (isset($element['#type']) && ('checkbox' === $element['#type'] || 'radio' === $element['#type']));
  if ((!isset($element['#title']) || $element['#title'] === '' && !$skip) && empty($element['#required'])) {
    return '';
  }
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';
  $title = filter_xss_admin($element['#title']);
  $attributes = array();
  if ($element['#title_display'] == 'after' && !$skip) {
    $attributes['class'][] = $element['#type'];
  }
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'][] = 'element-invisible';
  }
  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }
  $output = '';
  if (isset($variables['#children'])) {
    $output .= $variables['#children'];
  }
  $output .= t('!title !required', array('!title' => $title, '!required' => $required));
  return ' <label' . drupal_attributes($attributes) . '>' . $output . "</label>\n";
}


/**
 * Implements theme_form_element().
 */
function stability_form_element(&$variables) {
  $element = &$variables['element'];
  $is_checkbox = FALSE;
  $is_radio = FALSE;
  $element += array(
    '#title_display' => 'before',
  );
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  if (isset($element['#parents']) && form_get_error($element)) {
    $attributes['class'][] = 'error';
  }
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(
        ' ' => '-',
        '_' => '-',
        '[' => '-',
        ']' => '',
      ));
  }
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  if (!empty($element['#autocomplete_path']) && drupal_valid_path($element['#autocomplete_path'])) {
    $attributes['class'][] = 'form-autocomplete';
  }
  $attributes['class'][] = 'form-item';
  if (isset($element['#type'])) {
    if ($element['#type'] == "radio") {
      $attributes['class'][] = 'radio';
      $is_radio = TRUE;
    }
    elseif ($element['#type'] == "checkbox") {
      $attributes['class'][] = 'checkbox';
      $is_checkbox = TRUE;
    }
    else {
      $attributes['class'][] = 'form-group';
    }
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = '';
  $suffix = '';
  if (isset($element['#field_prefix']) || isset($element['#field_suffix'])) {
    if (!empty($element['#input_group'])) {
      $prefix .= '<div class="input-group">';
      $prefix .= isset($element['#field_prefix']) ? '<span class="input-group-addon">' . $element['#field_prefix'] . '</span>' : '';
      $suffix .= isset($element['#field_suffix']) ? '<span class="input-group-addon">' . $element['#field_suffix'] . '</span>' : '';
      $suffix .= '</div>';
    }
    else {
      $prefix .= isset($element['#field_prefix']) ? $element['#field_prefix'] : '';
      $suffix .= isset($element['#field_suffix']) ? $element['#field_suffix'] : '';
    }
  }
  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables) . ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      if ($is_radio || $is_checkbox) {
        $output .= ' ' . $prefix . $element['#children'] . $suffix;
      }
      else {
        $variables['#children'] = ' ' . $prefix . $element['#children'] . $suffix;
      }
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;
    case 'none':
    case 'attribute':
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }
  if (isset($element['#description'])) {
    $output .= '<p class="help-block">' . $element['#description'] . "</p>\n";
  }
  $output .= "</div>\n";
  return $output;
}


/**
 *  Implements theme_textfield().
 */
function stability_textfield($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'text';
  element_set_attributes($element, array(
    'id',
    'name',
    'value',
    'size',
    'maxlength',
  ));
  _form_set_class($element, array('form-text'));
  $output = '<input' . drupal_attributes($element['#attributes']) . ' />';
  $extra = '';
  if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
    drupal_add_library('system', 'drupal.autocomplete');
    $element['#attributes']['class'][] = 'form-autocomplete';
    $attributes = array();
    $attributes['type'] = 'hidden';
    $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
    $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
    $attributes['disabled'] = 'disabled';
    $attributes['class'][] = 'autocomplete';
    $output = '<div class="input-group">' . $output . '<span class="input-group-addon"><i class = "fa fa-refresh"></i></span></div>';
    $extra = '<input' . drupal_attributes($attributes) . ' />';
  }
  return $output . $extra;
}


/**
 * Implements hook_preprocess_button().
 */
function stability_preprocess_button(&$vars) {
  $vars['element']['#attributes']['class'][] = 'btn';
  $highlite_buttons = array(
    t('Save') => 'btn-primary',
    t('Send message') => 'btn-primary',
    t('Add to cart') => 'btn-primary',
    t('Submit order') => 'btn-primary',
    t('Log in') => 'btn-primary',
    t('Create new account') => 'btn-primary',
  );
  $class = isset($highlite_buttons[$vars['element']['#value']]) ? $highlite_buttons[$vars['element']['#value']] : 'btn-default';
  $vars['element']['#attributes']['class'][] = $class;
}

/**
 * Implements hook_element_info_alter().
 */
function stability_element_info_alter(&$elements) {
  foreach ($elements as &$element) {
    $element['#process'][] = '_stability_process_element';
    if (!empty($element['#input'])) {
      $element['#process'][] = '_stability_process_input';
    }
  }
}


function _stability_process_element(&$element, &$form_state) {
  if (!empty($element['#attributes']['class']) && is_array($element['#attributes']['class'])) {
    if (in_array('container-inline', $element['#attributes']['class'])) {
      $element['#attributes']['class'][] = 'form-inline';
    }
    if (in_array('form-wrapper', $element['#attributes']['class'])) {
      $element['#attributes']['class'][] = 'form-group';
    }
  }
  return $element;
}


function _stability_process_input(&$element, &$form_state) {
  $types = array(
    'textarea',
    'textfield',
    'webform_email',
    'webform_number',
    'select',
    'password',
    'password_confirm',
  );
  if (!empty($element['#type']) && (in_array($element['#type'], $types) || ($element['#type'] === 'file' && empty($element['#managed_file'])))) {
    $element['#attributes']['class'][] = 'form-control';
  }
  return $element;
}

/**
 * Theme a webform date element.
 */
function stability_webform_date($variables) {
  $element = $variables['element'];

  $col_size = !empty($element['#datepicker']) ? 3 : 4;
  $element['year']['#attributes']['class'][] = 'year';
  $element['year']['#prefix'] = '<div class = "col-xs-' . $col_size . '">';
  $element['year']['#suffix'] = '</div><div class = "col-xs-4">';
  $element['month']['#attributes']['class'][] = 'month';
  $element['month']['#suffix'] = '</div><div class = "col-xs-4">';
  $element['day']['#attributes']['class'][] = 'day';
  $element['day']['#suffix'] = '</div>';

  // Add error classes to all items within the element.
  if (form_get_error($element)) {
    $element['year']['#attributes']['class'][] = 'error';
    $element['month']['#attributes']['class'][] = 'error';
    $element['day']['#attributes']['class'][] = 'error';
  }

  $class = array('webform-container-inline', 'row');

  // Add the JavaScript calendar if available (provided by Date module package).
  if (!empty($element['#datepicker'])) {
    $class[] = 'webform-datepicker';
    $calendar_class = array('webform-calendar');
    if ($element['#start_date']) {
      $calendar_class[] = 'webform-calendar-start-' . $element['#start_date'];
    }
    if ($element['#end_date']) {
      $calendar_class[] = 'webform-calendar-end-' . $element['#end_date'];
    }
    $calendar_class[] ='webform-calendar-day-' . variable_get('date_first_day', 0);

    $calendar = theme('webform_calendar', array('component' => $element['#webform_component'], 'calendar_classes' => $calendar_class));
  }

  $output = '';
  $output .= '<div class="' . implode(' ', $class) . '">';
  $output .= drupal_render_children($element);
  $output .= isset($calendar) ? $calendar : '';
  $output .= '</div>';

  return $output;
}

/**
 * Theme a webform time element.
 */
function stability_webform_time($variables) {
  $element = $variables['element'];

  $element['hour']['#attributes']['class'][] = 'hour';
  $element['minute']['#attributes']['class'][] = 'minute';

  // Add error classes to all items within the element.
  if (form_get_error($element)) {
    $element['hour']['#attributes']['class'][] = 'error';
    $element['minute']['#attributes']['class'][] = 'error';
  }

  $output = '<div class="webform-container-inline row"><div class = "col-xs-2">' . drupal_render($element['hour']) . '</div><div class = "col-xs-3">' . drupal_render($element['minute']) . '</div><div class = "col-xs-2">' . drupal_render($element['ampm']) . '</div></div>';

  return $output;
}

/**
 * Implements theme_field()
 *
 * Make field items a comma separated unordered list
 */
function stability_field__field_tags($variables) {
  $output = '';
  if(count($variables['items'])) {
    $output .= '<aside>';
    $output .= $variables['label_hidden'] ? '' : '<div class="title-accent"><h3 class="widget-title">' . $variables['label'] . '</h3></div>';
    $output .= '<div class = "widget_tag_cloud"><div class = "tagcloud">';
    for ($i = 0; $i < count($variables['items']); $i++) {
      $output .= drupal_render($variables['items'][$i]);
    }
    $output .= '</div></div><aside>';
  }
  return $output;
}

/**
 * Theme the username description of the user login form
 * and the user login block.
 */
function stability_sub_lt_username_description($variables) {
  switch ($variables['form_id']) {
    case 'user_login':
      // The username field's description when shown on the /user/login page.
      return '';
      break;
    case 'user_login_block':
      return '';
      break;
  }
}
