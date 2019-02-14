<?php


//Подгрузка переменной из header/header-1.tpl.php

global $seller_limited_access;

//Путь ссылок на товары
$anchor_path = 'model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content;

(node_last_viewed($row->nid) > 0) ? $node_is_viewed = TRUE : $node_is_viewed = FALSE;


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
  <h4 class="title<?php if(!$node_is_viewed) {print ' font-bold';};?>"><?php print l(strval($fields['model']->raw), $anchor_path, array('html' => TRUE));?></h4>
        <?php if(!$logged_in || $seller_limited_access): ?>
          <div class="price row">
      <span class="amount col-md-12 col-sm-12 col-xs-12"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-sm', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('src-page' => 'show-price')));?></span>
        <?php elseif (($logged_in && !$seller_limited_access) || $is_wholesaler): ?>
          <div class="price">
            <div class="row prices">
        <div class="col-md-9 col-sm-9 col-xs-8">
          <?php if ($curr_reg_price) : ?>
            <?php if ($retail_price) : ?>
            <span class="retail-amount"><?php print $symbol . $retail_price; ?></span>
            <?php endif; ?>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-4">
            <div class="fau">
            <?php ($row->_field_data["nid"]["entity"]->gf_region_stock[$current_code]) ?
            print str_ireplace(t('Add to cart'), '&#xf218;', $addtocartlink)
            : print '<span class="fa-stack"> <i class="fa fa-shopping-cart fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x"></i></span>';
            ?>
            </div>
          </div>
          <div class="col-md-9 col-sm-9 col-xs-8">
            <span class="amount"><?php print $symbol . round($curr_reg_price * $discount_coefficient); ?></span>
          </div>
          <?php else: ?>
            <?php print $not_avaible_text; ?>
          <?php endif; ?>
            </div>
        <?php endif; ?>
          </div>
  </div>
</div>
