<?php

include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');
global $wpdb;
$dbtags = array_unique($wpdb->get_col("SELECT name FROM $wpdb->terms"));
$dbtitles = array_unique($wpdb->get_col("SELECT post_title FROM $wpdb->posts"));
$dbcontent = array_unique($wpdb->get_col("SELECT post_content FROM $wpdb->posts"));
$dblocations = array_unique($wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'location'"));

$uniqueTags = array_merge($dbtags, $dbtitles, $dbcontent, $dblocations);
$uniqueTags1 = array_unique($uniqueTags);

$tags1 = implode(" ", $uniqueTags1);
$tags2 = strtolower($tags1);
$tags3 = explode(" ", $tags2);
$tags4 = array_unique($tags3);

$final1 = implode(" ", $tags4);
$final2 = str_replace("\t", " ", $final1);
$final3 = str_replace("\n", " ", $final2);
$final4 = str_replace("\r", " ", $final3);
$final5 = str_replace('"', "", $final4);
$final6 = str_replace("'", "", $final5);
$final7 = str_replace(",", "", $final6);
$final8 = str_replace(".", "", $final7);
$final9 = strip_tags($final8);
$final10 = explode(" ", $final9);
$final10 = array_unique($final10);

$q = strtolower($_GET["q"]);
if (!$q) return;

foreach ($final10 as $key) {
 if (strpos(strtolower($key), $q) !== false) {
  echo "$key\n";
 }
}

?>