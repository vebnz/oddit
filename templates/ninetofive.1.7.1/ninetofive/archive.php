<?php
get_header();
$pageNumber = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>
<div class="focus">

<div class="whiteboard">

<div class="filter <?php
if ( get_option( "9t5_filterbar" ) == "Checkboxes" ) {
?>alt<?php
}
?> <?php
echo get_option( '9t5_color_primary' );
?>">
 <div class="push">
  <ul>
   <li class="first"><a href="#" id="all" class="first <?php
if ( $_SESSION[ 'active' ] == "all" ) {
    echo "active";
}
?>"><span><?php
_e( 'All Types', '9to5' );
?></span></a></li>
   <li><a href="#filter_1" id="filter_1" class="<?php
if ( $_SESSION[ 'active' ] == "filter_1" ) {
    echo "active";
}
?>"><span><?php echo get_option('filter_1') ?></span></a></li>
   <li><a href="#filter_2" id="filter_2" class="<?php
if ( $_SESSION[ 'active' ] == "filter_2" ) {
    echo "active";
}
?>"><span><?php echo get_option( 'filter_2' );?></span></a></li>
   <?php
if ( get_option( 'filter_3' ) != '' ) {
?>
   <li><a href="#filter_3" id="filter_3" class="<?php
    if ( $_SESSION[ 'active' ] == "filter_3" ) {
        echo "active";
    }
?>"><span><?php echo get_option( 'filter_3' );?></span></a></li>
   <?php
}
?>
   <li class="search">
    <?php
get_search_form();
?>
   </li>
  </ul>
 </div>
</div>

<div class="sub <?php
echo get_option( '9t5_color_secondary' );
?>">
 <h2>
  <?php
$post = $posts[ 0 ]; // Hack. Set $post so that the_date() works. 
?>
  <?php
/* If this is a category archive */
if ( is_category() ) {
?>
  <strong><?php
    single_cat_title();
?></strong> <?php
    _e( 'Jobs', '9to5' );
?>
  <?php
    $catName = get_query_var( 'cat' );
    $cat     = get_category( $catName );
    
    $count    = $cat->count;
    $name     = $cat->name;
    $slug     = $cat->slug;
    $taxonomy = "category";
?>
  <?php
    /* If this is a tag archive */
} elseif ( is_tag() ) {
?>
  <?php
    _e( 'Jobs tagged', '9to5' );
?> <strong><?php
    single_tag_title();
?></strong>
  <?php
    $count = $tags[ $i ]->count;
    
    $i     = 0;
    $found = false;
    
    
    $tagVars = get_query_var( 'tag' );
    
    $tags = get_term_by( 'slug', $tagVars, 'post_tag' );
    
    $count    = $tags->count;
    $name     = $tags->name;
    $slug     = $tags->slug;
    $taxonomy = "tag";
?>
  <?php
    /* If this is a daily archive */
} elseif ( is_day() ) {
?>
  <strong><?php
    the_time( 'F jS, Y' );
?></strong>
  <?php
    /* If this is a monthly archive */
} elseif ( is_month() ) {
?>
  <strong><?php
    the_time( 'F, Y' );
?></strong>
  <?php
    /* If this is a yearly archive */
} elseif ( is_year() ) {
?>
  <strong><?php
    the_time( 'Y' );
?></strong>
  <?php
    /* If this is a paged archive */
} elseif ( isset( $_GET[ 'paged' ] ) && !empty( $_GET[ 'paged' ] ) ) {
?>
  <?php
    _e( 'Archives', '9to5' );
?>
  <?php
}
?>
 </h2>
</div>

<ol class="list">

 <?php
$date       = date( "dmy" );
$yesterday  = date( "dmy", strtotime( "-1 day" ) );
$categories = get_categories( "hide_empty=0" );
$exclude    = "";
query_posts( "exclude=$exclude&" . $query_string );
$counter = 0;

if ( $paged == 0 ) {
    $offset = 0;
} else {
    $offset = $paged * 10;
}


