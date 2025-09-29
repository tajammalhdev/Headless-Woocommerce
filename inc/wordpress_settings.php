<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme support
 * https://developer.wordpress.org/reference/functions/add_theme_support/
 */
add_theme_support( 'custom-logo' );
add_theme_support( 'post-thumbnails' );


/**
 * Register Navigation Menus
 * https://developer.wordpress.org/reference/functions/register_nav_menus/
 */
function ditto_navigation_menus() {
  $locations = array(
    'main_menu' => __( 'Main Menu', 'text_domain' )
  );
  register_nav_menus( $locations );
}
add_action( 'init', 'ditto_navigation_menus' );



add_action( 'graphql_register_types', function() {
  register_graphql_field( 'RootQuery', 'siteLogo', [
    'type' => 'MediaItem',
    'description' => 'Get site custom logo',
    'resolve' => function( $root, $args, $context ) { 
      $logo_id = get_theme_mod( 'custom_logo' );
      if ( empty( $logo_id ) ) {
        return null;
      }

      return \WPGraphQL\Data\DataSource::resolve_post_object( $logo_id, $context ); 
    }
  ] );
});

