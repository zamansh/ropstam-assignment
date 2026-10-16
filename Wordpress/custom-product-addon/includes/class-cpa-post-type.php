<?php
/**
 * Registers the "Services" custom post type.
 *
 * @package CustomProductAddon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPA_Post_Type {

	const POST_TYPE = 'service';

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Register the "service" post type.
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => __( 'Services', 'cpa-block' ),
			'singular_name'      => __( 'Service', 'cpa-block' ),
			'menu_name'          => __( 'Services', 'cpa-block' ),
			'add_new'            => __( 'Add New', 'cpa-block' ),
			'add_new_item'       => __( 'Add New Service', 'cpa-block' ),
			'edit_item'          => __( 'Edit Service', 'cpa-block' ),
			'new_item'           => __( 'New Service', 'cpa-block' ),
			'view_item'          => __( 'View Service', 'cpa-block' ),
			'search_items'       => __( 'Search Services', 'cpa-block' ),
			'not_found'          => __( 'No services found', 'cpa-block' ),
			'not_found_in_trash' => __( 'No services found in Trash', 'cpa-block' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => $labels,
				'public'       => true,
				'show_in_rest' => true, // Block editor + REST API support.
				'menu_icon'    => 'dashicons-admin-tools',
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'services' ),
				'menu_position' => 20,
			)
		);
	}
}
