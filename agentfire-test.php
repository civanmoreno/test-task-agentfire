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



