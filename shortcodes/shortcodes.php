<?php
use Timber\Timber;
function render_html_plugin() {
	// Get Tags and count
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
    $context = Timber::get_context();
	$context['token'] = get_option('agentfire_token');
    $context['logo'] = plugins_url( '/agentfire-test/assets/images/AgentFire-Logo-2020-white.png' );
    $context['tags'] = $response;
	if (is_user_logged_in()) {
		return Timber::compile( WP_PLUGIN_DIR . '/agentfire-test/template/render-html.twig' , $context);
	}
	else {
		return Timber::compile( WP_PLUGIN_DIR . '/agentfire-test/template/render-guest-html.twig' , $context);
	}
}
add_shortcode('agentfire_test', 'render_html_plugin');