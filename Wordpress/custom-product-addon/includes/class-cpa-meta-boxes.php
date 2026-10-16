<?php
/**
 * Custom meta fields for Services: base_price and delivery_time_days.
 *
 * Registered with register_post_meta() (REST-exposed + sanitized) and edited
 * through a classic meta box with nonce + capability checks.
 *
 * @package CustomProductAddon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPA_Meta_Boxes {

	public function __construct() {
		add_action( 'init', array( $this, 'register_meta_fields' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_service', array( $this, 'save_meta' ) );
	}

	/**
	 * Register both fields so they are sanitized everywhere (admin, REST, code).
	 */
	public function register_meta_fields() {
		$auth = function () {
			return current_user_can( 'edit_posts' );
		};

		register_post_meta(
			'service',
			'base_price',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'number',
				'sanitize_callback' => array( $this, 'sanitize_price' ),
				'auth_callback'     => $auth,
			)
		);

		register_post_meta(
			'service',
			'delivery_time_days',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'auth_callback'     => $auth,
			)
		);
	}

	/**
	 * Price must be a non-negative number with 2 decimals.
	 *
	 * @param mixed $value Raw value.
	 * @return float
	 */
	public function sanitize_price( $value ) {
		$value = is_numeric( $value ) ? (float) $value : 0.0;
		return $value < 0 ? 0.0 : round( $value, 2 );
	}

	/**
	 * Add the "Service Details" meta box.
	 */
	public function add_meta_box() {
		add_meta_box(
			'cpa_service_details',
			__( 'Service Details', 'cpa-block' ),
			array( $this, 'render_meta_box' ),
			'service',
			'normal',
			'high'
		);
	}

	/**
	 * Render the meta box.
	 *
	 * @param WP_Post $post Current post.
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( 'cpa_save_service_meta', 'cpa_service_meta_nonce' );

		$base_price = get_post_meta( $post->ID, 'base_price', true );
		$days       = get_post_meta( $post->ID, 'delivery_time_days', true );
		?>
		<p>
			<label for="cpa_base_price"><strong><?php esc_html_e( 'Base Price ($)', 'cpa-block' ); ?></strong></label><br>
			<input type="number" step="0.01" min="0" id="cpa_base_price" name="cpa_base_price"
				value="<?php echo esc_attr( $base_price ); ?>" class="regular-text" />
		</p>
		<p>
			<label for="cpa_delivery_time_days"><strong><?php esc_html_e( 'Delivery Time (days)', 'cpa-block' ); ?></strong></label><br>
			<input type="number" step="1" min="0" id="cpa_delivery_time_days" name="cpa_delivery_time_days"
				value="<?php echo esc_attr( $days ); ?>" class="regular-text" />
		</p>
		<?php
	}

	/**
	 * Save the fields (nonce, autosave and capability checks, then sanitize).
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta( $post_id ) {
		if ( ! isset( $_POST['cpa_service_meta_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpa_service_meta_nonce'] ) ), 'cpa_save_service_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['cpa_base_price'] ) ) {
			update_post_meta( $post_id, 'base_price', $this->sanitize_price( wp_unslash( $_POST['cpa_base_price'] ) ) );
		}

		if ( isset( $_POST['cpa_delivery_time_days'] ) ) {
			update_post_meta( $post_id, 'delivery_time_days', absint( wp_unslash( $_POST['cpa_delivery_time_days'] ) ) );
		}
	}
}
