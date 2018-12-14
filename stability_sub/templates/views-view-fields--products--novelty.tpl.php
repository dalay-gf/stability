<?php
/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>
<?php
$current_region = $_SESSION['gf_stock_region'];

//Переключатель валют
$default_currency_code = variable_get('uc_currency_code', UC_CURRENCY_DEFAULT_CURRENCY);
$code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;
$symbol = currency_api_get_symbol($code);
$price_value = intval(str_replace($symbol, '', strip_tags($fields['display_price']->content)));
$ruble_sign = '<i class="fa fa-rub" aria-hidden="true"></i>';

if ($price_value > 0) {
    $display_price_base = str_replace('руб.', $ruble_sign, strip_tags($fields['display_price']->content));
    $symbol = str_replace('руб.', $ruble_sign, $symbol);
    $retail_price = $symbol . $price_value * 3;
    $order_price = $symbol . $price_value * 0.9;
  } else {
    $retail_price = '';
    $order_price = '';
    $display_price_base = '';
  }


$ru_price = round($row->gf_stock_prices_region_russia);
$cn_price_yuan = round($row->gf_stock_prices_region_china);
$cn_price = 0;

if ($cn_price_yuan > 0) {$cn_price = round($cn_price_yuan * $yuan_to_rub_rate);}

if ($current_region == $RU_CODE) {
  $current_code = $RU_CODE;
  $other_code = $CN_CODE;
  $curr_reg_price = round($row->gf_stock_prices_region_russia);
} elseif ($current_region == $CN_CODE) {
  $current_code = $CN_CODE;
  $other_code = $RU_CODE;
  if ($cn_price) {
    $curr_reg_price = $cn_price;
  } else {
    $curr_reg_price = FALSE;
  }
} else {
  $current_code = $CN_CODE;
  $other_code = $RU_CODE;
  if ($cn_price) {
    $curr_reg_price = $cn_price;
  } else {
    $curr_reg_price = FALSE;
  }
}
  

//Подгрузка переменной из header/header-1.tpl.php

global $seller_limited_access;

//Путь ссылок на товары
$anchor_path = 'model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content;

(node_last_viewed($row->nid) > 0) ? $node_is_viewed = TRUE : $node_is_viewed = FALSE;

/**
 * РРЦ
 */
if (!is_null($row->gf_stock_prices_region_russia)) {
  $retail_price = $ru_price * 2;
} elseif (!is_null($cn_price)) {
  $retail_price = round($row->gf_stock_prices_region_china) * 3;
}

/* Скидки */
if (count($row->_field_data["nid"]["entity"]->field_discount) > 0) {
  $raw_discount_value = $fields["field_discount"]->content;
  $discount_percent = substr($raw_discount_value, 0, strpos($raw_discount_value, '%'));
  $discount = TRUE;
}

if ($discount and $extra_10) {
  $discount_percent += 10;
} elseif ($extra_10) {
  $discount_percent = 10;
}

if ($discount_percent) {
  $discount_coefficient = 1.0 - $discount_percent / 100;
} else {
  $discount_coefficient = 1;
}

//Голосование
$vote_enabled = FALSE;
?>


<div class="project-item-inner" id="<?php print "nid-" . $fields['nid']->content; ?>">

  <?php 
    // Выделяем "новый" товар(если он опубликован не позже, чем 2 месяца назад).
    $is_new = ((REQUEST_TIME - (int) $fields['created']->content) < (60*60*60*60));
    if($is_new) print '<span class="new"></span>'; 
  ?>

<a href="<?php print url('model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content); ?>">
  <span class="in-stock">
    <?php
      $curr_stock = $row->_field_data["nid"]["entity"]->gf_region_stock[$current_code];
      print '<span>'. t("In Stock") .'</span><br>';
      if ($is_creator or $is_manager or $is_publicator or $is_admin) {
        print $curr_stock;
      } else {
        if ($curr_stock > 0 and $curr_stock < 10) {
          print $curr_stock;
        } elseif ($curr_stock >= 10) {
          print '&gt;10';
        } else {print '&#10007;';}
      }
    ?>
  </span>
</a>

<?php /*($fields['view_1']->content <> '  ') ? $subtotal = $fields['view_1']->content : $subtotal = '0';*/
/*if ($fields['model_qty_stats']->content > 0) {$subtotal = $fields['model_qty_stats']->content;} else {$subtotal = '0';};*/
/*if($is_creator or $is_manager or $is_publicator or $is_admin or $is_wholesaler) $in_orders = '<span class="in-orders">' . '<span>'. t('In Orders') . '</span><br>' . $subtotal . '</span>'; print l($in_orders, $anchor_path, array('query' => array('tab' => 'order'), 'html' => true)); */?>
	<figure class="alignnone project-img">
	<?php 
	  $anchor_text = $fields['uc_product_image']->content;
      print l($anchor_text, $anchor_path, array('html' => TRUE));
    ?>
	</figure>

	<div class="project-desc">
	<h4 class="title<?php if($node_is_viewed == FALSE) {print ' font-bold';};?>"><?php print l(strval($fields['model']->raw), $anchor_path, array('html' => TRUE));?></h4>
        <?php if($logged_in <> true or $seller_limited_access == true): ?>
          <div class="price row">
			<span class="amount col-md-12 col-sm-12 col-xs-12"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-sm', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('src-page' => 'show-price')));?></span>
        <?php elseif (($logged_in == true and $seller_limited_access <> true) or $is_wholesaler): ?>
          <div class="price">
            <div class="row prices">
			  <div class="col-md-9 col-sm-9 col-xs-8">
            <?php if ($retail_price) : ?>
            <span class="retail-amount"><?php print $symbol . $retail_price; ?></span>
            <?php endif; ?>
          </div>
          <?php if ($curr_reg_price) : ?>
          <div class="col-md-3 col-sm-3 col-xs-4">
            <div class="fau">
              <?php ($row->_field_data["nid"]["entity"]->gf_region_stock[$current_code]) ?
                print str_ireplace(t('Add to cart'), '&#xf218;', $fields['addtocartlink']->content): print '<span class="fa-stack"> <i class="fa fa-shopping-cart fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x"></i></span>';
              ?>
            </div>
          </div>
          <div class="col-md-9 col-sm-9 col-xs-8">
            <span class="amount"><?php print $symbol . round($curr_reg_price * $discount_coefficient); ?></span>
          </div>
          <?php endif; ?>
              <?php if ($vote_enabled and $is_gross == false) : ?>
				<div class="col-md-12 col-sm-12 col-xs-6">
				  <span class="rating"><?php print $fields['field_votes']->content; ?></span>
				</div>
			  <?php endif;?>
            </div>
        <?php endif; ?>
          </div>
	</div>

</div>
