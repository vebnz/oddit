<?php
// register an action (can be any suitable action)
add_action('admin_init', 'on_admin_init');

function on_admin_init()
{
    // include the library
    include_once('envato-wordpress-toolkit-library/class-envato-wordpress-theme-upgrader.php');
    
    $upgrader = new Envato_WordPress_Theme_Upgrader( 'Pixel_Press', 'ikqsmnmee9i0gfradcy88pt8l824zabm' );
    
    /*
     *  Uncomment to check if the current theme has been updated
     */
    
    // $upgrader->check_for_theme_update(); 

    /*
     *  Uncomment to update the current theme
     */
    
    // $upgrader->upgrade_theme();
}


get_template_part('/magnetco/magnetCo');
$Magnet = new Magnet;

register_nav_menus(array(
 'footer' => __('Footer', 'ninetofive'),
));

add_theme_support("post-thumbnails");
add_theme_support("menus");

if (function_exists('register_nav_menu')) {
 register_nav_menu('job_categories', 'Job Categories');
}

if (function_exists('register_sidebar')) {
 register_sidebar(array(
	'before_widget' => '<li class="widget">',
	'after_widget' => '</li>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
 ));
}

if (get_option("9t5_wp_login") != "") {

 function my_custom_login_logo()
 {
	echo '<style type="text/css">h1 a { background-image:url(' . get_option("9t5_wp_login") . ') !important; } </style>';
 }

 add_action('login_head', 'my_custom_login_logo');
}

