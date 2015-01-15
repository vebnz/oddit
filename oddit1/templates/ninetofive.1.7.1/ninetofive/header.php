<?php
global $Magnet;
if (!session_id())
 session_start();
setcookie('MagnetAttached_number', "", time() - 3600, '/');
if (!isset($_SESSION['active'])) {
 $allcats = get_categories("hide_empty=0");
 foreach ($allcats as $cat)
 {
  $_SESSION[$cat->category_nicename] = "true";
 }
}
if (is_home()) {
 $Magnet->clearExpiredPosts();
}
if (is_user_logged_in()) {
 global $user_login;
 get_currentuserinfo();
 wp_get_current_user();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>
 <?php
 if (function_exists('is_tag') && is_tag()) {
  single_tag_title('Tag Archive for &quot;');
  echo '&quot; - ';
 }
 elseif (is_archive())
 {
  wp_title('');
  echo ' ' . __("Archive", "9to5") . ' - ';
 }
 elseif (is_search())
 {
  echo __("Search for", "9to5") . ' &quot;' . esc_html($s) . '&quot; - ';
 }
 elseif (!(is_404()) && (is_single()) || (is_page()))
 {
  wp_title('');
  echo ' - ';
 }
 elseif (is_404())
 {
  echo __("Not Found", "9to5") . ' - ';
 }
 if (is_home()) {
  bloginfo('name');
  echo ' - ';
  bloginfo('description');
 }
 else
 {
  bloginfo('name');
 }
 if ($paged > 1) {
  echo ' - ' . __("page", "9to5") . ' ' . $paged;
 }
 ?>
</title>
<?php wp_head(); ?>
<link href="<?php echo get_option("9t5_favicon") ?>" rel="shortcut icon"/>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("webfont", "1");
  google.load("jquery", "1.7.1");
  google.load("jqueryui", "1.8.6");

  google.setOnLoadCallback(function() {
    WebFont.load({
      google: {
        families: [ 'Copse' ]
      }});
  });
</script>

<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jquery.autocomplete.pack.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jquery.select_skin.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jquery.pngfix.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jcookie.js"></script>
<script type="text/javascript">

jQuery(document).ready(function () {

 jQuery("form select.styled").select_skin();
 jQuery(".wp-post-image").addClass("alignright");
 jQuery(".listing iframe").attr("width", "688");


 if (jQuery(".hidden").length == jQuery("ol.list li:not(#notice, #results)").length) {
  setTimeout(function () {
   jQuery("#notice").fadeIn();
  }, 500);
 }

<?php if (!isset($_SESSION['active'])) {
 ?>
 jQuery("#all").addClass("active");
 <?php } ?>

 jQuery(".categories select").change(function () {
  var filterVar = jQuery(this).val();
  var dataString = 'cat=activeCat&val=' + filterVar;
  if (filterVar == "all") {
   successURL = "<?php echo site_url(); ?>";
  } else {
   successURL = "<?php echo site_url() ?>/category/" + filterVar;
  }
  jQuery.ajax({
   type:"POST",
   url:"<?php echo get_template_directory_uri() ?>/scripts/variable.php",
   data:dataString,
   success:function () {
    window.location = successURL;
   }
  });
 })

 // Live Filtering

 jQuery("select").change(function () {


  var filter = jQuery('select').val();
  var dataString = 'cat=active&val=' + jQuery(this).attr("id");
  jQuery.ajax({
   type:"POST",
   url:"<?php echo get_template_directory_uri() ?>/scripts/variable.php",
   data:dataString
  });

  if (filter == "all") {
   jQuery('ol.list li').removeClass("disabled");
   jQuery('ol.list li').removeClass("enabled");
   jQuery(".categories input[type=checkbox]").attr("checked", true);
   jQuery(".categories input[type=checkbox]").trigger("click");
   jQuery(".categories input[type=checkbox]").attr("checked", true);
   updateJobCount();
  } else {
   jQuery('ol.list .' + filter + ":not(#notice, #results)").removeClass("disabled");
   jQuery('ol.list .' + filter + ":not(#notice, #results)").addClass("enabled");

   jQuery("ol.list li:not(." + filter + ", #notice, #results)").addClass("disabled");
   jQuery("ol.list li:not(." + filter + ", #notice, #results)").removeClass("enabled");
   updateJobCount();
  }

  updateJobCount();
  return false;
 });


 // Live Filtering
 jQuery(".filter li a").click(function () {
  var filter = jQuery(this).attr("id"); // Checked: 6 Aug - Working

  jQuery(".filter li a").removeClass("active");
  jQuery(this).addClass("active");

  var dataString = 'cat=active&val=' + jQuery(this).attr("id");
  jQuery.ajax({
   type:"POST",
   url:"<?php echo get_template_directory_uri() ?>/scripts/variable.php",
   data:dataString
  });

  if (filter == "all") {
   // jQuery("ol.list li:not(#notice, #results)").animate({"opacity":"1"},300);
   jQuery('ol.list li:not(#notice, #results)').removeClass("disabled");
   jQuery('ol.list li:not(#notice, #results)').addClass("enabled");
   jQuery(".categories input[type=checkbox]").attr("checked", true);
   jQuery(".categories input[type=checkbox]").trigger("click");
   jQuery(".categories input[type=checkbox]").attr("checked", true);
   updateJobCount();
  } else {
   jQuery("ol.list li." + filter + ":not(#notice, #results)").removeClass("disabled");
   // jQuery('ol.list .' + filter+":not(#notice, #results)").removeClass("disabled");
   jQuery('ol.list .' + filter + ":not(#notice, #results)").addClass("enabled");

   jQuery("ol.list li:not(." + filter + ", #notice, #results)").addClass("disabled");
   // jQuery("ol.list li:not(."+filter+", #notice, #results)").addClass("disabled");
   jQuery("ol.list li:not(." + filter + ", #notice, #results)").removeClass("enabled");
   updateJobCount();
  }

  if (jQuery(this).hasClass(".active")) {
   jQuery(this).removeClass(".active");
  } else {
   jQuery(".filter li a").removeClass(".active");
   jQuery(this).addClass(".active");
  }

  updateJobCount();
  return false;
 });

 var totalJobs;

 function updateJobCount() {
  totalJobs = jQuery("ol.list li").length;
  if (jQuery("ol.list li.disabled").length != totalJobs) {
    totalJobs -= jQuery("ol.list li.disabled").length;
    var jobtext = "";
    ( totalJobs == "1" ? jobtext = " job found" : jobtext = " jobs found" );
    jQuery("div.paging p.count").html("<strong>"+totalJobs+"</strong>"+jobtext);
  } else {
   jQuery("div.paging p.count").html("<?php _e('No jobs found', '9to5') ?>");
   jQuery("span.job-count").text("");
  }
 }

 // Autocomplete
 jQuery("#search_box, #tags, input[name=skills_value]").autocompletefn("<?php echo get_template_directory_uri() ?>/scripts/keywords.php", { selectFirst:false  });

 // Sustained Category Filters
 var counter = 0;
 jQuery(".categories input").click(function () {

  if (jQuery(this).attr('checked') == false) {
   var dataString = 'cat=' + jQuery(this).attr("id") + '&val=false';
   counter++;
   jQuery.ajax({
    type:"POST",
    url:"<?php echo get_template_directory_uri() ?>/scripts/variable.php",
    data:dataString
   });
  } else {
   var dataString = 'cat=' + jQuery(this).attr("id") + '&val=true';
   counter++;
   jQuery.ajax({
    type:"POST",
    url:"<?php echo get_template_directory_uri() ?>/scripts/variable.php",
    data:dataString
   });
  }
  updateJobCount();
 });

 var skills = jQuery(".skills").children("li").length;
 var visible = skills - jQuery(".skills").children("li").children("div").children(".hidden").length;
 var maxSkills = <?php echo get_option('9t5_max_skills'); ?>;
 var selected = skills - 3;

 jQuery(".hidden").parent().parent().hide();

 jQuery("#addskill").click(function () {

  visible++;

  if (skills == visible) {
   jQuery(this).fadeOut();
  }

  if (skills >= visible) {
   jQuery(".skills #skill" + visible).removeClass("hidden");
   jQuery(".skills #skill" + visible).parent().parent().show();
  }

  return false;
 })

 jQuery("#date").datepicker({
  dateFormat:'yy/mm/dd',
  onSelect:function (dateText, inst) {
   jQuery('#labelexpdate').removeClass('yes').removeClass('no').addClass('yes')
  }
 });

 jQuery("#enableApply").change(function () {
  if (!jQuery(this).is(':checked')) {
   jQuery(".onPageApplication").addClass("hide");
   jQuery(".field_apply").addClass("last");
   firstCheck = false;
  } else {
   jQuery(".onPageApplication").removeClass("hide");
   jQuery(".field_apply").removeClass("last");
   firstCheck = false;
  }
 });

 jQuery("#enableApply").trigger("click");

 jQuery("input[name=anon]").click(function () {
  if (jQuery("input[name=anon]").attr("checked") && jQuery("input[name=company_name_value]").val() != "<?php _e('Anonymous', '9to5') ?>") {

   jQuery("#companyName").val('<?php _e('Anonymous', '9to5') ?>');
   jQuery("#companyName").addClass("disabled");
   jQuery("#companyName").attr("disabled", true);
   jQuery("#companyName").css("color", '#000');
   jQuery('#labelcompany_name_value').removeClass('yes').removeClass('no').addClass('yes');
   jQuery("input[name=anon]").attr("checked", true);
  } else {
   jQuery("#companyName").val('<?php _e('e.g. Acme Inc.', '9to5') ?>');
   jQuery("#companyName").removeClass("disabled");
   jQuery("#companyName").attr("disabled", false);
   jQuery("#companyName").css("color", '#CCC');
   jQuery('#labelcompany_name_value').removeClass('yes').removeClass('no').addClass('no');
   jQuery("input[name=anon]").attr("checked", false);

  }
 });

 jQuery("input[name=anywhere]").click(function () {
  if (jQuery("input[name=anywhere]").attr("checked") && jQuery("input[name=location_value]").val() != "<?php _e('Anywhere', '9to5') ?>") {

   jQuery("input[name=location_value]").val('<?php _e('Anywhere', '9to5') ?>');
   jQuery("input[name=location_value]").addClass("disabled");
   jQuery("input[name=location_value]").attr("disabled", true);
   jQuery("input[name=location_value]").css("color", '#000');
   jQuery('#labellocation_value').removeClass('yes').removeClass('no').addClass('yes');
   jQuery("input[name=anywhere]").attr("checked", true);

  } else {

   jQuery("input[name=location_value]").val('<?php _e('e.g. San Francisco, USA', '9to5') ?>');
   jQuery("input[name=location_value]").removeClass("disabled");
   jQuery("input[name=location_value]").attr("disabled", false);
   jQuery("input[name=location_value]").css("color", '#CCC');
   jQuery('#labellocation_value').removeClass('yes').removeClass('no').addClass('no');
   jQuery("input[name=anywhere]").attr("checked", false);

  }
 });

 jQuery("input[name=noexpire]").click(function () {
  if (jQuery("input[name=noexpire]").attr("checked") && jQuery("#date").val() != "<?php _e('No expiry date', '9to5') ?>") {

   jQuery("#date").val('<?php _e('No expiry date', '9to5') ?>');
   jQuery("#date").addClass("disabled");
   jQuery("#date").attr("disabled", true);
   jQuery('#labelexpdate').removeClass('yes').removeClass('no').addClass('yes');
   jQuery("input[name=noexpire]").attr("checked", true);

  } else {

   jQuery("#date").val('yyyy/mm/dd');
   jQuery("#date").removeClass("disabled");
   jQuery("#date").attr("disabled", false);
   jQuery('#labelexpdate').removeClass('yes').removeClass('no').addClass('no');
   jQuery("input[name=noexpire]").attr("checked", false);

  }
 });

<?php
if (is_archive()) {
 ?>

 jQuery("a#all").trigger("click");

 <?php } ?>

 jQuery("ul.filter .active").trigger("click");
 updateJobCount();
});
</script>
<!--stylesheet to override wp_head and plugins scripts -->
<link rel="stylesheet" href="<?php bloginfo("stylesheet_url") ?>" type="text/css" media="screen" charset="utf-8"/>
<link rel="stylesheet" href="<?php
$scheme = get_option("9t5_scheme");
echo  get_template_directory_uri() . "/css/";
if (!empty($scheme)) {
 echo strtolower($scheme);
}
else
{
 echo "light";
} ?>.css" type="text/css" media="screen" charset="utf-8"/>
<!--[if IE]>
<link
 rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie8.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->
<!--[if IE 7]>
<link
 rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie7.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->
<!--[if lt IE 7]>
<link
 rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie6.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->
<style type="text/css" media="screen">
 body {
  background-color: #<?php echo get_option("9t5_background_color"); ?>;
  background-image: url('<?php echo get_option("9t5_background_image"); ?>');
  background-position:<?php echo get_option("9t5_background_position"); ?>;
  background-repeat:<?php echo get_option("9t5_background_repeat"); ?>;
 }
 <?php if (get_option("9t5_typography") == "Serif") {
  ?>
 body, textarea, select, button, .button, a, .cmf-skinned-select {
  font-family: Georgia, Times, serif;
 }
  <?php } ?>

</style>
</head>
<body <?php body_class( $class ); ?>>
<div class="container">
 <div class="header">
  <div class="inner mod">
   <div class="logo">
    <a href="<?php echo home_url(); ?>"><img alt="<?php bloginfo("name") ?>" src="<?php echo get_option("9t5_logo"); ?>"/></a>
   </div>
   <div class="tagline">
    <p><?php bloginfo("description") ?></p>
   </div>

   <?php
   if (!is_user_logged_in()) {
    ?>
    <p class="status">
     <?php _e('To post a job,', '9to5') ?>
     <a
      href="<?php echo site_url() ?>/<?php echo get_option('9t5_link_login'); ?>"><strong><?php _e('login', '9to5') ?></strong></a>
     <?php _e('or', '9to5') ?>
     <a
      href="<?php echo site_url() ?>/<?php echo get_option('9t5_link_register'); ?>"><strong><?php _e('create an account', '9to5') ?></strong></a>
    </p>
    <?php
   }
   else
   {
    ?>

    <p class="status">
     <?php _e('Logged in as', '9to5') ?>  <a
     href="<?php echo site_url() ?>/wp-admin/profile.php"><strong><?php echo $user_login; ?></strong></a>
     |
     <?php
     if (current_user_can('administrator')) {
      ?><a href="<?php echo site_url() ?>/wp-admin/edit.php"><?php _e('Edit Listings', '9to5') ?></a>
      | <?php } ?>
     <a href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e('Logout', '9to5') ?></a>
    </p>

    <?php } ?>

   <?php if (get_option("9t5_categorybar") == "Enabled") {
   ?>
   <div class="category_menu <?php echo get_option('9t5_color_categories'); ?>">
    <ul>
     <li class="cat-item-all"><a href="<?php echo home_url() ?>"><?php _e('All Jobs', '9to5') ?></a></li>
     <?php
     if (is_category()) {
      $cat = get_the_category();
      $current_cat_ID = $cat[0]->cat_ID;
     }
     $categories = get_categories(array('orderby' => 'name', 'order' => 'ASC'));
     if (has_nav_menu('job_categories')) {
      $nav = $Magnet->removeUL(wp_nav_menu(array('theme_location' => 'job_categories', 'container' => 'false', 'echo' => false)));
      echo $nav;
     } else {
      foreach ($categories as $category) {
       echo '<li class="cat-item-' . $category->cat_ID . ' ';
       echo ($current_cat_ID == $category->cat_ID) ? 'current-cat' : '';
       echo '"><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
      }
     }
     ?>

     <li class="rss"><a href="<?php echo get_option("9t5_custom_rss") ?>"><img src="<?php echo get_template_directory_uri() ?>/images/icon.rss.png"/></a></li>
    </ul>
   </div>
   <?php } ?>

   <?php if (is_home()) {
   ?>
   <script type="text/javascript" charset="utf-8">
    jQuery('.category_menu li:first-child').addClass('current-cat');
   </script>
   <?php } ?>
  </div>
 </div>
 <div class="content mod">
  <div class="inner">
