<?php
/**
 * Register endpoints
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
add_action( 'rest_api_init', function () {
    
     // Main Menu
    register_rest_route( 'navigation', '/main_menu', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'main_menu_handler',
            'permission_callback'   => '__return_true',          
        )
    ) );

    // Router
    register_rest_route( 'router', '/pages', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'router_pages_handler',
            'permission_callback'   => '__return_true',          
        )
    ) );

});



function main_menu_handler() {
    $output = [];
    $child_items = [];
    $menu = wp_get_nav_menu_items(get_nav_menu_locations()['main_menu'], null);

    $frontpage_id = get_option( 'page_on_front' );
    $output["has_logo"] = has_custom_logo();

    if($output["has_logo"]) {
        $output["logo"] = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) , 'full' )[0];
    } else {
        $output["logo"] = get_bloginfo( 'name' );
    }
    
    if($menu) {
        foreach ($menu as $key => $item) {
            if ($item->menu_item_parent) {
                array_push($child_items, $item);
                unset($menu[$key]);
            }
        }
        
        foreach ($menu as $item) {
            foreach ($child_items as $key => $child) {
                if ($child->menu_item_parent == $item->ID) {
                    
                    if (!$item->child_items) {
                        $item->child_items = [];
                    }

                    array_push($item->child_items, $child);
                    unset($child_items[$key]);
                }
            }
        }
    }
    
    if($menu){
        
        foreach ($menu as $key => $item) {
            $path = ($frontpage_id == $item->object_id) ? '/' : '/'.get_post_field( 'post_name', $item->object_id );
            $output["menu"][] = [
                "ID"        => $item->ID,
                "title"     => $item->title,
                "url"       => $item->url,
                "slug"      => get_post_field( 'post_name', $item->object_id ),
                "path"      => $path,
                "page_id"   => $item->object_id,
                "child_items"   => $item->child_items,
                "parent"    => $item->post_parent, 
                "classes"   => $item->classes
            ];
        }
    }

    return $output;
}



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