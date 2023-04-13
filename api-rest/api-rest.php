<?php
// Create rest api to get points mark.
function agentfire_test_rest_api_init() {
	register_rest_route( 'api/v1', '/getPoints', array(
		'methods' => 'GET',
		'callback' => 'agentfire_test_rest_api_callback'
	) );
}
add_action( 'rest_api_init', 'agentfire_test_rest_api_init' );

// Callback return marks post type.
function agentfire_test_rest_api_callback( $request ){
	$args = [
		'post_type' => 'marks',
		'post_status' => 'publish',
		'posts_per_page' => -1
	];

	$query = new WP_Query($args);
	$posts = $query->get_posts();

	$data = [];
	foreach ($posts as $post) {
		$post_data = [
			'title' => $post->post_title,
			'post_lat' => get_post_meta($post->ID, 'post_lat', true),
			'post_lng' => get_post_meta($post->ID, 'post_lng', true),
		];
		$data[] = $post_data;
	}
	if (!empty($data)) {
		return new WP_REST_Response( $data );
	}
	else {
		return new WP_REST_Response( ['status' => ''] );
	}

}