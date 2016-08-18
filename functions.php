<?php

/* This is the Function to remove features from the Category output */

function the_category_filter($thelist,$separator=' ') {
	if(!defined('WP_ADMIN')) {
		//list the category names to exclude
		$exclude = array('Features');
		$cats = explode($separator,$thelist);
		$newlist = "";
		foreach($cats as $cat) {
			$catname = trim(strip_tags($cat));
			if(!in_array($catname,$exclude))
				$newlist .= $cat.$separator;
		}
		$newlist = rtrim($newlist,$separator);
		return $newlist;
	} else
		return $thelist;
}
add_filter('the_category','the_category_filter',10,2);

/* Cookie information */

if (isset($_GET['deletecookie'])) setcookie('invitationhomes', '', time() - 3600);
//if (isset($_GET['setcookie'])) setcookie('invitationhomes', 'visited', strtotime("+6 months"), "/");

/*
function visitCookie() {
	if (isset($_GET['deletecookie'])) return;
	$page = get_queried_object();
	if ($page->ID == 302891) {
		setcookie('invitationhomes', 'visited', strtotime("+6 months"), "/");
	} else if (is_front_page() && isset($_COOKIE['invitationhomes']) && $_COOKIE['invitationhomes'] == "visited") {
		header("Location: /current-residents");
	}
}
add_action('wp_head', 'visitCookie');
*/
function IH_add_first_time_visit_cachekey($key) {

	if(!isset($_COOKIE['invitationhomes']) && $_COOKIE['invitationhomes'] == "visited") {
		$key .= 'ihvisited';
	}

	return $key;
}
//add_cacheaction( 'wp_cache_key', 'IH_add_first_time_visit_cachekey' );

function visitCookie() {
	if (isset($_GET['deletecookie'])) return;
	$page = get_queried_object();
	if ($page->ID == get_page_by_path("current-residents")->ID) {
		setcookie('invitationhomes', 'visited', strtotime("+6 months"), "/");
	} else if (is_front_page() && isset($_COOKIE['invitationhomes']) && $_COOKIE['invitationhomes'] == "visited") {
		header("Location: /current-residents");
		die;
	}
	//echo "<pre>";print_r($_COOKIE);
	if (isset($_GET['cookie'])) print_r($_COOKIE);
}
add_action('wp_head', 'visitCookie');

//Staging restrictions
if (file_exists(sys_get_temp_dir().'/staging-restrictions.php')) {
	define('STAGING_RESTRICTIONS', true);
	require_once sys_get_temp_dir().'/staging-restrictions.php';
}

include( get_template_directory() .'/classes.php' );
include( get_template_directory() .'/widgets.php' );

add_action('themecheck_checks_loaded', 'theme_disable_cheks');
function theme_disable_cheks() {
	$disabled_checks = array('TagCheck');
	global $themechecks;
	foreach ($themechecks as $key => $check) {
		if (is_object($check) && in_array(get_class($check), $disabled_checks)) {
			unset($themechecks[$key]);
		}
	}
}

add_theme_support( 'automatic-feed-links' );

if ( ! isset( $content_width ) ) $content_width = 900;

remove_action('wp_head', 'wp_generator');

add_action( 'after_setup_theme', 'theme_localization' );
function theme_localization () {
	load_theme_textdomain( 'InvitationHomes', get_template_directory() . '/languages' );
}


if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'id' => 'default-sidebar',
		'name' => __('Default Sidebar', 'InvitationHomes'),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'id' => 'disabled-sidebar',
		'name' => __('Disabed Widgets', 'InvitationHomes'),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 50, 50, true ); // Normal post thumbnails
	add_image_size( 'single-post-thumbnail', 400, 9999, true );
}

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'InvitationHomes' ),
	'footer_menu_1' => __( 'Footer Menu 1', 'InvitationHomes' ),
	'footer_menu_2' => __( 'Footer Menu 2', 'InvitationHomes' ),
	'footer_menu_3' => __( 'Footer Menu 3', 'InvitationHomes' ),
) );


//Add [email]...[/email] shortcode
function shortcode_email($atts, $content) {
	$result = '';
	for ($i=0; $i<strlen($content); $i++) {
		$result .= '&#'.ord($content{$i}).';';
	}
	return $result;
}
add_shortcode('email', 'shortcode_email');

//Register tag [template-url]
function filter_template_url($text) {
	return str_replace('[template-url]',get_bloginfo('template_url'), $text);
}
add_filter('the_content', 'filter_template_url');
add_filter('get_the_content', 'filter_template_url');
add_filter('widget_text', 'filter_template_url');

//Register tag [site-url]
function filter_site_url($text) {
	return str_replace('[site-url]',get_bloginfo('url'), $text);
}
add_filter('the_content', 'filter_site_url');
add_filter('get_the_content', 'filter_site_url');
add_filter('widget_text', 'filter_site_url');

//Replace standard wp menu classes
function change_menu_classes($css_classes) {
	$css_classes = str_replace("current-menu-item", "active", $css_classes);
	$css_classes = str_replace("current-menu-parent", "active", $css_classes);
	return $css_classes;
}
add_filter('nav_menu_css_class', 'change_menu_classes');

function theme_post_class($classes) {
	if (is_array($classes)) {
		foreach ($classes as $key => $class) {
			$classes[$key] = 'post-class-' . $classes[$key];
		}
	}

	return $classes;
}
add_filter('post_class', 'theme_post_class', 9999);

//Allow tags in category description
$filters = array('pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description');
foreach ( $filters as $filter ) {
    remove_filter($filter, 'wp_filter_kses');
}


//Make wp admin menu html valid
function wp_admin_bar_valid_search_menu( $wp_admin_bar ) {
	if ( is_admin() )
		return;

	$form  = '<form action="' . esc_url( home_url( '/' ) ) . '" method="get" id="adminbarsearch"><div>';
	$form .= '<input class="adminbar-input" name="s" id="adminbar-search" tabindex="10" type="text" value="" maxlength="150" />';
	$form .= '<input type="submit" class="adminbar-button" value="' . __('Search', 'InvitationHomes') . '"/>';
	$form .= '</div></form>';

	$wp_admin_bar->add_menu( array(
		'parent' => 'top-secondary',
		'id'     => 'search',
		'title'  => $form,
		'meta'   => array(
			'class'    => 'admin-bar-search',
			'tabindex' => -1,
		)
	) );
}

function fix_admin_menu_search() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
	add_action( 'admin_bar_menu', 'wp_admin_bar_valid_search_menu', 4 );
}

add_action( 'add_admin_bar_menus', 'fix_admin_menu_search' );

//Disable comments on pages by default
function theme_page_comment_status($post_ID, $post, $update) {
	if (!$update) {
		remove_action('save_post_page', 'theme_page_comment_status', 10);
		wp_update_post(array(
			'ID' => $post->ID,
			'comment_status' => 'closed',
		));
		add_action('save_post_page', 'theme_page_comment_status', 10, 3);
	}
}
add_action('save_post_page', 'theme_page_comment_status', 10, 3);

