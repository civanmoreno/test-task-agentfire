<?php

/**
 * Area Test
 *
 * @link https://agentfire.com
 * @since 1.0.0
 * @package AgentFire\Plugin\Test
 *
 * @wordpress-plugin
 * Plugin Name: AgentFire Test
 * Description: Area Test plugin
 * Plugin URI: https://agentfire.com
 * Version: 1.0.0
 * Author: Author Name
 * License: Proprietary
 * Network: false
 */

define( 'AGENTFIRE_TEST_VERSION', '1.0.0' );
define( 'AGENTFIRE_TEST_PATH', plugin_dir_path( __FILE__ ) );

require AGENTFIRE_TEST_PATH . 'vendor/autoload.php';

// Include post to create post type.
include( plugin_dir_path( __FILE__ ) . 'post/post.php');

// Include file api.
include( plugin_dir_path( __FILE__ ) . 'api-rest/api-rest.php');

// Including shortcodes.
include( plugin_dir_path( __FILE__ ) . 'shortcodes/shortcodes.php');

// Including Settings Page Config.
include( plugin_dir_path( __FILE__ ) . 'admin/admin.php');

// Add bootstrap and custom css.
function agentfire_test_enqueue_styles() {
    wp_enqueue_style( 'bootstrap', plugins_url( 'vendor/twbs/bootstrap/dist/css/bootstrap.min.css', __FILE__ ) , false, '1.0', 'all' ); // Inside a parent theme
    wp_enqueue_style( 'agentfire-css', plugins_url('/assets/css/agentfire.css', __FILE__ ));
    wp_enqueue_style( 'mapbox-css', 'https://api.mapbox.com/mapbox-gl-js/v2.14.0/mapbox-gl.css');
}

// Add boostrap js.
function agentfire_test_enqueue_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-js', plugins_url('vendor/twbs/bootstrap/dist/js/bootstrap.min.js', __FILE__),  array('jquery'), '5.3', true );

	if (is_user_logged_in()) {
		wp_enqueue_script( 'agentfire-js', plugins_url('/assets/js/agentfire-admin.js', __FILE__), array(), '1.0.0', true );
	}else{
		wp_enqueue_script( 'agentfire-js', plugins_url('/assets/js/agentfire-guest.js', __FILE__), array(), '1.0.0', true );
	}

    wp_enqueue_script( 'mapbox-js', 'https://api.mapbox.com/mapbox-gl-js/v2.14.0/mapbox-gl.js', array(), '1.0.0', true );

}

// Add actions to enqueue scripst
add_action( 'wp_enqueue_scripts', 'agentfire_test_enqueue_scripts', 20);
add_action( 'wp_enqueue_scripts', 'agentfire_test_enqueue_styles', 20);