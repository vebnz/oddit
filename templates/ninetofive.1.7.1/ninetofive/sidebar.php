<?php global $Magnet; ?>
<div class="sidebar">
 <?php
 $widgets[0] = "";
 $widgets[1] = "";
 $widgets[2] = "";
 $widgets[3] = "";
 $widgets[4] = "";
 $widgets[5] = "";
 $widgets[6] = "";

 if (get_option("9t5_wid_search") == "Enabled") {
	if (get_option("9t5_search_all") == "Enabled") {
	 $widgets[1] = '
		 <li class="quicksearch">
			<form action="' . home_url() . '" method="get" role="search"><span><input type="text" name="s" id="search_box" value="' . __("Search by keyword", "9to5") . '" onfocus="if (this.value == \'' . __("Search by keyword", "9to5") . '\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'' . __("Search by keyword", "9to5") . '\';}" /><button name="submit" type="submit" class="icon icon_search"></button></span></form>
		 </li>
		';
	}
	else if (!is_home() && !is_search() && !is_archive()) {
	 $widgets[1] = '
		 <li class="quicksearch">
			<form action="' . home_url() . '" method="get" role="search"><span><input type="text" name="s" id="search_box" value="' . __("Search by keyword", "9to5") . '" onfocus="if (this.value == \'' . __("Search by keyword", "9to5") . '\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'' . __("Search by keyword", "9to5") . '\';}" /><button name="submit" type="submit" class="icon icon_search"></button></span></form>
		 </li>
		';
	}
 }

 if (get_option("9t5_categories") == "Enabled") {
	$categoryCount = get_option('9t5_category_number');
	if ($categoryCount == "")
	 $categoryCount = 15;
	$categories = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => $categoryCount));
	$categoriesArray = array();
	foreach ((array)$categories as $category)
	{
	 array_push($categoriesArray, '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '<span>' . $category->count . '</span></a></li>');
	}
	$widgets[2] = '
		<li><h2 class="widgettitle">' . __("Popular Categories", "9to5") . '</h2></li>
		<li><ul class="tablets">';

	function categories_function($v1, $v2)
	{
	 return $v1 . $v2;
	}

	$finalCategories = array_reduce($categoriesArray, "categories_function");
	$widgets[2] .= $finalCategories;
	$widgets[2] .= '</ul></li>';
 }

 if (get_option("9t5_tags") == "Enabled") {
	$tagCount = get_option('9t5_tag_number');
	if ($tagCount == "")
	 $tagCount = 15;
	$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => $tagCount));
	$tagsArray = array();
	foreach ((array)$tags as $tag)
	{
	 array_push($tagsArray, '<li><a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '<span>' . $tag->count . '</span></a></li>');
	}
	$widgets[3] = '
		<li><h2 class="widgettitle">' . __("Popular Tags", "9to5") . '</h2></li>
		<li><ul class="tablets">';

	function tags_function($v1, $v2)
	{
	 return $v1 . $v2;
	}

	$finalTags = array_reduce($tagsArray, "tags_function");
	$widgets[3] .= $finalTags;
	$widgets[3] .= '</ul></li>';
 }

 if (get_option("9t5_wid_add") == "Enabled") {
	$widgets[4] = '
		<li class="cell action">
			<p>' . get_option("9t5_caption_text") . '</p>
			<a href="' . $Magnet->post_a_job_url . '" class="button ' . get_option("9t5_color_button") . ' xlarge">
				<span>' . stripslashes(get_option("9t5_button_text")) . '</span>
		 	</a>
		</li>
	 ';
 }

 if (get_option("9t5_wid_about") == "Enabled") {
	$widgets[5] = '
		<li class="about">
		 <h2 class="widgettitle">' . __("About Us", "9to5") . '</h2>
		 ' . stripslashes(get_option("9t5_wid_about_text")) . '
		</li>
	 ';
 }


 if (get_option("9t5_ad_enabled") == "Enabled") {
	$widgets[6] = '
		<li class="advert">
		 <h4>' . __("Advertising", "9to5") . '</h4>
		 ' . stripcslashes(get_option("9t5_ad_code")) . '
		</li>
	 ';
 }


 $widgetOrder = explode(",", get_option("9t5_wid_order"));
 ?>
 <ul>
	<?php
	$count = count($widgetOrder);
	for ($id = 0; $id < $count; $id++){
	 echo $widgets[$widgetOrder[$id]];
	} ?>
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar()) {

 } ?>
 </ul>

</div>