//custom excerpt
function theme_the_excerpt() {
	global $post;

	if (trim($post->post_excerpt)) {
		the_excerpt();
	} elseif (strpos($post->post_content, '<!--more-->') !== false) {
		the_content();
	} else {
		the_excerpt();
	}
}

/* advanced custom fields settings*/

//theme options tab in appearance
if(function_exists('acf_add_options_sub_page')) {
	acf_add_options_sub_page(array(
		'title' => 'Theme Options',
		'parent' => 'themes.php',
	));
}

//acf theme functions placeholders
if(!class_exists('acf') && !is_admin()) {
	function get_field_reference( $field_name, $post_id ) {return '';}
	function get_field_objects( $post_id = false, $options = array() ) {return false;}
	function get_fields( $post_id = false) {return false;}
	function get_field( $field_key, $post_id = false, $format_value = true )  {return false;}
	function get_field_object( $field_key, $post_id = false, $options = array() ) {return false;}
	function the_field( $field_name, $post_id = false ) {}
	function have_rows( $field_name, $post_id = false ) {return false;}
	function the_row() {}
	function reset_rows( $hard_reset = false ) {}
	function has_sub_field( $field_name, $post_id = false ) {return false;}
	function get_sub_field( $field_name ) {return false;}
	function the_sub_field($field_name) {}
	function get_sub_field_object( $child_name ) {return false;}
	function acf_get_child_field_from_parent_field( $child_name, $parent ) {return false;}
	function register_field_group( $array ) {}
	function get_row_layout() {return false;}
	function acf_form_head() {}
	function acf_form( $options = array() ) {}
	function update_field( $field_key, $value, $post_id = false ) {return false;}
	function delete_field( $field_name, $post_id ) {}
	function create_field( $field ) {}
	function reset_the_repeater_field() {}
	function the_repeater_field($field_name, $post_id = false) {return false;}
	function the_flexible_field($field_name, $post_id = false) {return false;}
	function acf_filter_post_id( $post_id ) {return $post_id;}
}

define('video_page_id' , '126');
define('blog_page_id' , '333126');
define('features' , '7');
define('news_page_id' , '118');
define('features_news' , '188');



/* ------------------------------------------------------------------------------------
	Define custom box for 'featured property'

	we have to do it this way instead of using WCK custom fields,
	since those can't be retrieved with get_post_meta and get_posts
*/

add_action( 'add_meta_boxes', 'myplugin_add_custom_box' );

/* Do something with the data entered */
add_action( 'save_post', 'myplugin_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function myplugin_add_custom_box() {
	add_meta_box(
	    'myplugin_sectionid',
	    'Featured property',
	    'myplugin_inner_custom_box',
	    'property'
	);
}

/* Prints the box content */
function myplugin_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );

  // The actual fields for data entry
  // Use get_post_meta to retrieve an existing value from the database and use the value for the form
  $value = get_post_meta( $post->ID, 'featured-property', true );
  echo "<input type='checkbox' id='featured-property' name='featured-property' ";
	if($value) echo 'checked';
	echo '> This is a featured property<p><i>Marking this checkbox will cause the property to be featured on the home page and the market page</i></p>';
}

