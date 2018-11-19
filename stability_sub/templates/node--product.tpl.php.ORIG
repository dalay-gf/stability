<?php
/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all, or
 *   print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $display_submitted: whether submission information should be displayed.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 */
unset($content['add_to_cart']['#form']['qty']['#title']);
$content['add_to_cart']['#form']['qty']['#attributes']['class'] = array('qty', 'text', 'input-text');
$content['add_to_cart']['#form']['qty']['#prefix'] = '<div class="quantity"><input type="button" value="-" class="minus small-input-button">';
$content['add_to_cart']['#form']['qty']['#suffix'] = '<input type="button" value="+" class="plus small-input-button"></div>';
/* $content['add_to_cart']['#form']['actions']['submit']['#value'] = '&#xf218;';*/

?>
<?php
/*
Проверка на принадлежность к ролям
*/

/**
 * Переключатель валют
 */
$default_currency_code = variable_get('uc_currency_code', UC_CURRENCY_DEFAULT_CURRENCY);
$code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;
$symbol = currency_api_get_symbol($code);
$ruble_sign = '<i class="fa fa-rub" aria-hidden="true"></i>';

/**
 * установка РРЦ и цены в пошив
 */
if ($current_region == $RU_CODE) {
  $region_display_price = $gf_region_prices[$RU_CODE];
} elseif ($current_region == $CN_CODE) {
  $region_display_price = round($gf_region_prices[$CN_CODE]);
} else {
  $region_display_price = round($gf_region_prices[$RU_CODE]);
}

if ($region_display_price > 0) {
  $display_price_base = $region_display_price;
  $symbol = str_replace('руб.', $ruble_sign, $symbol);
  if ($gf_region_prices['cn'] > 0) {
    $retail_price = round($gf_region_prices[$CN_CODE] * 3);
    $order_price = round($gf_region_prices[$CN_CODE] * 0.9);
  } else {
    $retail_price = round($gf_region_prices[$RU_CODE] * 2);
    $order_price = '';
  }
} else {
  $retail_price = 0;
  $order_price = '';
  $display_price_base = '';
}
$user_data = user_load($user->uid);
$adaptive_field = $user_data->field_adaptive_design['und'];
if ($adaptive_field) {
  $adaptive_enabled = $adaptive_field[0]['value'];
}
else {
  $adaptive_enabled= '';
}

/*
Автодобавление в корзину при переходе по ссылке из мобильного приложения-сканера
*/
if (!empty(drupal_get_query_parameters()['add-to-cart']) OR !empty(drupal_get_query_parameters()['add_to_cart'])){ $add_scanned = (drupal_get_query_parameters()['add-to-cart'] == 'yes' OR drupal_get_query_parameters()['add_to_cart'] == 'yes') ? true : false;} else {$add_scanned = false;};


(uc_stock_level($node->model) > 0) ? $current_stock = uc_stock_level($node->model) : $current_stock = '';

if ($add_scanned) {
  uc_cart_add_item($node->nid, $qty = 1);
};

/*
 * Сумма товаров в заказе
 */
/*$gf_order_actual_sums = db_select('gf_order_production', 'o')
  ->fields('o', ['oid', 'qty_total'])
  ->condition('o.model_id', $node->field_main_sku['und'][0]['value'])
  ->condition('o.status', 0, '=')
  ->execute()
  ->fetchAllKeyed();

$gf_order_total_sum = 0;
foreach($gf_order_actual_sums as $oid => $subsum) {
  $gf_order_total_sum += $subsum;
};*/

$rrp_title = t('RRP');

//Голосование
$vote_enabled = FALSE;

/* Скидки */
if ($is_man_sadovod or $is_opt_sadovod) {
  $extra_10 = TRUE;
}

if ($field_discount[0]) {
  $raw_discount_value = $field_discount[0]["taxonomy_term"]->name;
  $discount_percent = substr($raw_discount_value, 0, strpos($raw_discount_value, '%'));
  $discount = TRUE;
}

