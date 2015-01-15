<?php get_header(); ?>


<div class="focus">

    <div class="results whiteboard">
        <?php
        if (get_option("9t5_filterbar") == "Checkboxes")
        {
        ?>
            <div class="filter alt <?php echo get_option('9t5_color_primary'); ?>">
                <div class="push">
                    <ul>
                        <li class="first"><a href="#" id="all" class="first <?php
            if ($_SESSION['active'] == "all")
            {
                echo "active";
            }
        ?>"><span><?php _e('All Types', '9to5') ?></span></a></li>
                    <li><a href="#filter_1" id="filter_1" class="<?php if ($_SESSION['active'] == "filter_1") { echo "active"; } ?>"><span><?php echo  get_option('filter_1') ?></span></a></li>
                    <li><a href="#filter_2" id="filter_2" class="<?php if ($_SESSION['active'] == "filter_2") { echo "active"; } ?>"><span><?php echo  get_option('filter_2') ?></span></a></li>
<?php if (get_option('filter_3') != '') { ?>
                    <li><a href="#filter_3" id="filter_3" class="<?php if ($_SESSION['active'] == "filter_3") { echo "active"; } ?>"><span><?php echo  get_option('filter_3') ?></span></a></li><?php } ?>
                    <li class="search"><?php get_search_form(); ?></li>
                 </ul>
             </div>
         </div>
<?php }
    else
    {
?>
         <div class="filter <?php echo get_option('9t5_color_primary'); ?>">
             <div class="push">
                 <ul>
                    <li class="first"><a href="#" id="all" class="first <?php if ($_SESSION['active'] == "all") { echo "active"; } ?>"><span><?php _e('All Types', '9to5') ?></span></a></li>
                    <li><a href="#filter_1" id="filter_1" class="<?php if ($_SESSION['active'] == "filter_1") { echo "active"; } ?>"><span><?php echo  get_option('filter_1') ?></span></a></li>
                    <li><a href="#filter_2" id="filter_2" class="<?php if ($_SESSION['active'] == "filter_2") {  echo "active"; } ?>"><span><?php echo  get_option('filter_2') ?></span></a></li>
<?php
    if (get_option('filter_3') != '')
    {
?>
                    <li><a href="#filter_3" id="filter_3" class="<?php if ($_SESSION['active'] == "filter_3") {  echo "active"; } ?>"><span><?php echo  get_option('filter_3') ?></span></a></li><?php } ?>
                    <li class="search"> <?php get_search_form(); ?> </li>
                </ul>
            </div>
        </div>
<?php } ?>
                <ol class="list">
<?php
     $date = date("dmy");
     $yesterday = date("dmy", strtotime("-1 day"));
     $categories = get_categories("hide_empty=0");
     $exclude = "";
     query_posts("exclude=$exclude&" . $query_string);
     $counter = 0;
     $expired = 0;
     if (get_option("9t5_new_days") == ""){
         $days = 1;
     } else {
         $days = get_option("9t5_new_days");
     }
     $newtagdisplay = date("m/d/y", time() - ($days * 86400));

     if ($paged == 0) {
         $offset = 0;
     } else {
         $offset = ($paged - 1) * 10;
     }

     if (have_posts ()) : while (have_posts ()) : the_post();
         if (
                    strtotime(stripslashes(get_post_meta($post->ID, 'exp_value', true))) >= strtotime(date("Ymd")) 
                ||  stripslashes(get_post_meta($post->ID, 'exp_value', true)) == "noexpire" 
                ||  stripslashes(get_post_meta($post->ID, 'exp_value', true)) == ""
            )
         {
?>
                     <li class="<?php $cat = get_the_category(); echo $cat[0]->category_nicename; if ($_SESSION[$cat[0]->category_nicename] == "false") { echo " disabled "; } ?> <?php echo strtolower(get_post_meta($post->ID, 'position_type_value', true)); ?>">
                     <div class="details">
                     <?php
                     $post_time = get_the_time("m/d/y");
                     if (strtotime($post_time) > strtotime($newtagdisplay))
                     {
                        ?>
                        <em class="new <?php echo get_option('9t5_color_secondary'); ?>"><?php _e('New', '9to5') ?></em>
                <?php } ?>
                     <h3><a href="<?php the_permalink() ?>">
<?php the_title() ?>
                 </a></h3>
                             <div class="company">
                                 <p><?php
                                     if (!get_post_meta($post->ID, 'company_name_value', true)) { ?>
                                     <?php _e('Anonymous', '9to5') ?><?php
                                     } else {
                                         echo get_post_meta($post->ID, 'company_name_value', true);
                                     }?></p>
                             </div>
                         </div>
                         <div class="meta">
                             <dl>
                                 <dt>
                <?php if (!get_post_meta($post->ID, 'location_value', true)) { ?>
                    <?php _e('Anywhere', '9to5') ?><?php }
                else {
                    echo get_post_meta($post->ID, 'location_value', true);
                }
                ?>
                                 </dt>
                                 <dd><?php echo get_option(get_post_meta($post->ID, 'position_type_value', true)); ?></dd>
                             </dl>
                         </div>
                         <div class="datestamp">
                         <p><?php if ($post_time == $date) { ?><?php _e('Today', '9to5') ?><?php } else { echo the_time("M d"); } ?></p>
                         </div>
                     </li>
<?php
$counter++;
} else {
$expired++;
} endwhile; endif;
?>
                </ol>
                 <div class="pagingShows">
                     <p class="count"><?php _e('Showing', '9to5') ?> <strong><?php echo $offset + 1 ?> - <?php echo $offset + $counter ?></strong> <?php _e('of', '9to5') ?> <strong><?php $all = wp_count_posts();
                     echo $all->publish; ?></strong>&nbsp;<?php _e('jobs', '9to5') ?></p>
                </div>
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
 if ($query->found_posts > $query->query_vars["posts_per_page"])
 {
     echo '<ul class="' . get_option('9t5_color_secondary') . '">';
     // Previous link?
     if ($page > 1){
         echo '<li class="previous"><a href="' .get_pagenum_link(($page-1)). '"><span>&lsaquo; '.__('Prev','9to5').'</span></a></li>';
     }
     // Loop through pages
     for ($i = 1; $i <= $query->max_num_pages; $i++){
         // Current page or linked page?
         if ($i == $page){
             echo '<li class="active"><a href="' . get_pagenum_link($i) .'"><span>' . $i . '</span></a></li>';
         }else{
             echo '<li><a href="' . get_pagenum_link($i) . '"><span>' . $i . '</span></a></li>';
         }
     }
     // Next link?
     if ($page < $query->max_num_pages){
         echo '<li class="next"><a href="' . get_pagenum_link(($page+1)) . '"><span>'.__('Next','9to5').' &rsaquo;</span></a></li>';
     }
     echo '</ul>';
 }
}

pagination($wp_query, home_url() . "/")
    ?>
         </div>
     </div>
 </div>
<?php get_sidebar(); get_footer(); ?>