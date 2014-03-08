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







// Force sidebar-content-sidebar layout setting
add_filter( 'genesis_site_layout', '__genesis_return_sidebar_content' );
 
// Unregister layout settings
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'content-sidebar' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'full-width-content' );
 
//Remove the header from normal location
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
 
// Move header into into content-sidebar-wrap
add_action( 'genesis_before_sidebar_widget_area', 'genesis_header_markup_open', 5 );
add_action( 'genesis_before_sidebar_widget_area', 'genesis_do_header' );
add_action( 'genesis_before_sidebar_widget_area', 'genesis_header_markup_close', 15 );

//Remove the footer from normal location
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
 
// Move footer into content-sidebar-wrap
add_action( 'genesis_after_sidebar_widget_area', 'genesis_footer_markup_open', 5 );
add_action( 'genesis_after_sidebar_widget_area', 'genesis_do_footer' );
add_action( 'genesis_after_sidebar_widget_area', 'genesis_footer_markup_close', 15 );








/*
To add Infinite Loop Module provided in Jetpack wordpress Plugin, in Genesis driven site.
*/

/*
Step 1. Install Jetpack Plugin
*/

/*
Step 2. Make your theme ready for Infinite Scroll

We will need to put an 'ID' inside the main posts container which by default in HTML5 contains only class.
Thanks to @GaryJ (Gary Jones), you can do that by filtering genesis_attr_content as shown below.
*/

add_filter( 'genesis_attr_content', 'custom_attributes_content' );

function custom_attributes_content( $attributes ) {
  $attributes['id']     = 'main-content';
  return $attributes;
}

/*
Step 3. Make your theme compatible for Infinite Scroll

We will put the following code, to show the Infinite Scroll module about how and which element to repeat, 
depending on what user interaction and to repeat what chunk of code while repeating the element.
*/

function custom_infinite_scroll() {

  add_theme_support( 'infinite-scroll', array(
      'container'  => 'main-content',
      'footer'     => false,
      'render'    => 'genesis_do_loop',
  ) );
}

add_action( 'after_setup_theme', 'custom_infinite_scroll' );

/*
Refer to http://jetpack.me/support/infinite-scroll/ for the description on attributes that we provided here. 
The main catch is 'container' which tells Infinite Loop to repeat 'ID' which is 'main-content-area'; 'type' - which tells 
Infinite loop that it should work when user clicks a load more (or Older Posts) button and 'render' where we proud genesis
users tell the Infinite Loop to use 'genesis_do_loop' to load more posts.
*/

/*
Step 4. Save the functions.php and refresh your site, if you still don't see the Infinite Loop in action,
then go to dashboard -> Jetpack and look for Infinite Loop module and activate it.
You will now have a working Infinite Loop in action!
*/
