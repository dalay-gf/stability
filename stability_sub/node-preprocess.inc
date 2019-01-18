<?php

/**
 * Implements hook_preprocess_node()/
 */
function stability_sub_preprocess_node(&$variables) {
  if (in_array($variables['node']->type, uc_product_types())) {
    global $user;
    $node = $variables['node'];

    $extra_10 = FALSE;
    (user_has_role(18)) ? $is_man_sadovod = $extra_10 = TRUE : $is_man_sadovod = FALSE;
    (user_has_role(19)) ? $is_opt_sadovod = $extra_10 = TRUE : $is_opt_sadovod = FALSE;


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

    $original_price = [
      GF_STOCK_REGION_RU => isset($node->gf_region_prices_original['руб']) ? 
      round($node->gf_region_prices_original['руб']) : 0,
        GF_STOCK_REGION_CN => isset($node->gf_region_prices_original['юан']) ? 
        round($node->gf_region_prices_original['юан']) : 0,
        ];

    $variables['original_price'] = $original_price;

    $ruble_sign = '<i class="fa fa-rub" aria-hidden="true"></i>';
    $variables['original_currency_symbol'] = [
      GF_STOCK_REGION_CN => '<i class="fa fa-cny" aria-hidden="true"></i>',
      GF_STOCK_REGION_RU => $ruble_sign,
    ];

    $order_price = (isset($node->gf_region_stock[$current_region]) && 
      isset($node->gf_region_prices[$current_region])) ? round($node->gf_region_prices[$current_region]) : 0;

    if ($current_region == GF_STOCK_REGION_RU) {
      $other_region = GF_STOCK_REGION_CN; 
    } 
    else {
      $yuan_to_rub_rate = variable_get('gf_stock_yuan_exchange_rate');
      $order_price = round($order_price / $yuan_to_rub_rate);
      $other_region = GF_STOCK_REGION_RU; 
    }

    $variables['other_region'] = $other_region; 


    $field_adaptive = user_load($user->uid)->field_adaptive_design;


    $default_currency_code = variable_get('uc_currency_code', UC_CURRENCY_DEFAULT_CURRENCY);
    $code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;

    $variables['symbol'] = str_replace('руб.', $ruble_sign, currency_api_get_symbol($code));
    $variables['adaptive_enabled'] = isset($field_adaptive[LANGUAGE_NONE]) ?
      $field_adaptive[LANGUAGE_NONE][0]['value'] : TRUE;



    $variables['order_price'] = $order_price;
    $variables['retail_price'] = $order_price ? ($order_price * GF_RETAIL_PRICE_COEFFICIENT) : 0;

  }

}
