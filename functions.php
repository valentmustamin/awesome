<?php
//*-----------------------------------------------------------------------------------*/
/*	Start the engine
/*-----------------------------------------------------------------------------------*/

require_once( get_template_directory() . '/lib/init.php' );

// Setup Theme Settings
define( 'CHILD_THEME_NAME', __( 'Equilibre Theme', 'equilibre' ) );
define( 'CHILD_THEME_URL', 'http://demo.sensothemes.com/equilibre' );


/*-----------------------------------------------------------------------------------*/
/*Enqueue CSS
/*-----------------------------------------------------------------------------------*/

// Enqueue icon font 
function enqueue_css() {
	wp_enqueue_style( 'icons-font', get_stylesheet_directory_uri()  . '/lib/css/font-awesome.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_css' );


/*-----------------------------------------------------------------------------------*/
/*	Theme Support
/*-----------------------------------------------------------------------------------*/
//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

// Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

// Unregister secondary navigation menu
add_theme_support ( 'genesis-menus' , array ( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

// Remove Unused Page Layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );


/*-----------------------------------------------------------------------------------*/
/*	Theme Setup
/*-----------------------------------------------------------------------------------*/

// Remove Edit link
add_filter( 'genesis_edit_post_link', '__return_false' );

// Set Genesis Responsive Slider defaults
add_filter( 'genesis_responsive_slider_settings_defaults', 'diligent_responsive_slider_defaults' );
function diligent_responsive_slider_defaults( $defaults ) {
	$defaults['slideshow_height'] = '450';
	$defaults['slideshow_width'] = '1060';
	return $defaults;
}

// Reposition the breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs' );

add_filter('genesis_breadcrumb_args', 'equilibre_breadcrumb_args');
function equilibre_breadcrumb_args($args) {
	$args['labels']['prefix'] = ''; //marks the spot
	$args['sep'] = ' &raquo; ';
	return $args;
}

add_action( 'genesis_before', 'equilibre_switch_sidebars' );
function equilibre_switch_sidebars() {
	$site_layout = genesis_site_layout();
	if ( $site_layout == 'sidebar-content-sidebar' ) {
		remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
		add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar_alt' );
	}
}

// Customize the post info function
add_filter( 'genesis_post_info', 'equilibre_post_info_filter' );
function equilibre_post_info_filter($post_info) {
	if ( !is_page() ) {
		$post_info = '[post_date] [post_author_posts_link] [post_comments]';
		return $post_info;
	}}

// Customize the post meta function
add_filter( 'genesis_post_meta', 'equilibre_post_meta_filter' );
function equilibre_post_meta_filter($post_meta) {
	if (!is_page()) {
		$post_meta = '[post_categories before=""] [post_tags before=""]';
		return $post_meta;
	}}


// Display author box on single posts */
//add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );


/*-----------------------------------------------------------------------------------*/
/*	Sidebars & widget areas
/*-----------------------------------------------------------------------------------*/
	
genesis_register_sidebar( array(
	'id'			=> 'featured',
	'name'			=> __( 'Featured', 'equilibre' ),
	'description'	=> __( 'This is the featured section.', 'equilibre' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'welcome',
	'name'			=> __( 'Welcome', 'equilibre' ),
	'description'	=> __( 'This is the welcome section.', 'equilibre' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-1',
	'name'			=> __( 'Home Middle #1', 'equilibre' ),
	'description'	=> __( 'This is the first column of the home middle section.', 'equilibre' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-2',
	'name'			=> __( 'Home Middle #2', 'equilibre' ),
	'description'	=> __( 'This is the second column of the home middle section.', 'equilibre' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-3',
	'name'			=> __( 'Home Middle #3', 'equilibre' ),
	'description'	=> __( 'This is the third column of the home middle section.', 'equilibre' ),
) );



/** Remove Header */
//remove_action( 'genesis_header', 'genesis_do_header' );

/** Remove Title & Description */
//remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
//remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//Remove the header from normal location
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

// Move header into into content-sidebar-wrap
add_action( 'genesis_before_content', 'genesis_header_markup_open', 5 );
add_action( 'genesis_before_content', 'genesis_do_header' );
add_action( 'genesis_before_content', 'genesis_header_markup_close', 15 );

