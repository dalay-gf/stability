<?php
  if((isset($node)) && (gettype($node) == 'object') && ($node->type == 'product')) { $title = $node->model;};
  $invite_page = FALSE;
  if ((isset($node)) && ($node->nid == '96965')) {$invite_page = TRUE;}
?>
<section class="page-heading">
  <div class="container">
    <div class="row">
      <div class="<?php ($invite_page) ? print 'centered' : print 'col-md-6';?>">
        <?php print render($title_prefix); ?>
        <h1><?php print $title;?></h1>
        <?php print render($title_suffix); ?>
      </div>
      <?php if ($invite_page == FALSE) :?>
      <div class="col-md-6">
        <span id="gf-role-countdown-role-countdown">
          <?php if (user_has_role(4)) {
            $block = module_invoke('gf_role_countdown', 'block_view', 'role_countdown');
            print render($block['content']);
          }
          ?>
        </span>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

