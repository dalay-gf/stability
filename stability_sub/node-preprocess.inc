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

    $other_region = ($current_region == GF_STOCK_REGION_RU) ? GF_STOCK_REGION_CN : GF_STOCK_REGION_RU;

    $variables['seller_limited_access'] = user_has_role(10);
    $variables['is_gross'] = user_has_role(12);
    $variables['is_publicator'] = user_has_role(13);
    $variables['is_manager'] = user_has_role(5);
    $variables['is_creator'] = user_has_role(8);
    $variables['is_man_sadovod'] = $is_man_sadovod;
    $variables['is_opt_sadovod'] = $is_opt_sadovod;
    $variables['current_region'] = $current_region;
    $variables['extra_10'] = $extra_10;
    $variables['currency_symbol'] = [
      GF_STOCK_REGION_CN => '<i class="fa fa-cny" aria-hidden="true"></i>',
      GF_STOCK_REGION_RU => '<i class="fa fa-rub" aria-hidden="true"></i>',
    ];

    $price_ru = ($node->gf_region_stock[GF_STOCK_REGION_RU] > 0) ? 
      round($node->gf_region_prices[GF_STOCK_REGION_RU]) : 0;
    $price_cn = ($node->gf_region_stock[GF_STOCK_REGION_CN] > 0) ? 
      round($node->gf_region_prices[GF_STOCK_REGION_CN]) : 0;

    $yuan_to_rub_rate = variable_get('gf_stock_yuan_exchange_rate');

    $retail_price = ($price_ru) ? ($price_ru * GF_RETAIL_PRICE_COEFFICIENT) : 0;
    $retail_price_cn = ($retail_price / $yuan_to_rub_rate);

    if ($current_region == GF_STOCK_REGION_CN) {
      $order_price = $price_cn;
      $other_retail_price = $retail_price;
      $retail_price = $retail_price_cn;
      $other_order_price = $price_ru;
    } 
    else {
      $order_price = $price_ru;
      $other_order_price = $price_cn;
      $other_retail_price = $retail_price_cn;
    }

    $variables['other_region'] = $other_region; 


    $field_adaptive = user_load($user->uid)->field_adaptive_design;


    $variables['adaptive_enabled'] = isset($field_adaptive[LANGUAGE_NONE]) ?
      $field_adaptive[LANGUAGE_NONE][0]['value'] : TRUE;



    $variables['order_price'] = $order_price;
    $variables['retail_price'] = round($retail_price);

    $variables['other_order_price'] = $other_order_price;
    $variables['other_retail_price'] = round($other_retail_price);
  }

}
