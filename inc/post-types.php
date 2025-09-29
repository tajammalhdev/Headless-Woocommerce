<?php 
/**
 * Register post types
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Post News
 */

function news_post_type() {

    register_post_type('news',
        array(
            'labels'      => array(
                'name'          => __("News"),
                'singular_name' => __("News"),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'has_archive'        => false,
            'show_in_rest'       => false,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => 'news' ),
            'menu_icon'          => 'dashicons-media-document',
            'supports'           => array( 'title' )
        )
    );
}
add_action('init', 'news_post_type');
