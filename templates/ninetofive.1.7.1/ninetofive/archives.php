<?php get_header(); ?>

<div class="focus">

 <div class="listing whiteboard">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <div class="title">
   <h1>
    <?php $post_time = get_the_time("dmy");
    if ($post_time == $date) {
     ?><em class="new <?php echo get_option('9t5_color_secondary'); ?>"><?php _e('New', '9to5') ?></em><?php } ?>
    <?php the_title(); ?>
   </h1>
  </div>

  <div class="description cms">
   <?php the_content(); ?>

   <h3><?php _e('By Year', '9to5') ?></h3>
   <?php wp_get_archives("type=yearly"); ?>
   <h3><?php _e('By Month', '9to5') ?></h3>
   <?php wp_get_archives("type=monthly"); ?>
  </div>

  <?php endwhile;
 endif; ?>

  <div class="cap"></div>
 </div>

</div>

<?php get_sidebar();
get_footer(); ?>
