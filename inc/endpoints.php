<?php
/**
 * Register endpoints
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
add_action( 'rest_api_init', function () {
    
    // Router
    register_rest_route( 'router', '/pages', array(
        array(
            'methods'               => WP_REST_Server::READABLE,
            'callback'              => 'router_pages_handler',
            'permission_callback'   => '__return_true',          
        )
    ) );

});