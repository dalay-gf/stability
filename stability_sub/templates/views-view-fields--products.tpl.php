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



$code = isset($_SESSION['currency_switcher']) ? $_SESSION['currency_switcher'] : $default_currency_code;
$symbol = currency_api_get_symbol($code);
$price_value = intval(str_replace($symbol, '', strip_tags($fields['display_price']->content)));

if ($price_value > 0) {
    $display_price_base = str_replace('руб.', '&#8381;', strip_tags($fields['display_price']->content));
  } else {
    $display_price_base = '';
  }
?>


<div class="project-item-inner" id="<?php print "nid-" . $fields['nid']->content; ?>">

  <?php 
    // Выделяем "новый" товар(если он опубликован не позже, чем 2 месяца назад).
    $is_new = ((REQUEST_TIME - (int) $fields['created']->content) < (60*60*60*60));
    if($is_new) print '<span class="new"></span>'; 
  ?>

    <figure class="alignnone project-img">
    <a href="<?php print url('model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content); ?>"><?php print $fields['uc_product_image']->content; ?></a>
    </figure>

    <div class="project-desc">
    <h4 class="title"><?php print l($fields['model']->raw, 'model/' . $fields['field_main_sku']->content . '/' . $fields['nid']->content);?></h4>
            <?php if($logged_in <> true or $seller_limited_access == true): ?>
            <span class="price row">
              <span class="amount col-md-12 col-sm-12 col-xs-12"><?php print l(t('Show price'), 'user/register', array('attributes' => array('class' => array('btn', 'btn-sm', 'btn-primary'), 'data-inner-height'=>array('90%'), 'data-inner-width'=>array("40%")), 'query' => array('src-page' => 'show-price')));?></span>
            <?php elseif ($logged_in == true and $seller_limited_access <> true): ?>
            <span class="row price">
                <span class="amount col-md-4 col-sm-4 col-xs-3"><?php print $display_price_base; ?></span>
                <?php if (isset($fields['addtocartlink'])): ?>
                <div class="fau col-md-8 col-sm-8 col-xs-9"><?php print str_ireplace(t('Add to cart'), '&#xf218;', $fields['addtocartlink']->content); ?></div>
                <?php endif; ?>
              <?php endif; ?>
            </span>
            </div>

</div>
