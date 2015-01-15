<?php get_header(); ?>

<div class="focus">

 <div class="listing whiteboard">

  <?php $date = date("dmy");
  $yesterday = date("dmy", strtotime("-1 day")); ?>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <div class="title mod <?php echo get_option('9t5_color_tertiary'); ?>">
   <h1>
    <?php the_title() ?>
   </h1>
   <?php if (get_option('9t5_share_blog') == "Enabled") {
   ?>
   <div class="share">
    <a href="http://twitter.com/home?status=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/images/icon-twitter.gif"/></a>
    <a href="http://www.facebook.com/sharer.php?u=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/images/icon-fb.gif"/></a>
    <a href="http://del.icio.us/post/%3furl=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/images/icon-delicious.gif"/></a>
    <a href="http://digg.com/submit?url=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/images/icon-digg.gif"/></a>
    <a href="http://www.addtoany.com/add_to/instapaper?linkurl=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/images/icon-instapaper.gif"/></a>
   </div>
   <?php } ?>
  </div>


  <div class="datestamp">
   <p>
    <?php _e('Posted:', '9to5'); ?> <strong><?php
    if ($post_time == $date) {
     _e('Today', '9to5');
    }
    else
    {
     echo the_time("M d");
    }
    ?></strong>
   </p>
  </div>

  <div class="description cms">
   <?php the_content(); ?>
  </div>

  <?php endwhile;
 endif; ?>

  <?php comments_template(); ?>

 </div>
</div>

<?php get_sidebar();
get_footer(); ?>
