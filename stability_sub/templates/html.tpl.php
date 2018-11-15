<!DOCTYPE html>
<!--[if IE 7]>                  <html class="ie7 no-js" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>  <!--<![endif]-->
<?php
function get_browser_name($user_agent)
{
if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'opera';
elseif (strpos($user_agent, 'Edge')) return 'edge';
elseif (strpos($user_agent, 'Chrome')) return 'chrome';
elseif (strpos($user_agent, 'Safari')) return 'safari';
elseif (strpos($user_agent, 'Firefox')) return 'firefox';
elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'old-ie';

return 'Other';
};

$browser_model = get_browser_name($_SERVER['HTTP_USER_AGENT']);


$user_data = user_load($user->uid);
if (isset($user_data->field_adaptive_design['und'])) {
  $adaptive_field = $user_data->field_adaptive_design['und'];
  } else {
	$adaptive_field = false;
  }
if ($adaptive_field) {
  $adaptive_enabled = $adaptive_field[0]['value'];
}
else {
  $adaptive_enabled= '';
}

(user_has_role(18)) ? $is_man_sadovod = true : $is_man_sadovod = false;
(user_has_role(19)) ? $is_opt_sadovod = true : $is_opt_sadovod = false;

?>

<head>
  <title><?php print $head_title; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

  <?php if ($adaptive_enabled != '0'):?>
  <!-- Mobile Specific Metas
  ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php endif; ?>
  <link rel="shortcut icon" type="image/png" href="<?php print base_path() . drupal_get_path('theme', 'stability_sub'); ?>/images/logo-small.png">
  <?php print $styles; ?>

	<!-- Head Libs -->
	<script src="<?php print base_path() . drupal_get_path('theme', 'stability_sub'); ?>/vendor/modernizr.js"></script>

	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="<?php print base_path() . drupal_get_path('theme', 'stability_sub'); ?>/vendor/respond.min.js"></script>
	<![endif]-->

	<!--[if IE]>
		<link rel="stylesheet" href="<?php print base_path() . drupal_get_path('theme', 'stability_sub'); ?>/css/ie.css">
	<![endif]-->
</head>
<body class="<?php print $classes . ' ' . $browser_model; if ($adaptive_enabled == '0') { print ' non-adaptive';}; if ($is_opt_sadovod or $is_man_sadovod) { print ' reg-fix';}; ?>"
  <?php print $attributes; ?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $scripts; ?>
  <script>
(function(w, d, s, h, id) {
   w.roistatProjectId = id; w.roistatHost = h;
   var p = d.location.protocol == "https:" ? "https://" : "http://";
   var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init";
   var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
})(window, document, 'script', 'cloud.roistat.com', '6fe17be32711e9af7745b1c8c1e70db6');
</script>
  <?php print $page_bottom; ?>
</body>
</html>
