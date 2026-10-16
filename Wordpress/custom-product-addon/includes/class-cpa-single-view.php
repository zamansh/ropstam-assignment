<?php
/**
 * Shows Base Price and Delivery Time on a single Service page.
 *
 * @package CustomProductAddon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPA_Single_View {

	public function __construct() {
		add_filter( 'the_content', array( $this, 'append_details' ) );
	}

	/**
	 * Append the details block after the main content of a single service.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function append_details( $content ) {
		if ( ! is_singular( 'service' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$data = cpa_get_service_data( get_the_ID() );

		ob_start();
		?>
		<aside class="cpa-single-details" aria-label="<?php esc_attr_e( 'Service details', 'cpa-block' ); ?>">
			<dl class="cpa-facts">
				<div class="cpa-facts__item">
					<dt><?php esc_html_e( 'Base price', 'cpa-block' ); ?></dt>
					<dd>
						<?php
						echo $data['is_available']
							? esc_html( cpa_format_price( $data['base_price'] ) )
							: esc_html__( 'On request', 'cpa-block' );
						?>
					</dd>
				</div>
				<div class="cpa-facts__item">
					<dt><?php esc_html_e( 'Delivery', 'cpa-block' ); ?></dt>
					<dd>
						<?php
						echo $data['delivery_days']
							? esc_html( cpa_format_days( $data['delivery_days'] ) )
							: esc_html__( 'To be confirmed', 'cpa-block' );
						?>
					</dd>
				</div>
			</dl>
		</aside>
		<?php
		return $content . ob_get_clean();
	}
}
