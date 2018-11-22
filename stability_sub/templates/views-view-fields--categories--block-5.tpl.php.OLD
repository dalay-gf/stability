<?php
$src_terms = array(
  'Мужской ассортимент',
  'Мужские портфели',
  'Мужские планшеты',
  'Несессеры',
  'Барсетки',
  'Мужские папки',
  'Мужские сумки GF',
  'Мужские сумки DorFlinger',
  'Мужские сумки',
  'Мужские перчатки',
  'Мужские клатчи',
  'Мужские кошельки',
  'Дорожные сумки',
  'Мужские визитницы',
  'Мужские зажим д/денег',
  'Мужские ключницы',
  'Мужские нагрудные кошельки',
  'Мужские обложки',
  'Женский ассортимент',
  'Женские кошельки большие',
  'Женские кошельки средние',
  'Женские кошельки маленькие',
  'Женские сумки Giaguaro',
  'Женские сумки коллекция Giorgio Ferretti',
  'Женские сумки',
  'Женские перчатки шерсть',
  'Женские перчатки',
  'Женские визитницы'
);

$en_terms = array(
  'Men\'s',
  'Briefcases',
  'Document cases',
  'Dressing bag',
  'Top handle bags',
  'Messenger Bags',
  'Men\'s Bags G. Ferretti',
  'Men\'s Bags DorFlinger',
  'Men\'s Bags',
  'Men\'s Gloves',
  'Men\'s clutch bags',
  'Men\'s wallet',
  'Travel Bags',
  'Men\'s business card holders',
  'Men\'s money clip',
  'Men\'s key wallet',
  'Men\'s breast wallets',
  'Men\'s covers for documents',
  'Women\'s',
  'Big Women\'s Wallets',
  'Middle-sized Women\'s Wallets',
  'Small Women\'s Wallets',
  'Women\'s Bags Giaguaro',
  'Women\'s Bags G. Ferretti',
  'Women\'s Bags',
  'Women\'s Wool Gloves',
  'Women\'s Gloves',
  'Women\'s business card holders'
);
?>
<span>
  <?php
  /*$asteriks = FALSE;
  $nids = taxonomy_select_nodes($fields['tid']->raw, FALSE);
  foreach ($nids as $nid) {
    $array_sku = uc_product_get_models($nid, FALSE);
    foreach ($array_sku as $sku) {
      $stock_level = uc_stock_level($sku);
    }
    if ($stock_level > 0 and node_last_viewed($nid) == 0) {
      $asteriks = TRUE;
      break;
    };
  }*/

  $term_name = str_ireplace($src_terms, $en_terms, $fields['name']->raw);
  $ready_render_term = l(t($term_name), "shop/" . $fields['tid']->raw, array('attributes' => array('class' => array('shop-cat-link'))));
  print render($ready_render_term);
  /*if ($asteriks) {print '&nbsp;<span class="unviewed-items"><i class="fa fa-bolt" aria-hidden="true"></i></span>' :}*/
  ?>
</span>
