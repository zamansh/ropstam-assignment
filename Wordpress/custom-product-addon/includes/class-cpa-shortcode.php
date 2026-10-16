<?php
/**
 * [cpa_recent_services] shortcode.
 *
 * - Fetches the 3 most recently UPDATED services with WP_Query (no raw SQL).
 * - Renders each card from templates/service-card.php (everything escaped).
 * - Enqueues the calculator assets through wp_enqueue_* only.
 *
 * @package CustomProductAddon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPA_Shortcode {

	const TAG = 'cpa_recent_services';

	public function __construct() {
		add_shortcode( self::TAG, array( $this, 'render' ) );
	}

	/**
	 * The 3 most recently updated, published services.
	 *
	 * @return WP_Query
	 */
	private function get_recent_services() {
		return new WP_Query(
			array(
				'post_type'              => 'service',
				'post_status'            => 'publish',
				'posts_per_page'         => 3,
				'orderby'                => 'modified',
				'order'                  => 'DESC',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,  // Skip the pagination count query.
				'update_post_term_cache' => false, // We do not use terms here.
			)
		);
	}

	/**
	 * Optional add-ons. Only "Priority Turnaround" ships by default; other
	 * plugins or themes can extend the list with the `cpa_addons` filter.
	 *
	 * @return array[] Each item: id (string), label (string), price (float).
	 */
	private function get_addons() {
		$addons = apply_filters(
			'cpa_addons',
			array(
				array(
					'id'    => 'priority-turnaround',
					'label' => __( 'Priority Turnaround', 'cpa-block' ),
					'price' => 50.00,
				),
			)
		);

		$clean = array();
		foreach ( (array) $addons as $addon ) {
			if ( empty( $addon['id'] ) || empty( $addon['label'] ) || ! isset( $addon['price'] ) ) {
				continue;
			}

			$clean[] = array(
				'id'    => sanitize_key( $addon['id'] ),
				'label' => sanitize_text_field( $addon['label'] ),
				'price' => max( 0, (float) $addon['price'] ),
			);
		}

		return $clean;
	}

	/**
	 * Shortcode callback.
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			array( 'title' => __( 'Latest Services', 'cpa-block' ) ),
			$atts,
			self::TAG
		);

		$query = $this->get_recent_services();

		// Empty state: nothing published yet.
		if ( ! $query->have_posts() ) {
			return '<p class="cpa-services__empty" role="status">' . esc_html__( 'No services are available right now.', 'cpa-block' ) . '</p>';
		}

		// Fallback enqueue (page builders do not put the shortcode in post_content).
		wp_enqueue_style( CPA_Assets::STYLE_HANDLE );
		wp_enqueue_script( CPA_Assets::SCRIPT_HANDLE );

		$addons   = $this->get_addons();
		$heading  = wp_unique_id( 'cpa-services-heading-' );
		$services = array();

		while ( $query->have_posts() ) {
			$query->the_post();
			$id = get_the_ID();

			$services[] = array_merge(
				cpa_get_service_data( $id ),
				array(
					'id'        => $id,
					'title'     => get_the_title(),
					'url'       => get_permalink(),
					'excerpt'   => wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 24 ),
					// Decorative image (the title sits right next to it), so empty alt.
					'thumbnail' => get_the_post_thumbnail(
						$id,
						'medium_large',
						array(
							'class'   => 'cpa-card__img',
							'loading' => 'lazy',
							'alt'     => '',
						)
					),
				)
			);
		}
		wp_reset_postdata();

		ob_start();
		?>
		<section class="cpa-services" aria-labelledby="<?php echo esc_attr( $heading ); ?>">
			<h2 class="cpa-services__title" id="<?php echo esc_attr( $heading ); ?>">
				<?php echo esc_html( sanitize_text_field( $atts['title'] ) ); ?>
			</h2>
			<div class="cpa-services__grid">
				<?php
				foreach ( $services as $service ) {
					$this->render_card( $service, $addons );
				}
				?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render one card. Kept in its own method so template variables stay scoped.
	 *
	 * @param array   $service Service data.
	 * @param array[] $addons  Add-ons.
	 */
	private function render_card( $service, $addons ) {
		include CPA_PLUGIN_DIR . 'templates/service-card.php';
	}
}
