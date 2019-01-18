<?php

unset($content['add_to_cart']['#form']['qty']['#title']);
$content['add_to_cart']['#form']['qty']['#attributes']['class'] = array('qty', 'text', 'input-text');
$content['add_to_cart']['#form']['qty']['#prefix'] = '<div class="quantity"><input type="button" value="-" class="minus small-input-button">';
$content['add_to_cart']['#form']['qty']['#suffix'] = '<input type="button" value="+" class="plus small-input-button"></div>';
/* $content['add_to_cart']['#form']['actions']['submit']['#value'] = '&#xf218;';*/

?>
<?php

/*
Автодобавление в корзину при переходе по ссылке из мобильного приложения-сканера
*/
$add_scanned = FALSE;
if (!empty(drupal_get_query_parameters()['add-to-cart']) && !empty(drupal_get_query_parameters()['add_to_cart'])){ 
  $add_scanned = (drupal_get_query_parameters()['add-to-cart'] == 'yes' && 
    drupal_get_query_parameters()['add_to_cart'] == 'yes');
} 


if ($add_scanned) {
  uc_cart_add_item($node->nid, $qty = 1);
};

$rrp_title = t('RRP');

if ($field_discount[0]) {
  $raw_discount_value = $field_discount[0]["taxonomy_term"]->name;
  $discount_percent = substr($raw_discount_value, 0, strpos($raw_discount_value, '%'));
  $discount = TRUE;
}

if ($discount && $extra_10) {
  $discount_percent += 10;
}
if (!$discount && $extra_10) {
  $discount_percent = 10;
}

if ($discount_percent) {
  $discount_coefficient = 1.0 - $discount_percent / 100;
} else {
  $discount_coefficient = 1;
}

?>


<?php hide($content['field_antiprice']);?>

