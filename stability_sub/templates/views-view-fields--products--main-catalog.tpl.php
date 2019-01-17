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
// @todo Для Дмитрия Владимировича сделать возможность давать скидку на товар - 10 и 20%

$yuan_to_rub_rate = variable_get('gf_stock_yuan_exchange_rate');

$current_region = $_SESSION['gf_stock_region'];

$ru_price = isset($row->gf_stock_prices_region_russia) ? round($row->gf_stock_prices_region_russia) : 0;
$cn_price_yuan = isset($row->gf_stock_prices_region_china) ? round($row->gf_stock_prices_region_china): 0;
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

/**
 * Переключатель валют
 */
$default_currency_code = variable_get('uc_currency_code', UC_CURRENCY_DEFAULT_CURRENCY);
$code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;
$symbol = currency_api_get_symbol($code);
/*$price_value = intval(str_replace($symbol, '', strip_tags($fields['display_price']->content)));*/
$ruble_sign = '<i class="fa fa-rub" aria-hidden="TRUE"></i>';
if ($symbol == 'руб.') { $symbol = $ruble_sign; }

/**
 * РРЦ
 */
if ($ru_price) {
  $retail_price = $ru_price * GF_RETAIL_PRICE_COEFFICIENT;
} elseif ($cn_price) {
  $retail_price = $cn_price * GF_RETAIL_PRICE_COEFFICIENT;
}

//Путь ссылок на товары
$anchor_path = 'model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content;

(node_last_viewed($row->nid) > 0) ? $node_is_viewed = TRUE : $node_is_viewed = FALSE;



/*Голосование*/
$vote_enabled = FALSE;

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

?>

<div class="project-item-inner" id="<?php print "nid-" . $fields['nid']->content; ?>">
  <?php 
    // Выделяем "новый" товар(если он опубликован не позже, чем 2 месяца назад).
    $is_new = ((REQUEST_TIME - (int) $fields['created']->content) < (60*60*60*60));
    if($is_new) print '<span class="new"></span>'; 
  ?>
  <?php if ($discount_coefficient < 1.0): ?>
  <a href="<?php print url('model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content); ?>">
        <span class="onsale">
          <?php print '-' . $discount_percent . '%'; ?>
        </span>
  </a>
  <?php endif; ?>

      <a href="<?php print url('model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content); ?>">
        <span class="cn-in-stock">
          <?php
          print '<span>'. t("CN") .'</span><br>';
          $cn_stock = $row->_field_data["nid"]["entity"]->gf_region_stock[$CN_CODE];
          if ($is_creator or $is_manager or $is_publicator or $is_admin) {
            print $cn_stock;
          } else {
            if($cn_stock > 0 and $cn_stock < 10) {
                print $cn_stock;
            } elseif ($cn_stock >= 10) {
              print '&gt;10';
            } else {
              print '&#10007;';
            }
          }
          ?>
        </span>
      </a>
      <a href="<?php print url('model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content); ?>">
        <span class="ru-in-stock">
          <?php
          $ru_stock = $row->_field_data["nid"]["entity"]->gf_region_stock[$RU_CODE];
          print '<span>'. t("RU") .'</span><br>';
          if ($is_creator or $is_manager or $is_publicator or $is_admin) {
            print $ru_stock;
          } else {
            if ($ru_stock < 10 and $ru_stock > 0) {
            print $ru_stock;
            } elseif ($ru_stock >=10) {
            print '&gt;10';
            } else {
            print '&#10007;';
            }
          }
          ?>
        </span>
      </a>


  <figure class="alignnone project-img">
    <a href="<?php print url('model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content); ?>"><?php print $fields['uc_product_image']->content; ?></a>
  </figure>

  <div class="project-desc">
    <h4 class="title<?php if($node_is_viewed == FALSE) {print ' font-bold';};?>"><a href="<?php print url('model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content); ?>"><?php print $fields['model']->raw; ?></a></h4>
    <?php if($logged_in <> TRUE or $seller_limited_access == TRUE): ?>
    <div class="price row">
      <span class="amount col-md-12 col-sm-12 col-xs-12"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-sm', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('src-page' => 'show-price')));?></span>
      <?php elseif ($logged_in == TRUE and $seller_limited_access <> TRUE): ?>
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
          <?php if ($vote_enabled and $is_gross == FALSE and $is_publicator == FALSE): ?>
            <div class="col-md-12 col-sm-12 col-xs-6">
              <span class="rating"><?php print $fields['field_votes']->content; ?></span>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
