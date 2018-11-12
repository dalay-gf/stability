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
 * - $display_submitted: whether submissionformation should be displayed.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result "node-blog". Note that the machine
 *     name will often be a short form of the human readable label.
 *   - node-teaser: Nodes teaser form.
 *   - node-preview: Nodes preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes teaser listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules,tended to be displayed front of the main title tag that
 *   appears the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules,tended to be displayed after the main title tag that appears
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *  to a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping
 *   teaser listings.
 * - $id: Position of the node.crements each time it's output.
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
 * - $is_front: Flags true when presented the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each fieldstance attached to the node a corresponding
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
?>
<?php if (!$page): ?>
  <article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
<?php endif; ?>
    <?php if (!$page): ?>
      <header>
	<?php endif; ?>
        <?php
        // Hide comments, tags, and links now so that we can render them later.
        hide($content['comments']);
        hide($content['links']);
        hide($content['body']);
        hide($content['field_1_1']);
        hide($content['field_1_2']);
        hide($content['field_1_3']);
        ?>
        <section class="nd-region section-dark h-outline d-violet">
          <div class="container">
            <div class="col-md-3">
              <?php print render($content['field_1_1']); ?>
            </div>
            <div class="col-md-6">
              <div class="field-1-2">
                <?php
                print render($content['field_1_2']);?>
              </div>
              <div class="row">
                <div class="col-md-8 col-md-offset-2">
                  <?php
                  $form_call_request = module_invoke('webform', 'block_view','client-block-96967');
                  print render($form_call_request['content']);
                  ?>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <?php print render($content['field_1_3']); ?>
            </div>
          </div>
        </section>
  <?php if (!$page): ?>
    </header>
  <?php endif; ?>

  <div class="content clearfix <?php print $classes_array['1']; ?>"<?php print $content_attributes; ?>>
    <section class="nd-region promo-cat">
        <nav class="nav-second l-grey-bg row">
          <div class="container">
          <ul class="flexnav with-js opacity lg-screen">
            <li class="tb-megamenu-item level-1 mega">
              <a class="tab active" id="bags-tab-header" data-toggle="tab" href="#tab-bags">Сумки</a>
            </li>
            
			<li class="tb-megamenu-item level-1 mega">
              <a class="tab" id="briefcases-tab-header" data-toggle="tab" href="#tab-briefcases">Портфели</a>
            </li>

            <li class="tb-megamenu-item level-1 mega">
              <a class="tab" id="wallets-tab-header" data-toggle="tab" href="#tab-wallets">Кошельки</a>
            </li>
            
            <li class="tb-megamenu-item level-1 mega">
              <a class="tab" id="gloves-tab-header" data-toggle="tab" href="#tab-gloves">Перчатки</a>
            </li>
            
            <li class="tb-megamenu-item level-1 mega">
              <a class="tab" id="belts-tab-header" data-toggle="tab" href="#tab-belts">Ремни</a>
            </li>
            
            <li class="tb-megamenu-item level-1 mega">
              <a class="tab" id="accessories-tab-header" data-toggle="tab" href="#tab-accessories">Платки</a>
            </li>

		  </ul>
          </div>
        </nav>
      <div class="container">
        <div class="tab-content">
            <div id="tab-bags" class="tab-pane fade row in active">
              <?php
                $bags_cat = implode('+', array(2764, 2756, 3579, 2749));
                print views_embed_view('products', 'block_landing', $bags_cat);
              ?>
            </div>
            
            <div id="tab-briefcases" class="tab-pane fade row">
              <?php
                $briefcases_cat = implode('+', array(2748));
                print views_embed_view('products', 'block_landing', $briefcases_cat);
              ?>
            </div>
            
            <div id="tab-wallets" class="tab-pane fade row">
              <?php
                $wallets_cat = implode('+', array(2769, 2771, 2770, 2752, 2988));
                print views_embed_view('products', 'block_landing', $wallets_cat);
              ?>
            </div>

            <div id="tab-gloves" class="tab-pane fade row">
              <?php
                $gloves_cat = implode('+', array(2755, 2943, 2767));
                print views_embed_view('products', 'block_landing', $gloves_cat);
              ?>
            </div>
            
            <div id="tab-belts" class="tab-pane fade row">
              <?php
                $belts_cat = implode('+', array(2795, 2803));
                print views_embed_view('products', 'block_landing', $belts_cat);
              ?>
            </div>

            <div id="tab-accessories" class="tab-pane fade row">
              <?php
                $accessories_cat = implode('+', array(3365));
                print views_embed_view('products', 'block_landing', $accessories_cat);
              ?>
            </div>

          </div>
      </div>
    </section>
    <div class="spacer"></div>
    <section class="nd-region section-light l-grey-bg v-padding-off">
      <div class="container call-request-with-name">
        <?php
        $form_call_request_with_name = module_invoke('webform', 'block_view','client-block-96969');
        print render ($form_call_request_with_name['content']);
        ?>
      </div>
    </section>
    <section class="nd-region section-dark h-outline">
    <div class="container">
      <div class="partners-desc"><h3>С нами работают:</h3></div>
    </div>
      <div class="row">
        <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A628b738e4efd85aeebdc362c573577f97444c269acd10667b56b2319ccc0a507&amp;width=100%&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
      </div>
    </section>
    <section class="nd-region section-light l-grey-bg v-padding-off">
      <div class="container form-subscribe-me">
        <?php
        $form_subscribtion = module_invoke('webform', 'block_view','client-block-96970');
        print render($form_subscribtion['content']);
        ?>
      </div>
    </section>
  </div>

  <?php if (!empty($content['links'])): ?>
    <footer>
      <?php print render($content['links']); ?>
    </footer>
  <?php endif; ?>

  <?php print render($content['comments']); ?>
<?php if (!$page): ?>
  </article> <!-- /.node -->
<?php endif; ?>