<div id="node-<?php print $node->nid; ?>" class="row <?php print $classes; ?>"<?php print $attributes; ?>>
  <div class="col-md-6 <?php if(!$adaptive_enabled){print ' col-xs-6';}; ?>">
    <!-- Project Slider -->
    <?php if ((isset($content["field_discount"][0]) and $content["field_discount"][0]["#markup"] != "0%") or $extra_10) : ?>
      <span class="onsale"><?php print '-' . $discount_percent . '%';?></span>
    <?php endif;?>
    <div class="owl-carousel owl-theme owl-slider thumbnail">
      <?php foreach(element_children($content['uc_product_image']) as $key): ?>
        <div class="item">
          <?php print render($content['uc_product_image'][$key]); ?>
        </div>
      <?php endforeach; ?>
    </div>
    <!-- Project Slider / End -->
    <div class="spacer lg"></div>
  </div>
  <div class="col-md-6 <?php if(!$adaptive_enabled){print ' col-xs-6';}; ?>">

    <?php if (!$is_gross) :?>
      <div class="tabs">
        <ul class="nav nav-tabs">
          <li class="<?php if (!$extra_10) {print 'half-width';}?> active">
            <a class="tab" data-toggle="tab" href="#tab-stocks">
              <?php
              print t('Warehouse') . " " . t($current_region) . '<br>';
              if ($logged_in && !$seller_limited_access) {
                if ($order_price) {
                  print '<span class="tab-price">' . $currency_symbol[$current_region] . 
                    ($order_price * $discount_coefficient) . '</span>';
                }
                if ($order_price && ($gf_region_stock[$current_region] > 0)) {
                  print " \ ";
                }
                if ($gf_region_stock[$current_region] > 0) {
                  if ($is_manager or $is_creator or $is_admin) {
                    print $gf_region_stock[$current_region];
                  } else {
                    print " &#10003;";
                  }
                } else {
                  print " &#10007;";
                }
              }
              ?>
            </a>
          </li>
          <?php if (!$extra_10) : ?>
          <li class="half-width">
            <a class="tab" id="other-stock-tab-header" data-toggle="tab" href="#tab-other-stock"><?php
              print t('Warehouse') . " " . t($other_region) . "<br>";
              if ($logged_in && !$seller_limited_access) {
                if ($other_order_price) {
                  print '<span class="tab-price">' . $currency_symbol[$other_region] . $other_order_price . '</span>';
                }
                if ($other_order_price && $gf_region_stock[$other_region] > 0) {
                  print " \ ";
                }
                if ($gf_region_stock[$other_region] > 0) {
                  if ($is_manager or $is_creator or $is_admin) {
                    print $gf_region_stock[$other_region];
                  } else {
                    print " &#10003;";
                  }
                } else {
                  print " &#10007;";
                }
              }
            ?>
            </a>
          </li>
          <?php endif; ?>
          <?php if ($is_manager && $is_creator) : ?>
          <li class="col-md-12">
            <a class="tab" id="ofp-tab-header" data-toggle="tab" href="#tab-order">
              <?php
              $totalSum =  views_embed_view('orders_for_production', 'block', $content['field_main_sku']['#items'][0]['value']);
              print t('Order for Production');
              print '<br><span class="tab-price">';
              if ($order_price) {print $order_price;}
              if ($order_price && ($totalSum > 0)) {print '</span>';}
              if ($totalSum > 0) {print $totalSum;}
              ?>
            </a>
          </li>
          <?php endif; ?>
        </ul>
        <div class="tab-content">
          <div id="tab-stocks" class="tab-pane fade row in active">
            <div class="row">
            <div class="available-colors col-md-12"><?php print views_embed_view('groupped_catalog', 'page', $content['field_main_sku']['#items'][0]['value']); ?></div>
            </div>
            <div class="row">
              <div class="col-xs-6">
                <div class="price row">
                  <?php if(!$logged_in || $seller_limited_access): ?>
                    <span class="amount"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('from' => 'show-price')));?></span>
                  <?php endif; ?>
                  <?php if($logged_in && !$seller_limited_access && $order_price): ?>
                    <?php if ($retail_price) {print '<span class="retail-amount col-sm-12">' . $rrp_title . "&nbsp;" . $currency_symbol[$current_region] . $retail_price . '</span>';} ?>
                    <?php if ($current_region != 'all'): ?>
                      <span class="col-sm-12 amount price-<?php print $current_region;?>">
                        <?php
                        if ($discount_coefficient < 1.0) {print '<del>'. $order_price . '</del>&nbsp;';}
                        print $currency_symbol[$current_region] . $order_price * $discount_coefficient;?>
                      </span>
                    <?php else: ?>
                      <span class="amount">
                      <?php print $currency_symbol[GF_STOCK_REGION_RU] . round($gf_region_prices[GF_STOCK_REGION_RU]);?>
                      </span>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <?php if ($is_publicator or $is_admin) {
                  $anchor_content = t('Request price change');
                  $anchor_path = 'node/89336';
                  print l($anchor_content, $anchor_path, ['attributes' => ['class' => 'colorbox-node', 'data-inner-height' => '50%', 'data-inner-width' => '50%'], 'query' => [drupal_get_destination(), 'nid' => $nid, 'region'=>$current_region, 'price'=>$order_price, 'model'=>$model], 'html' => TRUE]);
                  }
                ?>
              </div>

              <div class="col-xs-6">
                  <?php
                  print '<div class="buttons_added">';
                  if(user_access('create orders') && $logged_in && !$seller_limited_access && 
                    ($gf_region_stock[$current_region] > 0) && $order_price) {
                    print render($content['add_to_cart']);
                  } else {
                    print '<span class="fa-stack fa-2x">
  <i class="fa fa-shopping-cart fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x"></i></span>';
                  }
                  print '</div>';
                  ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="spacer xl"></div>
                <div class="region-selector"> <?php
                  $region_selector_block = block_load('gf_stock', 'gf_stock_region_switch');
                  $renderable_region_selector_block = _block_get_renderable_array(_block_render_blocks(array($region_selector_block)));
                  $rs_output = drupal_render($renderable_region_selector_block);
                  print $rs_output;
                  ?>
                </div>
              </div>
            </div>
            <?php print render($content['field_main_description']); ?>
            <div class="row">
              <div class="col-sm-12">
                <hr>
                <div class="table-responsive">
                  <?php
                  unset($content['field_main_description'], $content['field_rating'], $content['field_tags'], $content['field_catalog'], $content['field_antiprice'], $content['field_sku_autocomplete'], $content['field_votes'], $content['field_discount']);
                  $rows = array();
                  foreach($content as $key => $field){
                    if(strpos($key, 'field') !== FALSE && !empty($field)){
                      $content[$key]['#label_display'] = 'hidden';
                      $values = array();
                      foreach(element_children($content[$key]) as $i) {
                        $values[] = render($content[$key][$i]);
                      }
                      $rows[] = array($content[$key]['#title'], implode('<br/>', $values));
                    }
                  }
                  $weight = render($content['weight']);
                  if($weight) {
                    $rows[] = array(t('Weight'), $weight);
                  }
                  $dimensions = render($content['dimensions']);
                  if($dimensions) {
                    $rows[] = array(t('Dimensions'), $dimensions);
                  }
                  print theme('table', array('rows' => $rows, 'attributes' => array('class' => array('table table-striped'))));?>
                </div>
              </div>
            </div>
          </div>
          <?php if (!$extra_10) :?>
          <div id="tab-other-stock" class="tab-pane fade fitVids-tabs-processed row ">
            <div class="row">
              <div class="col-xs-6">
                <div class="price">
                  <?php if(!$logged_in || $seller_limited_access): ?>
                    <span class="amount"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('from' => 'show-price')));?></span>
                  <?php endif; ?>
                  <?php if($logged_in && !$seller_limited_access && $other_order_price): ?>
                    <span class="retail-amount"><?php print ($other_retail_price > 0) ? $rrp_title . ':<br>' .
                        $currency_symbol[$other_region] . $other_retail_price : ''; ?></span>
                      <span class="amount<?php if ($current_region != 'all') print ' price-' . $other_region;?>">
                      <?php print $currency_symbol[$other_region] . $other_order_price;?>
                      </span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="col-xs-6">
                <?php
                print '<div class="buttons_added">';
                  if($logged_in && !$seller_limited_access && 
                    ($gf_region_stock[$other_region] > 0) && $other_order_price) {
                  print '<span class="fa-stack fa-2x">
  <i class="fa fa-shopping-cart fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x"></i></span>';
                  print '</div><span>'. t("Other warehouse is selected") .'</span>';
                } else {
                  print '<span class="fa-stack fa-2x">
  <i class="fa fa-shopping-cart fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x"></i></span>';
                  print '</div>';
                }
              ?>
              </div>
            </div>
            <?php print render($content['field_main_description']); ?>
            <div class="row">
              <div class="col-sm-12">
                <hr>
                <div class="table-responsive">
                  <?php
                  unset($content['field_main_description'], $content['field_rating'], $content['field_tags'], $content['field_catalog'], $content['field_antiprice'], $content['field_sku_autocomplete'], $content['field_votes']);
                  $rows = array();
                  foreach($content as $key => $field){
                    if(strpos($key, 'field') !== FALSE && !empty($field)){
                      $content[$key]['#label_display'] = 'hidden';
                      $values = array();
                      foreach(element_children($content[$key]) as $i) {
                        $values[] = render($content[$key][$i]);
                      }
                      $rows[] = array($content[$key]['#title'], implode('<br/>', $values));
                    }
                  }
                  $weight = render($content['weight']);
                  if($weight) {
                    $rows[] = array(t('Weight'), $weight);
                  }
                  $dimensions = render($content['dimensions']);
                  if($dimensions) {
                    $rows[] = array(t('Dimensions'), $dimensions);
                  }
                  print theme('table', array('rows' => $rows, 'attributes' => array('class' => array('table table-striped'))));?>
                </div>
              </div>
            </div>
        </div>
        <?php endif; ?>
          <?php if ($order_price && $is_manager && $is_creator && $is_admin) : ?>
            <div id="tab-order" class="tab-pane fade row">
              <div class="available-colors col-md-12"><?php print views_embed_view('groupped_catalog', 'page', $content['field_main_sku']['#items'][0]['value']); ?></div>
              <div class="col-md-12">
                <p class="price">
                  <span class="amount"><?php print $order_price; ?></span>
                </p>

                <div class="orders-for-production">

                </div>
              </div>
          </div>
          <?php endif;?>
        </div>
      </div>
    <?php else : ?>
      <div class="tabs">
        <ul class="nav nav-tabs">
          <li class="half-width active">
            <a class="tab" id="ofp-tab-header" data-toggle="tab" href="#tab-order">
              <?php
              $totalSum =  views_embed_view('orders_for_production', 'block', $content['field_main_sku']['#items'][0]['value']);
              if (empty($totalSum)) { $totalSum = ''; }
              print t('Order for Production');
              ($logged_in == true and !($seller_limited_access == true)) ? print '<br><span class="tab-price">' . $order_price . '</span>' . $totalSum : print '';
              ?>
            </a>
          </li>
          <li class="half-width">
            <a class="tab" id="sampord-tab-header" data-toggle="tab" href="#tab-sampord"><?php print t('Order Sample'); ?><br></a>
          </li>
        </ul>
        <?php if ($is_manager or $is_creator or $is_admin) : ?>
        <div class="tab-content">
          <div id="tab-order" class="tab-pane fade row in active">
            <div class="available-colors col-md-12"><?php print views_embed_view('groupped_catalog', 'page', $content['field_main_sku']['#items'][0]['value']); ?></div>
            <div class="col-md-12">
              <p class="price">
                <span class="amount"><?php print $order_price; ?></span>
              </p>

              <div class="orders-for-production">

              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<hr class="lg">
 
