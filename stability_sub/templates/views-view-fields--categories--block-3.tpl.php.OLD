<?php
  $src_terms = array(
    'Мужской ассортимент',
    'Мужские портфели',
    'Мужские планшеты',
    'Несессеры',
    'Барсетки',
    'Мужские папки',
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
    'Женские сумки',
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
    'Women\'s Bags',
    'Women\'s Gloves',
    'Women\'s business card holders'
  );
?>
<span>
  <?php
  $asteriks = FALSE;
  $nids = taxonomy_select_nodes($fields['tid']->raw, FALSE);
  if ($asteriks == FALSE) {
    foreach ($nids as $nid) {
      $product = node_load($nid);
      $new_tid = field_get_items('node', $product, 'field_new');
      ($new_tid[0]['tid'] == "1997" or $new_tid[0]['tid'] == "3139") ? $is_new = TRUE : $is_new = FALSE;
      if ($is_new and node_last_viewed($nid) == 0) {
        $asteriks = TRUE;
        break;
      };
    }
  }


  $term_name = str_ireplace($src_terms, $en_terms, $fields['name']->raw);
  $ready_render_term = l(t($term_name), "novelty/" . $fields['tid']->raw, array('attributes' => array('class' => array('novelty-cat-link'))));
  print render($ready_render_term);
  ($asteriks) ? print '&nbsp;<span class="unviewed-items"><i class="fa fa-bolt" aria-hidden="true"></i></span>' : print '';
  ?>
</span>