/* When the post is saved, saves our custom data */
function myplugin_save_postdata( $post_id ) {

  // First we need to check if the current user is authorised to do this action.
  if ( 'page' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // Secondly we need to check if the user intended to change this value.
  if ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  // Thirdly we can save the value to the database

  //if saving in a custom table, get post_ID
  $post_ID = $_POST['post_ID'];
  //sanitize user input
  $mydata = sanitize_text_field( $_POST['featured-property'] );
  // Do something with $mydata
  // either using
  //add_post_meta($post_ID, 'featured-property', $mydata, true) or
    update_post_meta($post_ID, 'featured-property', $mydata);
}

/* ------------------------------------------------------------------------------------ */



/**
 * Register a Home Box post type.
 */
add_action( 'init', 'home_box_init' );

function home_box_init() {
	$labels = array(
		'name'               => _x( 'Home Box', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Home Box', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Home Boxes', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Home Box', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'Home box', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Home Box', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Home Box', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Home Box', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Home Box', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Home Box', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Home Boxes', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Home Boxes:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No home boxes found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No home box found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'home_box' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'home_box', $args );
}

register_taxonomy('home-category',array (
  0 => 'home_box'
),array( 'hierarchical' => true, 'label' => 'Homepage Options','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'Homepage Option') );

/* Inner Boxes Post Type */

add_action( 'init', 'inner_boxes_init' );

function inner_boxes_init() {
	$labels = array(
		'name'               => _x( 'Inner Page Box', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Inner Page Box', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Inner Page Boxes', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Inner Page Box', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'Inner Page box', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Inner Page Box', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Inner Page Box', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Inner Page Box', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Inner Page Box', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Inner Page Boxes', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Inner Page Boxes', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Inner Page Boxes:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No home boxes found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No Inner Page Box found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'inner_page_box' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('home-category'),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' )
	);

	register_post_type( 'inner_boxes', $args );
}


/**
 * Register a Video post type.
 */
add_action( 'init', 'video_init' );

 function video_init() {
	$labels = array(
		'name'               => _x( 'Video', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Video', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Videos', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Video', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'Video', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Video', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Video', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Video', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Video', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Video', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Video', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Videos:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No videos found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No video found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'video' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
	);

	register_post_type( 'video', $args );
}

// Custom post type Video Taxonomy Gallery

add_action( 'init', 'create_video_taxonomies', 0 );

function create_video_taxonomies() {
	$labels = array(
		'name'              => _x( 'Gallery', 'taxonomy general name' ),
		'singular_name'     => _x( 'Gallery', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Gallery' ),
		'all_items'         => __( 'All Gallery' ),
		'parent_item'       => __( 'Parent Gallery' ),
		'parent_item_colon' => __( 'Parent Gallery:' ),
		'edit_item'         => __( 'Edit Gallery' ),
		'update_item'       => __( 'Update Gallery' ),
		'add_new_item'      => __( 'Add New Gallery' ),
		'new_item_name'     => __( 'New Gallery Name' ),
		'menu_name'         => __( 'Gallery' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'gallery' ),
	);

	register_taxonomy( 'gallery', array( 'video' ), $args );

	$label = array(
		'name'              => _x( 'Category', 'taxonomy general name' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Category' ),
		'all_items'         => __( 'All Category' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Category' ),
	);

	$arg = array(
		'hierarchical'      => true,
		'labels'            => $label,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'video_category' ),
	);

	register_taxonomy( 'video_category', array( 'video' ), $arg );

}

function new_excerpt_more( $more ) {
	return ' ... <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Read More', 'your-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

function custom_search( $query ) {
    if ( $query->is_search() && $query->is_main_query() ) {

 $taxonomies = array('gallery', 'video_category', 'category');
 $key = array_search($_GET['taxonomy'], $taxonomies);
 $term_ids = array();

 if($key !== false && isset($_GET['cat_name'])){
  foreach ($_GET['cat_name'] as $term_id)
   if(!empty($term_id))
    $term_ids[] = (int) $term_id;

  if($term_ids){
   switch($taxonomies[$key]){
    case 'category' : $query->set( 'post_type', 'post' ); break;
    case 'gallery':
    case 'video_category': $query->set( 'post_type', 'video' ); break;
    case 'news':
    case 'news_category': $query->set( 'post_type', 'news' ); break;
   }

   $query->set( 'tax_query', array(
    array(
     'taxonomy' => $taxonomies[$key],
     'field'    => 'id',
     'terms'    => $term_ids,
     'compare' => 'IN',
    ),
   ) );
  }



 }
    }
}
add_action( 'pre_get_posts', 'custom_search' );
//ADD HOME SLIDE TYPE
add_action('init', 'homeslide_register');
function homeslide_register() {
    $labels = array(
        'name' => _x('Home Slides', 'home_page_image'),
	'description' => _x('A single image for the home page slider.','default'),
        'singular_name' => _x('Home Slide', 'home_page_image'),
        'add_new' => _x('Add New', 'home_page_image'),
        'add_new_item' => __('Add New Home Slide'),
        'edit_item' => __('Edit Home Slide'),
        'new_item' => __('New Home Slide'),
        'view_item' => __('View Home Slide'),
        'search_items' => __('Search Home Slide'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
		'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 10,
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'home_page_image' , $args );
}

//ADD ANNOUNCEMENT TYPE
add_action('init', 'announcement_register');
function announcement_register() {
    $labels = array(
        'name' => _x('Announcements', 'announcement'),
		'description' => _x('General site announcements belong here.','default'),
        'singular_name' => _x('Announcement', 'announcement'),
        'add_new' => _x('Add New', 'announcement'),
        'add_new_item' => __('Add New Announcement'),
        'edit_item' => __('Edit Announcement'),
        'new_item' => __('New Announcement'),
        'view_item' => __('View Announcement'),
        'search_items' => __('Search Announcements'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
		'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 10,
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'announcement' , $args );
}

//ADD TESTIMONIAL TYPE
//add_action('init', 'testimonial_register');
function testimonial_register() {
    $labels = array(
        'name' => _x('Testimonials', 'testimonial'),
		'description' => _x('Testimonial page.','default'),
        'singular_name' => _x('Testimonial', 'testimonial'),
        'add_new' => _x('Add New', 'testimonial'),
        'add_new_item' => __('Add New Testimonial'),
        'edit_item' => __('Edit Testimonial'),
        'new_item' => __('New Testimonial'),
        'view_item' => __('View Testimonial'),
        'search_items' => __('Search Testimonials'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
		'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
		'rewrite_slug' => 'testimonial',
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 11,
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'testimonial' , $args );
}

//ADD EVENT TYPE
/*
add_action('init', 'event_register');
function event_register() {
    $labels = array(
        'name' => _x('Events', 'event'),
		'singular_name' => _x('Event', 'event'),
		'add_new' => _x('Add New', 'event'),
		'add_new_item' => __('Add New Event'),
		'edit_item' => __('Edit Event'),
		'new_item' => __('New Event'),
		'view_item' => __('View Event'),
		'search_items' => __('Search Event'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
	    'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
        'has_archive' => true,
		'rewrite' => array( 'slug' => 'ihevent' ),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 14,
		'supports' => array('title','editor','thumbnail'),
		'taxonomies' => array('category', 'post_tag')
	);
	register_post_type( 'event' , $args );
}
*/

// Register blogs

add_action('init', 'blogs_register');
function blogs_register() {
    $labels = array(
        'name' => _x('Blog', 'blog'),
		'description' => _x('Blog','default'),
        'singular_name' => _x('Blog', 'blog'),
        'add_new' => _x('Add New', 'blog'),
        'add_new_item' => __('Add New Blog'),
        'edit_item' => __('Edit Blog'),
        'new_item' => __('New Blog'),
        'view_item' => __('View Blog'),
        'search_items' => __('Search Blog'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
		'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 9,
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'blog' , $args );
}
register_taxonomy('blog-categories',array (
  0 => 'blog'
),array( 'hierarchical' => true, 'label' => 'Categories','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'Category') );

// ADD NEWS TYPE
add_action('init', 'news_register');
function news_register() {
    $labels = array(
        'name' => _x('News', 'news'),
		'description' => _x('News','default'),
        'singular_name' => _x('News', 'news'),
        'add_new' => _x('Add New', 'news'),
        'add_new_item' => __('Add New News'),
        'edit_item' => __('Edit News'),
        'new_item' => __('New News'),
        'view_item' => __('View News'),
        'search_items' => __('Search News'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
		'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 9,
        //'taxonomies' => array('team-categories','post_tag'),
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'news' , $args );

	register_taxonomy('news-categories',array (
  0 => 'news'
),array( 'hierarchical' => true, 'label' => 'Categories','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'News') );

	//register_taxonomy_for_object_type( 'post_tag', 'news' );
}

//ADD MARKET TYPE
add_action('init', 'market_register');
function market_register() {
    $labels = array(
        'name' => _x('Market Pages', 'market'),
		'description' => _x('Primary market page.','default'),
        'singular_name' => _x('Market', 'market'),
        'add_new' => _x('Add New', 'market'),
        'add_new_item' => __('Add New Market'),
        'edit_item' => __('Edit Market'),
        'new_item' => __('New Market'),
        'view_item' => __('View Market'),
        'search_items' => __('Search Market'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
		'has_archive' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => true,
        'menu_position' => 9,
        'supports' => array('title','editor','thumbnail','page-attributes')
    );
    register_post_type( 'market' , $args );
}

//ADD PROPERTY TYPE
add_action('init', 'property_register');
function property_register() {
    $labels = array(
        'name' => _x('Properties', 'property'),
		'description' => _x('Property page.','default'),
        'singular_name' => _x('Property', 'property'),
        'add_new' => _x('Add New', 'property'),
        'add_new_item' => __('Add New Property'),
        'edit_item' => __('Edit Property'),
        'new_item' => __('New Property'),
        'view_item' => __('View Property'),
        'search_items' => __('Search Property'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
		'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
		'rewrite_slug' => 'property',
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 8,
        'exclude_from_search' => true,
        'supports' => array('custom-fields','title','editor','thumbnail')
    );
    register_post_type( 'property' , $args );
}

//ADD TEAM TYPE
add_action('init', 'team_register');
function team_register() {
    $labels = array(
        'name' => _x('Team', 'team'),
		'description' => _x('Team Members','default'),
        'singular_name' => _x('Team', 'team'),
        'add_new' => _x('Add Team Member', 'team'),
        'add_new_item' => __('Add New Team Member'),
        'edit_item' => __('Edit Team Member'),
        'new_item' => __('New Team Member'),
        'view_item' => __('View Team Member'),
        'search_items' => __('Search Team Members'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
		'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
		'rewrite_slug' => 'team',
        'capability_type' => 'page',
        'hierarchical' => false,
        'menu_position' => 8,
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'team' , $args );
}
register_taxonomy('team-categories',array (
  0 => 'team'
),array( 'hierarchical' => true, 'label' => 'Categories','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'Category') );

register_taxonomy('team-locations',array (
  0 => 'team'
),array( 'hierarchical' => true, 'label' => 'Locations','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'Location') );
//ADD VIDEO TYPE
add_action('init', 'video_register');
function video_register() {
    $labels = array(
        'name' => _x('Videos', 'video'),
		'description' => _x('Videos for the site.','default'),
        'singular_name' => _x('Video', 'video'),
        'add_new' => _x('Add New', 'video'),
        'add_new_item' => __('Add New Video'),
        'edit_item' => __('Edit Video'),
        'new_item' => __('New Video'),
        'view_item' => __('View Video'),
        'search_items' => __('Search Videos'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
		'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
		'rewrite_slug' => 'video',
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 8,
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'video' , $args );
}

//Register Featured Column
add_filter('manage_edit-property_columns', 'my_columns');
function my_columns($columns) {
    $columns['featured-property'] = 'Is Featured Property';
    unset($columns['comments']);
    return $columns;
}
add_action('manage_posts_custom_column', 'populate_columns');
function populate_columns( $column) {
    global $post;
    if ('featured-property' == $column) {
        $is_featured = get_post_meta($post->ID, 'featured-property', true );
        echo $is_featured;
    }
}


//ADD CAREER TYPE
add_action('init', 'career_register');
function career_register() {
    $labels = array(
        'name' => _x('Careers', 'career'),
		'description' => _x('Career post','default'),
        'singular_name' => _x('Career', 'career'),
        'add_new' => _x('Add New', 'career'),
        'add_new_item' => __('Add New Career'),
        'edit_item' => __('Edit Career'),
        'new_item' => __('New Career'),
        'view_item' => __('View Career'),
        'search_items' => __('Search Career'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
		'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 13,
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'career' , $args );
}


//ADD LINKS TYPE
add_action('init', 'links_register');
function links_register() {
    $labels = array(
        'name' => _x('Links', 'links'),
		'description' => _x('Link post','default'),
        'singular_name' => _x('Link', 'links'),
        'add_new' => _x('Add New', 'links'),
        'add_new_item' => __('Add New Link'),
        'edit_item' => __('Edit Link'),
        'new_item' => __('New Link'),
        'view_item' => __('View Link'),
        'search_items' => __('Search Link'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
		'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 15,
        'supports' => array('title','editor','excerpt','thumbnail')
    );
    register_post_type( 'links' , $args );
}

// NOW REGISTER TAXONOMY
add_action('init', 'market_hierarchical_taxonomy');
function market_hierarchical_taxonomy() {
 $labels = array(
    'name' => _x( 'Markets', 'market' ),
    'singular_name' => _x( 'Market', 'market' ),
    'search_items' =>  __( 'Search Market' ),
    'all_items' => __( 'All Market' ),
    'parent_item' => __( 'Parent Market' ),
    'parent_item_colon' => __( 'Parent Market:' ),
    'edit_item' => __( 'Edit Market' ),
    'update_item' => __( 'Update Market' ),
    'add_new_item' => __( 'Add Market' ),
    'new_item_name' => __( 'New Market' ),
    'menu_name' => __( 'Markets' ),
  );
  register_taxonomy('market', array('market', 'property'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'market' ),
  ));
}

add_action('init', 'career_taxonomy');
function career_taxonomy() {
 $labels = array(
    'name' => _x( 'Career Locations', 'careerlocation' ),
    'singular_name' => _x( 'Career Location', 'careerlocation' ),
    'search_items' =>  __( 'Search Career Locations' ),
    'all_items' => __( 'All Career Locations' ),
    'edit_item' => __( 'Edit Career Locations' ),
    'update_item' => __( 'Update Career Locations' ),
    'add_new_item' => __( 'Add Career Location' ),
    'new_item_name' => __( 'New Career Location' ),
    'menu_name' => __( 'Career Locations' ),
  );
  register_taxonomy('careerlocation', array('career'), array(
    'hierarchical' => true,
    'labels' => $labels,
	'public' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'careerlocation' ),
  ));
}


add_action('init', 'links_taxonomy');
function links_taxonomy() {
 $labels = array(
    'name' => _x( 'Link Topic', 'linktopic' ),
    'singular_name' => _x( 'Link Topic', 'linktopic' ),
    'search_items' =>  __( 'Search Link Topics' ),
    'all_items' => __( 'All Link Topics' ),
    'edit_item' => __( 'Edit Link Topics' ),
    'update_item' => __( 'Update Link Topics' ),
    'add_new_item' => __( 'Add Link Topic' ),
    'new_item_name' => __( 'New Link Topic' ),
    'menu_name' => __( 'Link Topics' ),
  );
  register_taxonomy('linktopic', array('links'), array(
    'hierarchical' => true,
    'labels' => $labels,
	'public' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'linktopic' ),
  ));
}

/* Arrow Pagination */

function get_pagination($range = 4){
  // $paged - number of the current page
  global $paged, $wp_query;
  // How much pages do we have?
  if ( !$max_page ) {
    $max_page = $wp_query->max_num_pages;
  }
  // We need the pagination only if there are more than 1 page
  if($max_page > 1){
    if(!$paged){
      $paged = 1;
    }
    // On the first page, don't put the First page link
    if($paged != 1){
      echo "<a href=" . get_pagenum_link(1) . " class=\"first\"></a>";
    }
    // To the previous page
    previous_posts_link('<span class="previous"></span>');
    // Next page
    next_posts_link('<span class="next"></span>');
    // On the last page, don't put the Last page link
    if($paged != $max_page){
      echo " <a href=" . get_pagenum_link($max_page) . " class=\"last\"></a>";
    }
  }
}
add_action( 'after_setup_theme', 'get_pagination' );
add_filter('gform_validation', 'custom_validation');
function custom_validation($validation_result){
    $form = $validation_result["form"];

    //supposing we don't want input 1 to be a value of First Name
    if($_POST['input_1'] == "First Name *"){

        // set the form validation to false
        $validation_result["is_valid"] = false;

        //finding Field with ID of 1 and marking it as failed validation
        foreach($form["fields"] as &$field){

            //NOTE: replace 1 with the field you would like to validate
            if($field["id"] == "1"){
                $field["failed_validation"] = true;
                $field["validation_message"] = "First name required";
                break;
            }
        }

    }
	//supposing we don't want input 6 to be a value of Last Name
    if($_POST['input_6'] == "Last Name *"){

        // set the form validation to false
        $validation_result["is_valid"] = false;

        //finding Field with ID of 1 and marking it as failed validation
        foreach($form["fields"] as &$field){

            //NOTE: replace 1 with the field you would like to validate
            if($field["id"] == "6"){
                $field["failed_validation"] = true;
                $field["validation_message"] = "Last Name required";
                break;
            }
        }

    }

    //Assign modified $form object back to the validation result
    $validation_result["form"] = $form;
    return $validation_result;

}


// Custom redirect for market taxonomy
add_action('admin_init', 'custom_redirect_for_market_taxonomy');

function custom_redirect_for_market_taxonomy ()
{
$base=explode('?', basename($_SERVER['REQUEST_URI']));

if($base[0]=='edit.php' && isset($_GET['market']) && $_GET['market'] != '' && isset($_GET['post_type']) && $_GET['post_type'] != '' )
  {
    if($_GET['post_type'] == 'market' || $_GET['post_type'] == 'property')
	 {
     $path = "edit.php?taxonomy=market&term=".$_GET['market']."&post_type=".$_GET['post_type'];
     $urlR = admin_url($path);
	 wp_redirect($urlR);
	 exit;
	 }
   }
}
/* Events */

function tribe_events_event_schedule_details_2( $event = null, $before = '', $after = '' ) {
		if ( is_null( $event ) ) {
			global $post;
			$event = $post;
		}

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		$schedule                 = '<span class="date-start-modified dtstart">';
		$format                   = '';
		$date_without_year_format = 'M j l';
		$date_with_year_format    = 'M j l';
		$time_format              = ' ';
		$datetime_separator       = tribe_get_option( 'dateTimeSeparator', '' );
		$time_range_separator     = tribe_get_option( 'timeRangeSeparator', '' );
		$microformatStartFormat   = tribe_get_start_date( $event, false, 'Y-m-dTh:i' );
		$microformatEndFormat     = tribe_get_end_date( $event, false, 'Y-m-dTh:i' );
		$empty_value = '';

		$settings = array(
			'show_end_time' => true,
			'time'          => true,
		);

		$settings = wp_parse_args( apply_filters( 'tribe_events_event_schedule_details_2_formatting', $settings ), $settings );
		if ( ! $settings['time'] ) {
			$settings['show_end_time'] = false;
		}
		extract( $settings );

		$format = $date_with_year_format;

		// if it starts and ends in the current year then there is no need to display the year
		if ( tribe_get_start_date( $event, false, 'Y' ) === date( 'Y' ) && tribe_get_end_date( $event, false, 'Y' ) === date( 'Y' ) ) {
			$format = $date_without_year_format;
		}

		if ( tribe_event_is_multiday( $event ) ) { // multi-date event

			$format2ndday = apply_filters( 'tribe_format_second_date_in_range', $format, $event );

			if ( 1 || tribe_event_is_all_day( $event ) ) {
				$schedule .= '<span class="word1">';
				$schedule .= tribe_get_start_date( $event, true, 'M' );
				$schedule .= '</span><span class="word2">';
				$schedule .= tribe_get_start_date( $event, true, 'j' );
				$schedule .= '</span><span class="word3">';
				$schedule .= tribe_get_start_date( $event, true, 'l' );
				$schedule .= '</span>';
			} else {
				$schedule .= tribe_get_start_date( $event, false, $format ) . ( $time ? $empty_value . tribe_get_start_date( $event, false, $time_format ) : '' );

				$schedule .= '</span>';
				$schedule .= '';
				$schedule .= '';
			}

		} elseif ( tribe_event_is_all_day( $event ) ) { // all day event
			$schedule .= '<span class="word1">';
			$schedule .= tribe_get_start_date( $event, true, 'M' );
			$schedule .= '</span><span class="word2">';
			$schedule .= tribe_get_start_date( $event, true, 'j' );
			$schedule .= '</span><span class="word3">';
			$schedule .= tribe_get_start_date( $event, true, 'l' );
			$schedule .= '</span>';
		} else { // single day event
			if ( tribe_get_start_date( $event, false, 'g:i A' ) === tribe_get_end_date( $event, false, 'g:i A' ) ) { // Same start/end time
				$schedule .= '<span class="word1">';
				$schedule .= tribe_get_start_date( $event, false, 'M' );
				$schedule .= '</span><span class="word2">';
				$schedule .= tribe_get_start_date( $event, false, 'j' );
				$schedule .= '</span><span class="word3">';
				$schedule .= tribe_get_start_date( $event, false, 'l' );
				$schedule .= '</span>';

			} else { // defined start/end time
				$schedule .= '<span class="word1">';
				$schedule .= tribe_get_start_date( $event, false, 'M' );
				$schedule .= '</span><span class="word2">';
				$schedule .= tribe_get_start_date( $event, false, 'j' );
				$schedule .= '</span><span class="word3">';
				$schedule .= tribe_get_start_date( $event, false, 'l' );
				$schedule .= '</span>';
			}
		}

		$schedule .= '</span>';

		$schedule = $before . $schedule . $after;

		return apply_filters( 'tribe_events_event_schedule_details_2', $schedule );
	}
function add_menu_icons_styles(){
?>

<style>
#adminmenu #menu-posts-team div.wp-menu-image:before {
	content: "\f307";
}
#adminmenu #menu-posts-career div.wp-menu-image:before {
	content: "\f310";
}
#adminmenu #menu-posts-property div.wp-menu-image:before {
	content: "\f102";
}
#adminmenu #menu-posts-announcement div.wp-menu-image:before {
	content: "\f488";
}
#adminmenu #menu-posts-home_page_image div.wp-menu-image:before {
	content: "\f489";
}
}
</style>

<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );

/*class my_Walker_CategoryDropdown extends Walker_CategoryDropdown {
	function start_el(&$output, $category, $depth, $args) { $pad = str_repeat(' ', $depth * 3);
	$cat_name = apply_filters('list_cats', $category->name, $category); $output .= "\t<option class=\"level-$depth\" value=\"".$category->slug."\""; if ( $category->term_id == $args['selected'] ) $output .= ' selected="selected"'; $output .= '>'; $output .= $pad.$cat_name; if ( $args['show_count'] ) $output .= '  ('. $category->count .')'; if ( $args['show_last_update'] ) { $format = 'Y-m-d'; $output .= '  ' . gmdate($format, $category->last_update_timestamp); } $output .= "</option>\n"; }
}*/

function ih_breadcrumb (array $options = array() ) {


	// default values assigned to options
	$options = array_merge(array(
        'crumbId' => 'nav_crumb', // id for the breadcrumb Div
	'crumbClass' => 'nav_crumb', // class for the breadcrumb Div
	 // text showing before breadcrumb starts
	'showOnHome' => 0,// 1 - show breadcrumbs on the homepage, 0 - don't show
	'delimiter' => '|', // delimiter between crumbs
	'homePageText' => 'Home', // text for the 'Home' link
	'showCurrent' => 1, // 1 - show current post/page title in breadcrumbs, 0 - don't show
	'beforeTag' => '<span class="current">', // tag before the current breadcrumb
	'afterTag' => '</span>', // tag after the current crumb
	'showTitle'=> 1 // showing post/page title or slug if title to show then 1
   ), $options);

   $crumbId = $options['crumbId'];
	$crumbClass = $options['crumbClass'];
	$beginningText = $options['beginningText'] ;
	$showOnHome = $options['showOnHome'];
	$delimiter = $options['delimiter'];
	$homePageText = $options['homePageText'];
	$showCurrent = $options['showCurrent'];
	$beforeTag = $options['beforeTag'];
	$afterTag = $options['afterTag'];
	$showTitle =  $options['showTitle'];

	global $post;
	$name ='';
	if( isset($_GET['loc']) && $_GET['loc'] != '' )
	{
		$location = explode(',',$_GET['loc']);
		foreach($location as $lc)
		{
		$tname = get_term( $lc, 'team-locations' );
		$name .= $tname->name.' , ';
		}
		$name = rtrim(' | '.$name, " , ");
	}
	$tcname ='';
	if( isset($_GET['team_c']) && $_GET['team_c'] != '' )
	{
		$categories = explode(',',$_GET['team_c']);
		foreach($categories as $lc)
		{
		$tname = get_term( $lc, 'team-categories' );
		$tcname .= $tname->name.' , ';
		}
		$tcname = rtrim(' | '.$tcname, " , ");
	}
	$wp_query = $GLOBALS['wp_query'];

	$homeLink = get_bloginfo('url');

	$queried_object = $wp_query->get_queried_object();

	echo '<div id="'.$crumbId.'" class="'.$crumbClass.'" >'.$beginningText;

	if (is_home() || is_front_page()) {

	  if ($showOnHome == 1)

		  echo $beforeTag . $homePageText . $afterTag;

	} else {

	  echo '<a href="' . $homeLink . '">' . $homePageText . '</a> ' . $delimiter . ' ';

	  if ( is_category() ) {


		$thisCat = get_category(get_query_var('cat'), false);
		if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
		echo $beforeTag . 'Archive by category "' . single_cat_title('', false) . '"' . $afterTag;

	  }
	  elseif(isset($_GET['tribe_venues']) && $_GET['tribe_venues']!=""&& $_GET['tribe_venues']>0)
	  {
		$vid = $_GET['tribe_venues'];
	  	echo '<a href="'.get_option('site_url').'/events/">Upcoming Events</a> ' ;
		$all_venue =  get_ih_venue_values();
		echo $delimiter.' ' ;
		if(isset($queried_object->name) && $queried_object->name!="")
		echo  $queried_object->name.' &raquo; ';
		if(isset($all_venue[$vid]) && $all_venue[$vid]!="")
		{
			$v_detail = $all_venue[$vid];
			echo $beforeTag . $v_detail['name'] . '' . $afterTag;
		}
	  }
	  elseif ( is_tax() ) {
		  $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		  $parents = array();
		  $parent = $term->parent;
		  while ( $parent ) {
			  $parents[] = $parent;
			  $new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
			  $parent = $new_parent->parent;

		  }
		  if ( ! empty( $parents ) ) {
			  $parents = array_reverse( $parents );
			  foreach ( $parents as $parent ) {
				  $item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
				  echo   '<a href="' . get_term_link( $item->slug, get_query_var( 'taxonomy' ) ) . '">' . $item->name . '</a>'  . $delimiter;
			  }
		  }



		  foreach ( $queried_object as $taxonomy_slug => $taxonomy ){
		     if($taxonomy_slug == 'taxonomy' && $taxonomy == 'tribe_events_cat') {

			 echo '<a href="'.site_url().'/events">' ."Upcoming Events". '</a>';
			 }
		  }
		          //$post_type = wp_get_post_terms($post->ID, 'video_category', array("fields" => "all"));
				 // print_r($post_type);
                 $post = get_post( $post->ID );
				  $post_type = $post->post_type;
				  $taxonomies = get_object_taxonomies( $post_type, 'objects' );
				// print_r($taxonomies);die;
				  foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){
				//  print_r($taxonomy_slug);
					  $tax = str_replace('gallery','',$taxonomy_slug);
					  if($taxonomy_slug == 'video_category') {
					  $check = get_term_link( $term, $taxonomy_slug );

					  echo '<a href="'.site_url().'/video-gallery">' . "Video Gallery" . '</a>';
					    } else if($taxonomy_slug == 'blog-categories') {
						 echo '<a href="'.site_url().'/blog">' ."Blog". '</a>';
					} else if($taxonomy_slug == 'news-categories') {
						 echo '<a href="'.site_url().'/news">' ."News". '</a>';
					} else if($taxonomy_slug == 'view') {
						 echo '<a href="'.site_url().'/news">' ."View". '</a>';
					}  else if($taxonomy_slug == 'wpm-testimonial-category') {
						 echo '<a href="'.site_url().'/reviews">' ."Reviews". '</a>';
					} else if($taxonomy_slug == 'team-categories') {
						 echo '<a href="'.site_url().'/our-team">' ."Team". '</a>';
					}
					  //$terms = get_the_terms( $post->ID, $taxonomy_slug );
					  //print_r($terms);
				  }
				$slug = $post_type->rewrite;
		 	   echo   ' ' . $delimiter  . ' ' . $slug['slug']. $queried_object->name . $afterTag;
		  } elseif ( is_search() ) {
		echo $beforeTag . 'Search results for "' . get_search_query() . '"' . $afterTag;

	  } elseif ( is_day() ) {
		echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
		echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
		echo $beforeTag . get_the_time('d') . $afterTag;


	  } elseif ( is_month() ) {
		echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
		echo $beforeTag . get_the_time('F') . $afterTag;

	  } elseif ( is_year() ) {
		echo $beforeTag . get_the_time('Y') . $afterTag;

	  } elseif ( is_single() && !is_attachment() ) {

		     if($showTitle)
			   $title = get_the_title();
			  else
			  $title =  $post->post_name;

					 if ( get_post_type() == 'product' ) { // it is for custom post type with custome taxonomies like
					 //Breadcrumb would be : Home Furnishings > Bed Covers > Cotton Quilt King Kantha Bedspread
					 // product = Cotton Quilt King Kantha Bedspread, custom taxonomy product_cat (Home Furnishings -> Bed Covers)
// show  product with category on single page

					  if ( $terms = wp_get_object_terms( $post->ID, 'product_cat' ) ) {

						  $term = current( $terms );

						  $parents = array();

						  $parent = $term->parent;
						  while ( $parent ) {

							  $parents[] = $parent;

							  $new_parent = get_term_by( 'id', $parent, 'product_cat' );

							  $parent = $new_parent->parent;

						  }
						  if ( ! empty( $parents ) ) {

							  $parents = array_reverse($parents);

							  foreach ( $parents as $parent ) {

								  $item = get_term_by( 'id', $parent, 'product_cat');

								  echo  '<a href="' . get_term_link( $item->slug, 'product_cat' ) . '">' . $item->name . '</a>'  . $delimiter;

							  }

						  }
						  echo  '<a href="' . get_term_link( $term->slug, 'product_cat' ) . '">' . $term->name . '</a>'  . $delimiter;
					  }
					  echo $beforeTag . $title . $afterTag;
				  }  elseif ( get_post_type() != 'post' ) {
				  $post_type = get_post_type_object(get_post_type());
				 // print_r($post->post_type);
				  $slug = $post_type->rewrite;
				 // print_r($post->post_name);
				  //echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
				  if ($showCurrent == 1) {
				  if($post->post_type=='market') {
				   echo $title;
				   //echo ' ' . $delimiter . ' ' . 'Property Map';
				   } else if($post->post_type=='team'){
				   echo '<a href="' . $homeLink . '/our-team">' . $post_type->labels->singular_name . '</a>';
				   echo  ' ' .$delimiter . $beforeTag . $title . $afterTag;
				   } else if($post->post_type=='video'){
				   echo '<a href="' . $homeLink . '/video-gallery">' . $post_type->labels->singular_name . '</a>';
				   echo  ' ' .$delimiter . $beforeTag . $title . $afterTag;
				   }
				    else if($post->post_type=='tribe_events'){
				   echo '<a href="' . $homeLink . '/event">' . 'Upcoming Events' . '</a>';
				   echo  ' ' .$delimiter . $beforeTag . $title . $afterTag;
				   } else {
				   echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
				   echo  ' ' .$delimiter . $beforeTag . $title . $afterTag;
				   }

				  }

				} else {
				  $cat = get_the_category(); $cat = $cat[0];
				  $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				  if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
				  echo $cats;
				  if ($showCurrent == 1) echo $beforeTag . $title . $afterTag;

				}

	  } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {

		$post_type = get_post_type_object(get_post_type());
		if($post_type->labels->singular_name == "Event")
		echo $beforeTag . 'Upcoming Events' . $afterTag;
		else
		echo $beforeTag . $post_type->labels->singular_name . $afterTag;

	 } elseif ( is_attachment() ) {

		$parent = get_post($post->post_parent);
		$cat = get_the_category($parent->ID); $cat = $cat[0];
		echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
		echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
		if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $beforeTag . get_the_title() . $afterTag;

		} elseif ( is_page() && !$post->post_parent ) {
			$title =($showTitle)? get_the_title():$post->post_name;
			if( is_page(25) && isset($_GET['team_c']) && $_GET['team_c'] != '' && isset($_GET['loc']) && $_GET['loc'] != '' )
			{
			$title = '<a href="'.site_url().'/our-team">' .$title. '</a>' . $tcname.str_replace("|",",",$name);
			} else if( is_page(25) && isset($_GET['team_c']) && $_GET['team_c'] != '' )
			{
				$title = '<a href="'.site_url().'/our-team">' .$title. '</a>' . $tcname;
			} else if( is_page(25) && isset($_GET['loc']) && $_GET['loc'] != '' )
			{
			$title = '<a href="'.site_url().'/our-team">' .$title. '</a>' . $name;
			}
			//if(is_page(25))$title .= $name;

		if ($showCurrent == 1) echo $beforeTag .  $title . $afterTag;

	  } elseif ( is_page() && $post->post_parent ) {
		$parent_id  = $post->post_parent;
		$breadcrumbs = array();
		while ($parent_id) {
		  $page = get_page($parent_id);
		  $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
		  $parent_id  = $page->post_parent;
		}
		$breadcrumbs = array_reverse($breadcrumbs);
		for ($i = 0; $i < count($breadcrumbs); $i++) {
		  echo $breadcrumbs[$i];
		  if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
		}
			$title =($showTitle)? get_the_title():$post->post_name;

	if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $beforeTag . $title . $afterTag;

	  } elseif ( is_tag() ) {

		echo $beforeTag . 'Posts tagged "' . single_tag_title('', false) . '"' . $afterTag;

	  } elseif ( is_author() ) {
		 global $author;
		$userdata = get_userdata($author);

		echo $beforeTag . 'Articles posted by ' . $userdata->display_name . $afterTag;

	  } elseif ( is_404() ) {
		  global $wp_query;
         if($wp_query->query_vars['post_type'] == 'tribe_events') {

		 echo $beforeTag . 'Upcoming Events' . $afterTag;

		 } else {
		echo $beforeTag . 'Error 404' . $afterTag;
		}

	  }

	  if ( get_query_var('paged') ) {
		if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_tax() ) echo ' (';
		echo __(' ' . $delimiter.' Page') . '  ' . get_query_var('paged');
		if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_tax() ) echo ')';
	  }
	}
	echo '</div>';
  }
// end ft_custom_breadcrumb
//to remove next/prev post link
add_filter( 'previous_post_rel_link', 'disable_stuff' );
add_filter( 'next_post_rel_link', 'disable_stuff' );

function disable_stuff( $data ) {
	return false;
}
//remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10,0);
//Addedon 17 Aug 2015

add_filter("tribe_events_filter_values", "ih_tribe_events_filter_values",10, 2);
function ih_tribe_events_filter_values($value , $slug)
{
	$valuereturn = array();
	$menu = wp_get_nav_menu_items(104);
	$allMarket = array();
	if($menu)
	foreach ($menu as $market) {
		$allMarket[] =  $market->title;
	}
	foreach ( $value as $option )
	{
		//echo "asd";
		if(in_array($option['name'], $allMarket))
			$valuereturn[] = $option;
	}
	return $valuereturn;
}
function isnotemptyeventvenue($eventid)
{
	global $wpdb, $wp_query;
	$start_date  = $wp_query->get( 'start_date' );

	$sqlET = $wpdb->prepare("SELECT DISTINCT ".$wpdb->prefix."posts.*, MIN(".$wpdb->prefix."postmeta.meta_value) as EventStartDate, tribe_event_end_date.meta_value as EventEndDate FROM ".$wpdb->prefix."posts  INNER JOIN ".$wpdb->prefix."postmeta ON ( ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id ) LEFT JOIN ".$wpdb->prefix."postmeta as tribe_event_end_date ON ( ".$wpdb->prefix."posts.ID = tribe_event_end_date.post_id AND tribe_event_end_date.meta_key = '_EventEndDate' ) LEFT JOIN ".$wpdb->prefix."postmeta as tribe_event_id ON ( ".$wpdb->prefix."posts.ID = tribe_event_id.post_id AND tribe_event_id.meta_key = '_EventVenueID' )  WHERE 1=1 AND tribe_event_id.meta_value = %d AND (
  ".$wpdb->prefix."postmeta.meta_key = '_EventStartDate'
) AND ".$wpdb->prefix."posts.post_type = 'tribe_events' AND ( ".$wpdb->prefix."posts.post_status = 'publish' OR ".$wpdb->prefix."posts.post_status = 'private') AND ( ".$wpdb->prefix."postmeta.meta_value >= %s OR ( ".$wpdb->prefix."postmeta.meta_value <= %s AND tribe_event_end_date.meta_value >= %s )) GROUP BY  IF( ".$wpdb->prefix."posts.post_parent = 0, ".$wpdb->prefix."posts.ID, ".$wpdb->prefix."posts.post_parent ) ORDER BY ".$wpdb->prefix."posts.menu_order, DATE(MIN( ".$wpdb->prefix."postmeta.meta_value)) ASC, TIME( ".$wpdb->prefix."postmeta.meta_value) ASC LIMIT 0, 1",$eventid ,$start_date, $start_date ,$start_date );
							$resultsET = $wpdb->get_results( $sqlET );
							if ( !is_wp_error( $resultsET ) ) {
								$return = array();
								foreach ( $resultsET as $fivesdraft )
								{
									$return[] = $fivesdraft->ID;
								}
								return $return;
							}
							return array();

						///$numberofevents = new WP_Query($args);

						//print_r($numberofevents);
						//return $numberofevents->post_count;
}
function isnotemptyeventcategory($catid)
{
	global $wpdb, $wp_query;
	$start_date  = $wp_query->get( 'start_date' );
	$sqlET = $wpdb->prepare("SELECT  DISTINCT ".$wpdb->prefix."posts.*, MIN(".$wpdb->prefix."postmeta.meta_value) as EventStartDate, tribe_event_end_date.meta_value as EventEndDate FROM ".$wpdb->prefix."posts  INNER JOIN ".$wpdb->prefix."term_relationships ON (wp_posts.ID = ".$wpdb->prefix."term_relationships.object_id)  INNER JOIN ".$wpdb->prefix."term_relationships AS tt1 ON (wp_posts.ID = tt1.object_id) INNER JOIN ".$wpdb->prefix."postmeta ON ( ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id ) LEFT JOIN ".$wpdb->prefix."postmeta as tribe_event_end_date ON ( ".$wpdb->prefix."posts.ID = tribe_event_end_date.post_id AND tribe_event_end_date.meta_key = '_EventEndDate' )  WHERE 1=1  AND (
  ".$wpdb->prefix."term_relationships.term_taxonomy_id IN (%d)
  AND
  tt1.term_taxonomy_id IN (%d)
) AND (
  ".$wpdb->prefix."postmeta.meta_key = '_EventStartDate'
) AND ".$wpdb->prefix."posts.post_type = 'tribe_events' AND ( ".$wpdb->prefix."posts.post_status = 'publish' OR ".$wpdb->prefix."posts.post_status = 'private') AND ( ".$wpdb->prefix."postmeta.meta_value >= %s OR ( ".$wpdb->prefix."postmeta.meta_value <= %s AND tribe_event_end_date.meta_value >= %s )) GROUP BY  IF( ".$wpdb->prefix."posts.post_parent = 0, ".$wpdb->prefix."posts.ID, ".$wpdb->prefix."posts.post_parent ) ORDER BY ".$wpdb->prefix."posts.menu_order, DATE(MIN( ".$wpdb->prefix."postmeta.meta_value)) ASC, TIME( ".$wpdb->prefix."postmeta.meta_value) ASC LIMIT 0, 1",$catid , $catid ,$start_date, $start_date ,$start_date );
							$resultsET = $wpdb->get_results( $sqlET );

							if (  !is_wp_error( $resultsET ) )
							 {

								$return = array();
								foreach ( $resultsET as $fivesdraft )
								{
									$return[] = $fivesdraft->ID;
								}
								return $return;
							}
							return array();

						///$numberofevents = new WP_Query($args);

						//print_r($numberofevents);
						//return $numberofevents->post_count;
}

function get_ih_venue_values() {
		/** @var wpdb $wpdb */
		global $wpdb;

		// get venue IDs associated with published posts
		$venue_ids = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT m.meta_value FROM {$wpdb->postmeta} m INNER JOIN {$wpdb->posts} p ON p.ID=m.post_id WHERE p.post_type=%s AND p.post_status='publish' AND m.meta_key='_EventVenueID' AND m.meta_value > 0", TribeEvents::POSTTYPE));
		$venue_ids = array_filter($venue_ids);
		if ( empty($venue_ids) ) {
			return array();
		}
		$venues = get_posts(array(
			'post_type' => TribeEvents::VENUE_POST_TYPE,
			'posts_per_page' => 200, // arbitrary limit
			'suppress_filters' => FALSE,
			'post__in' => $venue_ids,
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
		));

		$venues_array = array();
		foreach( $venues as $venue ) {
			$venues_array[$venue->ID] = array(
				'name' => $venue->post_title,
				'value' => $venue->ID,
			);
		}

		return $venues_array;
}



remove_all_actions( 'do_feed_rss2' );
add_action( 'do_feed_rss2', 'acme_product_feed_rss2', 10, 1 );

function acme_product_feed_rss2( $for_comments ) {
    $rss_template = get_template_directory() . '/feed-acme_product-rss2.php';
    //if( get_query_var( 'post_type' ) == 'blog' )
        load_template( $rss_template );
   // else
       // do_feed_rss2( $for_comments ) // Call default function
}


add_action('init', 'feed_removal');

function feed_removal() {
global $post;
if ( is_post_type_archive() != 'blog' || $post->post_type != 'market' || !is_singular('blog') )  {

remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', array( 'TribeiCal', 'set_feed_link'      ), 2,  0 );

  }
}


/* Fix Sub Child issue*/
//add_action( 'init', 'ih_app_detail_rewrite_rules' );
function ih_app_detail_rewrite_rules(){
   global $wp_rewrite;
   //add_rewrite_rule('^inboxmember_listhacking/market/(.*)/(.*)/?$','index.php?pagename=child1&market=parent','top');
   //add_rewrite_rule('^inboxmember_listhacking/market/(.*)/(.*)/?$','index.php?pagename=market/parent/child1','top');
   add_rewrite_rule('^market/(.*)/(.*)/?$','index.php?market=$matches[1]/$matches[2]','top');
   //add_rewrite_rule('^market/(.*)/(.*)/?$','index.php?market=metropolitan-atlanta/sample','top');
   //flush_rewrite_rules();
   //add_rewrite_rule('^inboxmember_listhacking/market/(.*)/(.*)/?$','^inboxmember_listhacking/index.php?pagename=$matches[2]','top');
   //echo "<pre>sadasd"; print_r($wp_rewrite);
	//echo "<pre>"; print_r($wp_query);

	//remove action of plugin custom-post-type-permalinks
}
//add_action( 'parse_request','ih_cptp_parse_request', 100 );
function ih_cptp_parse_request( $obj )
{
	if ( isset( $obj->query_vars[ 'market' ] ) ) {

		//if its child page
		if( isset($obj->request) && isset($obj->matched_rule) && $obj->matched_rule== "^market/(.*)/(.*)/?$")
		{
			$requestArray  = explode("/" , $obj->request);
			if (  count ($requestArray ) == 3 )
			{
				$marketarray = array( $requestArray[1], $requestArray[2]);
				$obj->query_vars[ 'market' ] = implode("/", $marketarray);
			}
		}
	}
}

//add_action( 'template_redirect', 'ih_awp_loaded' );
function ih_awp_loaded()
{global $wp, $template;
 //$wp->query_vars['market'] =  'metropolitan-atlanta/sample1';
 //  set_query_var('market', 'metropolitan-atlanta/sample1');
 echo "<pre>"; print_r( $wp);
 echo "<pre>"; print_r( $template);
 echo ' '."\r\n";
}

include_once("get_infowindow_content.php");
/*End Fix Sub Child issue*/

add_filter( 'aioseop_title', 'ih_blog_wp_title', 10 );
function ih_blog_wp_title( $title )
{
	global $wp_query;
	if( isset($wp_query) && isset( $wp_query->queried_object ) &&  isset( $wp_query->queried_object->query_var) &&
	$wp_query->queried_object->query_var == 'blog' )
	{
		$title = 'Information on the Home Rental Lifestyle | Invitation Homes Blog';
	}
	return $title;
}
include("filter-function.php");
//Event Location
register_taxonomy('event-locations',array (
  0 => 'tribe_events'
),array( 'hierarchical' => true, 'label' => 'Event Locations','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'Event Location') );