register_post_type('blog', array(
		'label' => __('Blog Posts', '9to5'),
		'singular_label' => __('Blog Post', '9to5'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'rewrite' => array(
		'slug' => 'blog',
		'with_front' => FALSE,
		),
		'query_var' => false,
		'supports' => array('title', 'editor', 'author', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions'),
		'menu_position' => 5
));

	nine2five_blog_news();


function nine2five_blog_news(){
	global $wpdb;
	$nine2five_sql= $wpdb->query( 
		"
		UPDATE $wpdb->posts 
		SET post_type = 'blog'
		WHERE post_type = 'news'
		"
	);
}

function mytheme_add_init()
{
 $file_dir = get_template_directory_uri();
 wp_enqueue_style("functions", $file_dir . "/functions.css", false, "1.0", "all");
}

/* * *****************
 * Load localization
 * **************** */
load_theme_textdomain('9to5', get_template_directory() . '/languages');
$locale = get_locale();
$locale_file = get_template_directory() . "/languages/$locale.php";
if (is_readable($locale_file)) require_once($locale_file);


/* * ************************************************
* Redirection of standart wordpress auth functions
* *********************************************** */

function possibly_redirect()
{
 global $pagenow;
 if ('wp-login.php' == $pagenow && $_REQUEST['action'] != 'logout') {
	if ($_REQUEST['action'] == 'lostpassword') {
	 wp_redirect(site_url() . '/lostpassword/');
	 exit();
	}
	elseif ($_REQUEST['action'] == 'rp')
	{
	 update_option('pass_change', 'true');
	}
 }
}


/**
 * Validation of auth-provided data
 *
 * @global array $auth_errors Output validation errors
 * @param string $type Action type
 * @param array $parameters Validation parameters
 * @return bool Validation status
 */
function auth_validation($type, $parameters)
{
 global $auth_errors;

 if ($type == 'login') {
	$counter = 0;
	if (empty($parameters['username']) || empty($parameters['password'])) {
	 $auth_errors[] = __('Fields "Username" and "Password" are required.', '9to5');
	 $counter++;
	}
	if (!preg_match("/^[-a-zA-Z0-9]+$/", $parameters['username'])) {
	 $auth_errors[] = __('"Username" may contain only A-Z,a-z,0-9.', '9to5');
	 $counter++;
	}
	if ($counter != 0)
	 return FALSE;
	return TRUE;
 }
 elseif ($type == 'register')
 {
	$counter = 0;

	require_once(ABSPATH . WPINC . '/registration.php');

	if (empty($parameters['username']) || empty($parameters['password']) || empty($parameters['email'])) {
	 $auth_errors[] = __('Fields "Username", "Password" and "Email" are required.', '9to5');
	 $counter++;
	}
	if (!preg_match("/^[-a-zA-Z0-9]+$/", $parameters['username'])) {
	 $auth_errors[] = __('"Username" may contain only A-Z,a-z,0-9.', '9to5');
	 $counter++;
	}
	if (!is_email($parameters['email'])) {
	 $auth_errors[] = __('Invalid email format.', '9to5');
	 $counter++;
	}
	if (username_exists($parameters['username'])) {
	 $auth_errors[] = __('This username already taken.', '9to5');
	 $counter++;
	}
	if (email_exists($parameters['email'])) {
	 $auth_errors[] = __('This email already registered.', '9to5');
	 $counter++;
	}
	if ($counter != 0)
	 return FALSE;
	return TRUE;
 }
}

/* * ***********
 * Job posting
 * ********** */
if ($_REQUEST['action'] == 'postajob') {
 global $current_user;
 get_currentuserinfo();

 // Recive POST data
 $positionType = $_POST['position_type_value'];
 $jobCategory = $_POST['category'];
 $jobTitle = format_to_post($_POST['post_title']);
 $jobDescription = format_to_post($_POST['content']);
 $companyName = format_to_post($_POST['company_name_value']);
 $jobLocation = format_to_post($_POST['location_value']);
 if (get_option('9t5_job_salary') == 'Yes') {
	if ($_POST['salary'] != '' && $_POST['salary'] != __('e.g. $1500 a month or 25 USD per hour', '9to5')) {
	 $jobSalary = format_to_post($_POST['salary']);
	}
	if ($_POST['salary'] != '') {
	 $salaryDescription = format_to_post($_POST['salary_description']);
	}
 }

 $jobLocation = format_to_post($_POST['location_value']);
 $googleMap = $_POST['map'];
 $jobTags = $_POST['post_tags'];
 if (isset($_POST['noexpire'])) {
	$expireDate = 'noexpire';
 }
 else
 {
	$expireDate = $_POST['expdate'];
 }
 $howToApply = format_to_post($_POST['how_to_apply_value']);
 $enableApply = $_POST['enableApply'];
 $applicationEmail = $_POST['application_email'];
 $applicationEmailMessage = format_to_post($_POST['application_email_message']);
 $maxSkills = get_option("9t5_max_skills") + 1;
 $jobSkills = array();
 $counter = 1;
 while ($maxSkills > $counter)
 {
	$selector = 'skills_value_' . $counter;
	if (!empty($_POST[$selector])) {
	 $jobSkills[] = $_POST[$selector];
	}
	$counter++;
 }
 $jobSkills = implode(',', $jobSkills);
 if ($enableApply == 'enableApply') {
	$enableApply = 'yes';
 }
 if (isset($_POST['anon'])) {
	$companyName = __('Anonymous', '9to5');
 }
 if (isset($_POST['anywhere'])) {
	$jobLocation = __('Anywhere', '9to5');
 }
 $ps = 'draft';
 if (current_user_can('edit_others_posts')) {
	$ps = 'publish';
 } else {
	$ps = 'draft';
 }
 // Creation of post object
 $my_post = array(
	'post_title' => $jobTitle,
	'post_content' => $jobDescription,
	'post_status' => $ps,
	'post_author' => $current_user->ID,
	'post_category' => array($jobCategory),
	'tags_input' => $jobTags
 );
 $post_id = wp_insert_post($my_post);

 // Add post meta data
 update_post_meta($post_id, 'position_type_value', $positionType);
 update_post_meta($post_id, 'company_name_value', $companyName);
 update_post_meta($post_id, 'enableapply_value', $enableApply);
 update_post_meta($post_id, 'exp_value', $expireDate);
 update_post_meta($post_id, 'how_to_apply_value', $howToApply);
 update_post_meta($post_id, 'location_value', $jobLocation);
 update_post_meta($post_id, 'position_type_value', $positionType);
 update_post_meta($post_id, 'skills_value', $jobSkills);
 update_post_meta($post_id, 'map_value', $googleMap);
 update_post_meta($post_id, 'email_value', $applicationEmail);
 update_post_meta($post_id, 'email_message_value', $applicationEmailMessage);
 if ($jobSalary) update_post_meta($post_id, 'salary_value', $jobSalary);
 if ($salaryDescription) update_post_meta($post_id, 'salary_description_value', $salaryDescription);

 /******************************************************************************/
 /********************* Here mark as used the credit in the db *****************/
 /******************************************************************************/
 global $wpdb, $current_user;
 $table_name = $wpdb->prefix . 'transactions';
 ini_set('log_errors', true);
 ini_set('error_log', TEMPLATEPATH . '/ipn_errors.log');
 $have_credits = false;

 $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 if (mysqli_connect_errno()) {
	error_log("Connection error, error: " . mysqli_connect_errno());
 } else {
	if ($stmt = $mysqli->prepare("UPDATE $table_name SET credit_used=1 WHERE txn_id=?")) {
	 $txn_id = $_POST['txn_id'];
	 $stmt->bind_param("i", $txn_id);
	 $stmt->execute();
	}
 }
 $mysqli->close();
 /************************ End  paypal process *****************************/


 $expire = 60 * 60 * 24 * 60 + time();
 setcookie('activeToken', "false", $expire, '/');

 // Redirect to added job
 $post_url = get_permalink($post_id);
 if ($ps != 'draft'):
	wp_redirect($post_url);
 else:
	setcookie("draftMSG", 'yes', time() + 120);
	wp_redirect($Magnet->post_a_job_url);
 endif;
 exit();
}

/**
 * Reload of a standart wordpress auth functions
 */
if ('registration' == $_REQUEST['action']) {
 global $auth_errors;

 $username = trim($_REQUEST['regusername']);
 $password = trim($_REQUEST['regpassword']);
 $email = trim($_REQUEST['regemail']);
 $FirstName = addslashes(trim($_REQUEST['FirstName']));
 $LastName = addslashes(trim($_REQUEST['LastName']));
 $parameters['username'] = $username;
 $parameters['password'] = $password;
 $parameters['email'] = $email;

 if (auth_validation('register', $parameters)) {
	/*
	* Creation of a new user
	*/
	require_once(ABSPATH . WPINC . '/registration.php');
	wp_create_user($username, $password, $email);
	$usermeta = get_userdatabylogin($username);
	$userID = $usermeta->ID;
	update_user_meta($userID, 'first_name', $FirstName);
	update_user_meta($userID, 'last_name', $LastName);
	wp_signon(array('user_login' => $username, 'user_password' => $password, 'remember' => 'true'), false);
	wp_redirect($Magnet->post_a_job_url);
	exit();
 }
}
elseif ('login' == $_REQUEST['action'])
{

 global $auth_errors;

 $username = trim($_REQUEST['username']);
 $password = trim($_REQUEST['password']);

 $parameters['username'] = $username;
 $parameters['password'] = $password;

 if (auth_validation('login', $parameters)) {
	/*
	* User sign in
	*/
	if (is_wp_error(wp_signon(array('user_login' => $username, 'user_password' => $password, 'remember' => 'true'), false))) {
	 $auth_errors[] = __('Invalid username or password.', '9to5');
	}
	else
	{
	 wp_redirect($Magnet->post_a_job_url);
	 exit();
	}
 }
}
elseif ('lost' == $_REQUEST['action'])
{
 /**
	* Restoring user password
	*/
 global $auth_errors;
 global $auth_messages;

 $counter = 0;

 if (empty($_REQUEST['ident'])) {
	$auth_errors[] = __('Please, specify your username or email.', '9to5');
	$counter++;
 }
 if (strpos($_REQUEST['ident'], '@')) {
	$user_data = get_user_by_email(trim($_REQUEST['ident']));
	if (empty($user_data) && $counter == 0) {
	 $auth_errors[] = __('User with such email not found.', '9to5');
	 $counter++;
	}
 }
 else
 {
	$login = trim($_REQUEST['ident']);
	$user_data = get_userdatabylogin($login);
 }

 do_action('lostpassword_post');

 if (!$user_data && $counter == 0) {
	$auth_errors[] = __('User with such username not found.', '9to5');
	$counter++;
 }

 // redefining user_login ensures we return the right case in the email
 $user_login = $user_data->user_login;
 $user_email = $user_data->user_email;

 do_action('retreive_password', $user_login); // Misspelled and deprecated
 do_action('retrieve_password', $user_login);

 $allow = apply_filters('allow_password_reset', true, $user_data->ID);

 if (!$allow && $counter == 0) {
	$auth_errors[] = __('User with such username not found.', '9to5');
	$counter++;
 }
 if ($counter == 0) {
	$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

	if (empty($key)) {
	 // Generate something random for a key...
	 $key = wp_generate_password(20, false);
	 do_action('retrieve_password_key', $user_login, $key);
	 // Now insert the new md5 key into the db
	 $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
	}
	$message = __('Someone has asked to reset the password for the following site and username.', '9to5') . "\r\n\r\n";
	$message .= network_site_url() . "\r\n\r\n";
	$message .= sprintf(__('Username: %s', '9to5'), $user_login) . "\r\n\r\n";
	$message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.', '9to5') . "\r\n\r\n";
	$message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf(__('[%s] Password Reset', '9to5'), $blogname);

	$title = apply_filters('retrieve_password_title', $title);
	$message = apply_filters('retrieve_password_message', $message, $key);

	if ($message && !wp_mail($user_email, $title, $message))
	 wp_die(__('The email could not be sent.', '9to5') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...', '9to5'));

	$auth_messages[] = __('Please, check your email.', '9to5');
 }
}

/* * *************
 * Theme options
 * ************ */
$themename = "Nine to Five";
$shortname = "9t5";

/**
 * Load options description
 */
if (isset($_GET['page']) == basename(__FILE__)) {
 include('scripts/themeoptions.php');
}

/**
 * If theme is just installed - register settings
 */
if (get_option('9t5_version') != '4') {
 include('scripts/initconfig.php');
}

/* * **********
 * Admin page
 * ********* */

function mytheme_add_admin()
{
 global $themename, $shortname, $options;
 add_menu_page($themename, 'Theme Settings', 'administrator', basename(__FILE__), 'mytheme_admin');
}

if (isset($_GET['page']) == basename(__FILE__)) {
 include('scripts/adminfunctions.php');
 include('scripts/adminpage.php');
}

add_action('admin_init', 'mytheme_add_init');
add_action('admin_menu', 'mytheme_add_admin');

add_action('admin_notices', 'upload_dir_warning');
function upload_dir_warning()
{
 $tplpath = TEMPLATEPATH . '/scripts/uploads/';
 if (!is_writable($tplpath)) {
	echo '<div id="uploads-warn" class="error fade">
						<p><b>Warning!</b> ' . sprintf(__("The %s is not writeable", '9to5'), $tplpath) . '</p>
				</div>';
 }
}

/* * *****************
 * Custom meta boxes
 * **************** */
if (is_admin()) {
 include('scripts/metaboxes.php');
}

// Block WP-Admin Access for Candidates and Employers

if (!function_exists('nine2five_wp_admin_access_init')) {

 function nine2five_wp_admin_access_init()
 {

	if (
	 // Look for the presence of /wp-admin/ in the url
	 stripos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false
	 &&
	 // Allow calls to async-upload.php
	 stripos($_SERVER['REQUEST_URI'], 'async-upload.php') == false
	 &&
	 // Allow calls to admin-ajax.php
	 stripos($_SERVER['REQUEST_URI'], 'admin-ajax.php') == false
	) {

	 if (get_option("9t5_blockadmin_enabled") == 'true') {
		// Does the current user fail the required capability level?
		if (!current_user_can('add_users')) {
		 wp_redirect(get_option('siteurl'), 302);
		}
	 }

	}

 }

}

add_action('init', 'nine2five_wp_admin_access_init', 0);

add_filter('show_admin_bar', '__return_false');

function hide_admin_bar_prefs()
{
 if (!is_admin()) {
	echo '<style type="text/css">   .show-admin-bar { display: none; }     </style>';
 }
}
add_action('admin_print_scripts-profile.php', 'hide_admin_bar_prefs');


// remove some WP defaults
function removeHeadLinks() {
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'noindex', 1);
}
add_action('init', 'removeHeadLinks');

function nine2five_no_generator() {
	return '';
}
add_filter('the_generator', 'nine2five_no_generator');

function nine2five_admin_scripts(){
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_deregister_script('l10n');
	}
}
add_action('init', 'nine2five_admin_scripts');

function nine2five_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}

add_action('wp_head', 'nine2five_remove_recent_comments_style', 1);

function nine2five_gallery_style($css) {
	return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}

add_filter('gallery_style', 'nine2five_gallery_style');

function change_post_menu_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'Jobs';
	$submenu['edit.php'][5][0] = 'Jobs';
	$submenu['edit.php'][10][0] = 'Add Jobs';
	$submenu['edit.php'][16][0] = 'Jobs Tags';
	echo '';
}
function change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
				
	$labels->name = 'Jobs';
	$labels->singular_name = 'Jobs';
	$labels->add_new = 'Add Jobs';
	$labels->add_new_item = 'Add Jobs';
	$labels->edit_item = 'Edit Jobs';
	$labels->new_item = 'Jobs';
	$labels->view_item = 'View Jobs';
	$labels->search_items = 'Search Jobs';
	$labels->not_found = 'No Jobs found';
	$labels->not_found_in_trash = 'No Jobs found in Trash';
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );

?>