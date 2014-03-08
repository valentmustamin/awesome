<?php

add_action( 'genesis_meta', 'equilibre_home_genesis_meta' );

/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function equilibre_home_genesis_meta() {

	if ( is_active_sidebar( 'featured' ) || is_active_sidebar( 'welcome' ) || is_active_sidebar( 'home-middle-1' ) || is_active_sidebar( 'home-middle-2' ) || is_active_sidebar( 'home-middle-3' ) ) {

		remove_action( 'genesis_loop', 'genesis_do_loop' );
		add_action( 'genesis_loop', 'equilibre_home_featured',5 );
		add_action( 'genesis_loop', 'equilibre_home_loop_helper',10 );
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
		add_filter( 'body_class', 'add_body_class' );

		function add_body_class( $classes ) {
   			$classes[] = 'equilibre';
  			return $classes;
		}
	}
}


function equilibre_home_featured() {

	if ( is_active_sidebar( 'featured' ) ) {
		echo '<div id="home-featured"><div class="wrap">';
		dynamic_sidebar( 'featured' );
		echo '</div></div><!-- end .featured -->';
	}
	
}
	
function equilibre_home_loop_helper() {
	
	if ( is_active_sidebar( 'welcome' ) ) {
			echo '<div id="home-welcome">';
			dynamic_sidebar( 'welcome' );
			echo '</div><!-- end .welcome -->';
		}
		
	echo '<div id="home-middle">';
	
	if ( is_active_sidebar( 'home-middle-1' ) ) {
		echo '<div class="one-third first">';
		dynamic_sidebar( 'home-middle-1' );
		echo '</div><!-- end .home-middle-1 -->';
	}
	
	if ( is_active_sidebar( 'home-middle-2' ) ) {
		echo '<div class="one-third">';
		dynamic_sidebar( 'home-middle-2' );
		echo '</div><!-- end .home-middle-2 -->';
	}
	
	if ( is_active_sidebar( 'home-middle-3' ) ) {
		echo '<div class="one-third">';
		dynamic_sidebar( 'home-middle-3' );
		echo '</div><!-- end .home-middle-3 -->';
	}
	
	echo '</div><!-- end .home-middle -->';
	
}


genesis();