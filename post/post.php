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


// Create custom fields to marks post.
function add_post_meta_box_marsk() {
	add_meta_box(
		'post_lat',
		__( 'Latitude', 'textdomain' ),
		'post_latitude_box_callback',
		'marks'
	);
	add_meta_box(
		'post_lng',
		__( 'Longitude', 'textdomain' ),
		'post_longitude_box_callback',
		'marks'
	);
}
add_action( 'add_meta_boxes', 'add_post_meta_box_marsk' );

// Callback latitude field.
function post_latitude_box_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'post_lat_nonce' );

	$value = get_post_meta( $post->ID, 'post_lat', true );
	echo '<input type="text" id="post_lat_nonce" name="post_lat" value="' . esc_attr( $value ) . '">';
}

function post_longitude_box_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'post_lng_nonce' );

	$value = get_post_meta( $post->ID, 'post_lng', true );
	echo '<input type="text" id="post_lng" name="post_lng" value="' . esc_attr( $value ) . '">';
}

// Save fields marks.
function save_post_field_marks( $post_id ) {
	if ( ! isset( $_POST['post_lat_nonce'] ) || ! wp_verify_nonce( $_POST['post_lat_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	if ( ! isset( $_POST['post_lng_nonce'] ) || ! wp_verify_nonce( $_POST['post_lng_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save the custom fields
	if ( isset( $_POST['post_lat'] ) ) {
		update_post_meta( $post_id, 'post_lat', sanitize_text_field( $_POST['post_lat'] ) );
	}
	if ( isset( $_POST['post_lng'] ) ) {
		update_post_meta( $post_id, 'post_lng', sanitize_text_field( $_POST['post_lng'] ) );
	}
}
add_action( 'save_post', 'save_post_field_marks' );
