<?php get_header(); ?>

<div class="focus">

 <div class="message white">
  <div class="push">
   <h3><?php _e('Page not found', '9to5') ?></h3>

   <p><?php _e('The page you are looking for no longer exists. Try checking the URL you typed for spelling errors and if not header over to ', '9to5'); ?> <a href='<?php echo home_url(); ?>'> <?php _e(' the homepage', '9to5'); ?></a><?php _e(' to search what you were looking for.', '9to5') ?></p>
  </div>
 </div>

</div>

<?php get_sidebar();
get_footer(); ?>