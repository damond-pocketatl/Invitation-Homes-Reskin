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
define('blog_page_id' , '124');
define('features' , '7');



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
add_action('init', 'testimonial_register');
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
        'taxonomies' => array('category'),
        'supports' => array('title','editor','thumbnail')
    );
    register_post_type( 'blog' , $args );
}
add_action( 'init', 'ih_register_taxonomy_for_object_type' );
function ih_register_taxonomy_for_object_type() {
    register_taxonomy_for_object_type( 'post_tag', 'blog' );
};


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
        'name' => _x('Team Members', 'team'),
		'description' => _x('Team members.','default'),
        'singular_name' => _x('Team', 'team'),
        'add_new' => _x('Add New', 'team'),
        'add_new_item' => __('Add New Team'),
        'edit_item' => __('Edit Team'),
        'new_item' => __('New Team'),
        'view_item' => __('View Team'),
        'search_items' => __('Search Team'),
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