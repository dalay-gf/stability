<?php

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
  } 
  else {
    $variables['current_code'] = GF_STOCK_REGION_CN; 
    $variables['other_code'] = GF_STOCK_REGION_RU; 
  }



  $field_adaptive = user_load($user->uid)->field_adaptive_design;

  $current_region_price = $node->gf_region_prices[$current_region] ?? 0;
  $default_currency_code = variable_get('uc_currency_code', UC_CURRENCY_DEFAULT_CURRENCY);
  $code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;

  $variables['symbol'] = str_replace('руб.', $ruble_sign, currency_api_get_symbol($code));
  $variables['display_price_base'] = round($current_region_price);
  $variables['adaptive_enabled'] = isset($field_adaptive[LANGUAGE_NONE]) ?
    $field_adaptive[LANGUAGE_NONE][0]['value'] : TRUE;




  $variables['retail_price'] = $variables['display_price_base'] * GF_RETAIL_PRICE_COEFFICIENT;
  $variables['order_price'] = $node->gf_region_prices[$current_region] ?? 0;




  // if ($node->nid == 96965) {
  //   // Подключаем скрип передачи данных в Roistat на 
  //   // странице invite.
  //   drupal_add_js(drupal_get_path('theme', 'stability_sub') . '/js/roistat/roistat_invite.js');
  // }
}
