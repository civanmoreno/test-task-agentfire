<?php
// Create post type marks
function create_marks_post_type() {
	register_post_type( 'marks',
		array(
			'labels' => array(
				'name' => __( 'Marks' ),
				'singular_name' => __( 'Mark' )
			),
			'public' => true,
			'menu_icon' => 'dashicons-admin-post',
			'supports' => array('title'),
		)
	);
}
add_action( 'init', 'create_marks_post_type' );