if ( have_posts() ):
    while ( have_posts() ):
        the_post();
?>

  <li class="<?php
        echo $cat->category_nicename;
        if ( $_SESSION[ $cat->category_nicename ] == "false" ) {
            echo " disabled ";
        }
?> <?php
        echo strtolower( get_post_meta( $post->ID, 'position_type_value', true ) );
?>">
   <div class="details">
    <?php
        $post_time = get_the_time( "dmy" );
        if ( $post_time == $date ) {
?><em class="new <?php
            echo get_option( '9t5_color_secondary' );
?>"><?php
            _e( 'New', '9to5' );
?></em><?php
        }
?>
    <h3><a href="<?php
        the_permalink();
?>">
     <?php
        the_title();
?>
    </a></h3>

    <div class="company">
     <p><?php
        if ( !get_post_meta( $post->ID, 'company_name_value', true ) ) {
?><?php
            _e( 'Anonymous', '9to5' );
?><?php
        } else {
            echo get_post_meta( $post->ID, 'company_name_value', true );
        }
?></p>
    </div>
   </div>
   <div class="meta">
    <dl>
     <dt>
      <?php
        if ( !get_post_meta( $post->ID, 'location_value', true ) ) {
?>Anywhere<?php
        } else {
            echo get_post_meta( $post->ID, 'location_value', true );
        }
?>
     </dt>
     <dd><?php
        echo get_option( get_post_meta( $post->ID, 'position_type_value', true ) );
?></dd>
    </dl>
   </div>
   <div class="datestamp">
    <p><?php
        if ( $post_time == $date ) {
?><?php
            _e( 'Today', '9to5' );
?><?php
        } else {
            echo the_time( "M d" );
        }
?></p>
   </div>
  </li>

  <?php
    endwhile;
endif;
?>
</ol>
<div class="paging">
 <p class="count"><strong><?php
echo $count;
?></strong> <?php
_e( 'jobs found', '9to5' );
?></p>
 <?php

function pagination( $query, $baseURL )
{
    global $pageNumber;
    $page = $pageNumber;
    if ( !$page )
        $page = 1;
    $ppqs     = $_SERVER[ "QUERY_STRING" ] ? "&" . $_SERVER[ "QUERY_STRING" ] : "";
    $pp_pagd  = "/&paged\=\d*[0-9](\d*[0-9])?/";
    $pp_blank = "";
    $qs       = preg_replace( $pp_pagd, $pp_blank, $ppqs );
    // Only necessary if there's more posts than posts-per-page
    if ( $query->found_posts > $query->query_vars[ "posts_per_page" ] ) {
        echo '<ul class="' . get_option( '9t5_color_secondary' ) . '">';
        // Previous link?
        if ( $page > 1 ) {
            echo '<li class="previous"><a href="' . $baseURL . '?paged=' . ( $page - 1 ) . $qs . '"><span>&lsaquo; ' . __( 'Prev', '9to5' ) . '</span></a></li>';
        }
        // Loop through pages
        for ( $i = 1; $i <= $query->max_num_pages; $i++ ) {
            // Current page or linked page?
            if ( $i == $page ) {
                echo '<li class="active"><a href="' . $baseURL . '?paged=' . $i . $qs . '"><span>' . $i . '</span></a></li>';
            } else {
                echo '<li><a href="' . $baseURL . '?paged=' . $i . $qs . '"><span>' . $i . '</span></a></li>';
            }
        }
        // Next link?
        if ( $page < $query->max_num_pages ) {
            echo '<li class="next"><a href="' . $baseURL . '?paged=' . ( $page + 1 ) . $qs . '"><span>' . __( 'Next', '9to5' ) . ' &rsaquo;</span></a></li>';
        }
        echo '</ul>';
    }
}

pagination( $wp_query, home_url() . "/" );
?>
</div>
</div>

</div>


<?php
get_sidebar();
get_footer();
?>