if ($discount and $extra_10) {
  $discount_percent += 10;
}
if ($discount != TRUE and $extra_10) {
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
  <div class="col-md-6 <?php if($adaptive_enabled == '0'){print ' col-xs-6';}; ?>">
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
    <?php if ($vote_enabled and $logged_in == true and !($seller_limited_access == true) and $is_gross == false and $is_publicator == false): ?>
      <div class="spacer sm"></div>
      <?php /*print render($content['field_votes']);*/?>
    <?php endif; ?>
    <div class="spacer lg"></div>
  </div>
  <div class="col-md-6 <?php if($adaptive_enabled == '0'){print ' col-xs-6';}; ?>">

    <?php if ($is_gross == false) :?>
      <div class="tabs">
        <ul class="nav nav-tabs">
          <li class="<?php if ($extra_10 != TRUE) {print 'half-width';}?> active">
            <a class="tab" data-toggle="tab" href="#tab-stocks">
              <?php
              print t('Warehouse') . " " . t($current_code) . '<br>';
              if ($logged_in == true and !($seller_limited_access == true)) {
                if (isset($gf_region_prices[$current_code])) {
                  print '<span class="tab-price">' . $symbol . round((float)$gf_region_prices[$current_code] * $discount_coefficient) . '</span>';
                }
                if (isset($gf_region_prices[$current_code]) and $gf_region_stock[$current_code] > 0) {
                  print " \ ";
                }
                if ($gf_region_stock[$current_code] > 0) {
                  if ($is_manager or $is_creator or $is_admin) {
                    print $gf_region_stock[$current_code];
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
          <?php if ($extra_10 != TRUE) : ?>
          <li class="half-width">
            <a class="tab" id="other-stock-tab-header" data-toggle="tab" href="#tab-other-stock"><?php
              print t('Warehouse') . " " . t($other_code) . "<br>";
              if ($logged_in == true and !($seller_limited_access == true)) {
                if (isset($gf_region_prices[$other_code])) {
                  print '<span class="tab-price">' . $symbol . round($gf_region_prices[$other_short_code]) . '</span>';
                }
                if (isset($gf_region_prices[$other_code]) and $gf_region_stock[$other_code] > 0) {
                  print " \ ";
                }
                if ($gf_region_stock[$other_code] > 0) {
                  if ($is_manager or $is_creator or $is_admin) {
                    print $gf_region_stock[$other_code];
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
          <?php if ($is_manager or $is_creator /*or $is_admin and !$is_man_sadovod*/) : ?>
          <li class="col-md-12">
            <a class="tab" id="ofp-tab-header" data-toggle="tab" href="#tab-order">
              <?php
              $totalSum =  views_embed_view('orders_for_production', 'block', $content['field_main_sku']['#items'][0]['value']);
              print t('Order for Production');
              print '<br><span class="tab-price">';
              if ($order_price > 0) {print $order_price;}
              if ($order_price > 0 and $totalSum > 0) {print '</span>';}
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
                  <?php if($logged_in <> true or $seller_limited_access == true): ?>
                    <span class="amount"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('from' => 'show-price')));?></span>
                  <?php endif; ?>
                  <?php if($logged_in == true and !($seller_limited_access == true)): ?>
                    <?php if ($retail_price > 0) {print '<span class="retail-amount col-sm-12">' . $rrp_title . "&nbsp;" . $symbol . $retail_price . '</span>';} ?>
                    <?php if ($current_region != 'all'): ?>
                      <span class="col-sm-12 amount price-<?php print $current_region;?>">
                        <?php
                        if ($discount_coefficient < 1.0) {print '<del>'. round((float)$gf_region_prices[$current_region]) . '</del>&nbsp;';}
                        print $symbol . round((float)$gf_region_prices[$current_region] * $discount_coefficient);?>
                      </span>
                    <?php else: ?>
                      <span class="amount">
                      <?php print $symbol . round($gf_region_prices['ru']);?>
                      </span>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <?php if ($is_publicator or $is_admin) {
                  $anchor_content = t('Request price change');
                  $anchor_path = 'node/89336';
                  print l($anchor_content, $anchor_path, ['attributes' => ['class' => 'colorbox-node', 'data-inner-height' => '50%', 'data-inner-width' => '50%'], 'query' => [drupal_get_destination(), 'nid' => $nid, 'region'=>$current_region, 'price'=>$display_price_base, 'model'=>$model], 'html' => TRUE]);
                  }
                ?>
              </div>

              <div class="col-xs-6">
                  <?php
                  print '<div class="buttons_added">';
                  if($logged_in == true and !($seller_limited_access == true) and $gf_region_stock[$current_code] > 0 and isset($gf_region_prices[$current_code])) {
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
          <?php if ($extra_10 != TRUE) :?>
          <div id="tab-other-stock" class="tab-pane fade fitVids-tabs-processed row ">
            <div class="row">
              <div class="col-xs-6">
                <div class="price">
                  <?php if($logged_in <> true or $seller_limited_access == true): ?>
                    <span class="amount"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('from' => 'show-price')));?></span>
                  <?php endif; ?>
                  <?php if($logged_in == true and !($seller_limited_access == true)): ?>
                    <span class="retail-amount"><?php ($retail_price !== 0) ? print $rrp_title . ':<br>' .
                        $symbol . $retail_price : print ''; ?></span>
                    <?php if ($current_region != 'all'): ?>
                      <span class="amount price-<?php print $current_region;?>">
                      <?php print $symbol . round($gf_region_prices[$other_short_code]);?>
                      </span>
                    <?php else: ?>
                      <span class="amount">
                      <?php print $symbol . round($gf_region_prices[$other_short_code]);?>
                      </span>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>

              <div class="col-xs-6">
                <?php
                print '<div class="buttons_added">';
                if($logged_in == true and !($seller_limited_access == true) and $gf_region_stock[$other_code] >= 1 and isset($gf_region_prices[$other_short_code])) {
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
          <?php if ($is_manager or $is_creator or $is_admin) : ?>
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
 
