<?php

declare( strict_types=1 );

namespace AgentFire\Plugin\Task;

use AgentFire\Plugin\Task\Traits\Singleton;

/**
 * Class ShortCode
 * @package AgentFire\Plugin\Task
 */
class ShortCodes {
	use Singleton;
	public function __construct() {
		add_shortcode( 'agentfire_test', [ $this, 'agentfire_test' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts'] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles']);
	}

	/*
	 * Function to render content shortcode.
	 */
	public function agentfire_test() {
		$tags = get_tags([
			'orderby' => 'count',
			'order' => 'DESC',
			'hide_empty' => false,
		]);
		$response = array_map(function($tag) {
			return [
				'name' => $tag->name,
				'slug' => $tag->slug,
				'count' => $tag->count,
			];
		}, $tags);
		$context['logged'] = is_user_logged_in() ? '#newmark' : '';
		$context['token'] = get_option('agentfire_token');
		$context['logo'] = plugins_url( '/agentfire-test/assets/images/AgentFire-Logo-2020-white.png' );
		$context['tags'] = $response;
		Template::getInstance()->display( 'render-html.twig', $context );
	}

	/*
	 * Enqueue styles.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ). '../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css');//plugins_url( 'vendor/twbs/bootstrap/dist/css/bootstrap.min.css', __FILE__ ) , false, '1.0', 'all' ); // Inside a parent theme
		wp_enqueue_style( 'agentfire-css', plugin_dir_url( __FILE__ ). '../../assets/css/agentfire.css');
		wp_enqueue_style( 'mapbox-css', 'https://api.mapbox.com/mapbox-gl-js/v2.14.0/mapbox-gl.css');
	}

	/*
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ). '../../vendor/twbs/bootstrap/dist/js/bootstrap.min.js',   array('jquery'), '5.3', true );
		wp_enqueue_script( 'agentfire-js', plugin_dir_url( __FILE__ ). '../../assets/js/agentfire-admin.js', array(), '1.0.0', true );
		wp_enqueue_script( 'mapbox-js', 'https://api.mapbox.com/mapbox-gl-js/v2.14.0/mapbox-gl.js', array(), '1.0.0', true );
	}
}
