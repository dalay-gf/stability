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

<?php if($logged_in == true and isset($fields['field_image']->content)): ?>
  <div class="text-center"><?php print $fields['field_image']->content; ?></div>
<?php endif; ?>
<?php if($logged_in == false and isset($fields['field_anonym_photo']->content)): ?>
  <div class="text-center"><?php print $fields['field_anonym_photo']->content; ?></div>
<?php endif; ?>
<header class="team-head">
  <?php if(isset($fields['field_position']->content)): ?>
	 <span class="team-head-info"><?php print $fields['field_position']->content; ?></span>
  <?php endif; ?>
	<?php if(isset($fields['title']->content)): ?>
	<h4 class="team-name text-center"><?php print $fields['title']->content; ?></h4>
	<?php endif; ?>
  <ul class="team-social list-unstyled">
	<?php if(isset($fields['field_email']->content)): ?>
		<li>
  			<i class="fa fa-envelope-o"></i><a href="mailto:<?php print $fields['field_email']->content; ?>" target="_blank"><?php print $fields['field_email']->content; ?></a>
		</li>
    <?php endif; ?>
    <?php if(isset($fields['field_skype']->content)): ?>
  		<li>
  		<i class="fa fa-skype"></i>Skype: <a href="skype:<?php print $fields['field_skype']->content; ?>" target="_blank"><?php print $fields['field_skype']->content; ?></a>
  		</li>
    <?php endif; ?>
    <?php if(!empty($fields['field_qq']->content)): ?>
		<li>
		<i class="fa fa-qq"></i>QQ: <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php print $fields['field_qq']->content; ?>&site=qq&menu=yes" target="_blank"><?php print $fields['field_qq']->content; ?></a>
		</li>
    <?php endif; ?>
	<?php if(isset($fields['field_viber']->content)): ?>
		<li>
			<i class="fa fa-phone-square"></i>Viber: <?php print $fields['field_viber']->content; ?>
		</li>
    <?php endif; ?>
    <?php if(isset($fields['field_whatsapp']->content)): ?>
		<li>
		<i class="fa fa-whatsapp"></i>WhatsApp: <a href="https://api.whatsapp.com/send?phone=<?php $phoneno = substr($fields['field_whatsapp']->content, 1); print  $phoneno; ?>"><?php print $fields['field_whatsapp']->content; ?></a>
		</li>
    <?php endif; ?>
    <?php if(isset($fields['field_wechat']->content)): ?>
		<li>
		<i class="fa fa-wechat"></i>WeChat: <?php print $fields['field_wechat']->content; ?>
		</li>
    <?php endif; ?>
    <?php if(isset($fields['field_speak_languages']->content)): ?>
        <li>
            <i class="fa fa-flag"></i><?php print $fields['field_speak_languages']->content; ?></a>
        </li>
    <?php endif; ?>
	</ul>
</header>
<?php if(isset($fields['body']->content)): ?>
  <div class="team-excerpt">
  	<?php print $fields['body']->content; ?>
  </div>
<?php endif; ?>
