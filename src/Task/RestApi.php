<?php

declare( strict_types=1 );

namespace AgentFire\Plugin\Task;

use AgentFire\Plugin\Task\Traits\Singleton;

use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Rest
 * @package AgentFire\Plugin\Task
 */
class RestApi {
	use Singleton;

	/**
	 * @var string Endpoint namespace
	 */
	const NAMESPACE = 'api/v1';

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register endpoints
	 */
	public static function register_routes() {
		register_rest_route( self::NAMESPACE, '/getPoints', [
			'methods' => 'GET',
			'callback' => [ self::class, 'rest_api_callback' ],
		] );
		register_rest_route( self::NAMESPACE, '/getAccessToken', array(
			'methods' => 'GET',
			'callback' => [ self::class, 'get_access_token_callback' ],
		) );
		register_rest_route( self::NAMESPACE, '/createMark', array(
			'methods' => 'POST',
			'callback' => [ self::class, 'post_create_post_callback'],
		) );
		register_rest_route( self::NAMESPACE, '/tags-count', array(
			'methods' => 'GET',
			'callback' => [ self::class, 'tags_callback'],
		) );
		register_rest_route(self::NAMESPACE, '/marks-by-tag/(?P<tag>.+)', [
			'methods' => 'GET',
			'callback' => [ self::class, 'get_marks_by_tag'],
		]);
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public static function rest_api_callback( WP_REST_Request $request ){
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

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */

	public static function get_access_token_callback( WP_REST_Request $request ) {
		$data = get_option('agentfire_token');
		return new WP_REST_Response( [
			'status' => 200,
			'token' => $data ?: null
		]);
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public static function post_create_post_callback( WP_REST_Request $request  ) {
		$title = $request->get_param('title');
		$post_lng = $request->get_param('post_lng');
		$post_lat = $request->get_param('post_lat');
		$post_tag = $request->get_param('post_tag');

		// create a new post.
		$post_data = [
			'post_type' => 'marks',
			'post_title' => $title,
			'post_status' => 'publish',
			'post_category' => array(1)
		];
		$post_id = wp_insert_post($post_data);

		// Save post_lng and post_lat.
		if ($post_id) {
			if (!empty($post_tag)){
				$tags = array($post_tag);
			}
			else{
				$tags = array('default');
			}
			wp_set_post_tags($post_id, implode(',', $tags));
			update_post_meta($post_id, 'post_lng', $post_lng);
			update_post_meta($post_id, 'post_lat', $post_lat);
		}
		// return the new post ID
		if (isset($post_id)) {
			return new WP_REST_Response( [
				'status' => 200,
				'post_id' => $post_id
			]);
		}else{
			return new WP_REST_Response( [
				'status' => 200,
				'post_id' => null
			]);
		}
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public static function tags_callback( WP_REST_Request $request ) {
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
		return $response;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public static function get_marks_by_tag( WP_REST_Request $request ) {
		$tag  = $request['tag'];
		$query_args = [
			'post_type' => 'marks',
			'tag'       => $tag,
			'orderby'   => 'tag',
			'order'     => 'ASC',
		];
		$query      = new WP_Query( $query_args );
		$posts      = $query->posts;

		$data = [];
		foreach ( $posts as $post ) {
			$tags = get_the_tags( $post->ID );
			if ( isset( $tags ) ) {
				$tag_data = '';
				foreach ( $tags as $tag ) {
					$tag_data .= $tag->name . ' , ';
				}
			}

			$post_data = [
				'title'    => $post->post_title,
				'post_lat' => get_post_meta( $post->ID, 'post_lat', true ),
				'post_lng' => get_post_meta( $post->ID, 'post_lng', true ),
				'created'  => $post->post_date,
				'tags'     => $tag_data
			];
			$data[]    = $post_data;
		}

		return $data;
	}

}
