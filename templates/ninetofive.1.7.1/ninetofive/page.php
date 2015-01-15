<?php get_header(); ?>

<div class="focus">
 <div class="listing whiteboard">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <div class="title <?php echo get_option('9t5_color_tertiary'); ?>">
   <h1><?php the_title() ?></h1>
  </div>
  <div class="description cms">
   <?php the_content(); ?>
  </div>
  <?php endwhile; endif; ?>
  <div class="cap"></div>
 </div>
</div>
<?php get_sidebar();
get_footer(); ?>