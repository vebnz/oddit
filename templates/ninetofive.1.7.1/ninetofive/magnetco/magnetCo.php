<?php
class Magnet
{
 var $post_a_job_url = '';

 function Magnet()
 {
  $this->post_a_job_url = $this->post_a_job_url();
 }


 function clearExpiredPosts()
 {
  global $post;
  $all = get_posts("posts_per_page=500");

  foreach ($all as $post) :
   setup_postdata($post);
   $exp = get_post_meta($post->ID, "exp_value", true);
   if ($exp != 'noexpire') {
    $expired = str_replace("/", "", $exp);
    if ($expired < date("Ymd")) {
     wp_delete_post($post->ID);
    }
   }
  endforeach;
  wp_reset_query();
 }

 function post_a_job_url()
 {

  $post_a_job = get_option("9t5_job_post_page");

  if ($post_a_job != "") {
   $post_a_job = home_url() . '/' . $post_a_job;
   return $post_a_job;
  } else {
   return home_url();
  }

 }

 function removeUL($menuString)
 {
  $menu = preg_replace(array(
   '#^<ul[^>]*>#',
   '#</ul>$#'
  ), '', $menuString);
  return $menu;
 }

}

?>