<?php 
/* Template Name: Blog */
get_header();?>

<div class="focus">

 <div  class="blog whiteboard">
  <div class="title <?php echo get_option('9t5_color_tertiary'); ?>">
   <h1>
    <?php the_title() ?>
   </h1>
  </div>

  <ul class="blog_posts">
   <?php
   if ($paged == 0) {
    $offset = 0;
   }
   else
   {
    $offset = ($paged - 1) * 10;
   }
   ?>
   <?php
   query_posts("posts_per_page=10&offset=$offset&post_type=blog");
   if (have_posts()) : while (have_posts()) : the_post();
    ?>
    <li class="post" id="<?php the_ID(); ?>" <?php post_class(); ?>>
     <div class="thumbnail"><?php echo get_post_meta($post->ID, 'Thumbnail', true); ?></div>
     <div class="summary">
      <h3><a href="<?php the_permalink() ?>" class="highlight"><?php the_title() ?></a></h3>
      <?php the_excerpt(); ?>
     </div>
     <div class="datestamp">
      <?php $date = date('l jS F Y');
      $post_time = get_the_time("l jS F Y"); ?>
      <p><?php if ($post_time == $date) {
       ?>Today<?php
      }
      else
      {
       echo the_time("M d");
      } ?></p>
     </div>
    </li>
    <?php endwhile;
   endif; ?>
  </ul>
  <div class="paging">
   <?php

   function pagination($query, $baseURL)
   {
    global $paged;
    $page = $paged;
    if (!$page)
     $page = 1;
    $qs = $_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "";
    // Only necessary if there's more posts than posts-per-page
    if ($query->found_posts > $query->query_vars["posts_per_page"]) {
     echo '<ul class="' . get_option('9t5_color_secondary') . '">';
     // Previous link?
     if ($page > 1) {
      echo '<li class="previous"><a href="' . get_pagenum_link($page - 1) . '"><span>&lsaquo; ' . __('Prev', '9to5') . '</span></a></li>';
     }
     // Loop through pages
     for ($i = 1; $i <= $query->max_num_pages; $i++)
     {
      // Current page or linked page?
      if ($i == $page) {
       echo '<li class="active"><a href="' . get_pagenum_link($i) . '"><span>' . $i . '</span></a></li>';
      }
      else
      {
       echo '<li><a href="' . get_pagenum_link($i) . '"><span>' . $i . '</span></a></li>';
      }
     }
     // Next link?
     if ($page < $query->max_num_pages) {
      echo '<li class="next"><a href="' . get_pagenum_link($page + 1) . '"><span>' . __('Next', '9to5') . ' &rsaquo;</span></a></li>';
     }
     echo '</ul>';
    }
   }

   pagination($wp_query, home_url() . "/blog/")
   ?>
  </div>
 </div>

</div>

<?php get_sidebar();
get_footer(); ?>