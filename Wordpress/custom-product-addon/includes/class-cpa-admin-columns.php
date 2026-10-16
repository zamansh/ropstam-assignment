<?php
/**
 * Adds Base Price and Delivery Time columns to Dashboard > Services.
 *
 * @package CustomProductAddon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPA_Admin_Columns {

	public function __construct() {
		add_filter( 'manage_service_posts_columns', array( $this, 'add_columns' ) );
		add_action( 'manage_service_posts_custom_column', array( $this, 'render_column' ), 10, 2 );
	}

	/**
	 * Insert the new columns right after the title.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_columns( $columns ) {
		$updated = array();

		foreach ( $columns as $key => $label ) {
			$updated[ $key ] = $label;

			if ( 'title' === $key ) {
				$updated['cpa_base_price']    = __( 'Base Price', 'cpa-block' );
				$updated['cpa_delivery_days'] = __( 'Delivery Time', 'cpa-block' );
			}
		}

		return $updated;
	}

	/**
	 * Print a column cell.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	public function render_column( $column, $post_id ) {
		if ( 'cpa_base_price' !== $column && 'cpa_delivery_days' !== $column ) {
			return;
		}

		$data = cpa_get_service_data( $post_id );

		if ( 'cpa_base_price' === $column ) {
			echo $data['is_available'] ? esc_html( cpa_format_price( $data['base_price'] ) ) : '&mdash;';
			return;
		}

		echo $data['delivery_days'] ? esc_html( cpa_format_days( $data['delivery_days'] ) ) : '&mdash;';
	}
}
