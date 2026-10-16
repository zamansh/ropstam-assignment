<?php
/**
 * Template: one service card.
 *
 * @var array   $service Service data (id, title, url, excerpt, thumbnail, base_price, delivery_days, is_available).
 * @var array[] $addons  Optional add-ons (id, label, price).
 *
 * @package CustomProductAddon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cpa_title_id  = 'cpa-title-' . $service['id'];
$cpa_total_id  = 'cpa-total-' . $service['id'];
$cpa_available = $service['is_available'];
?>
<article
	class="cpa-card<?php echo $cpa_available ? '' : ' cpa-card--unavailable'; ?>"
	aria-labelledby="<?php echo esc_attr( $cpa_title_id ); ?>"
	data-available="<?php echo $cpa_available ? '1' : '0'; ?>"
	data-base-price="<?php echo esc_attr( $service['base_price'] ); ?>"
>
	<?php if ( $service['thumbnail'] ) : ?>
		<div class="cpa-card__media">
			<?php echo wp_kses_post( $service['thumbnail'] ); ?>
		</div>
	<?php endif; ?>

	<div class="cpa-card__body">
		<h3 class="cpa-card__title" id="<?php echo esc_attr( $cpa_title_id ); ?>">
			<a href="<?php echo esc_url( $service['url'] ); ?>"><?php echo esc_html( $service['title'] ); ?></a>
		</h3>

		<?php if ( $service['excerpt'] ) : ?>
			<p class="cpa-card__excerpt"><?php echo esc_html( $service['excerpt'] ); ?></p>
		<?php endif; ?>

		<dl class="cpa-facts">
			<div class="cpa-facts__item">
				<dt><?php esc_html_e( 'Base price', 'cpa-block' ); ?></dt>
				<dd>
					<?php
					echo $cpa_available
						? esc_html( cpa_format_price( $service['base_price'] ) )
						: esc_html__( 'On request', 'cpa-block' );
					?>
				</dd>
			</div>
			<div class="cpa-facts__item">
				<dt><?php esc_html_e( 'Delivery', 'cpa-block' ); ?></dt>
				<dd>
					<?php
					echo $service['delivery_days']
						? esc_html( cpa_format_days( $service['delivery_days'] ) )
						: esc_html__( 'To be confirmed', 'cpa-block' );
					?>
				</dd>
			</div>
		</dl>

		<?php if ( $cpa_available && $addons ) : ?>
			<fieldset class="cpa-addons">
				<legend class="cpa-addons__legend"><?php esc_html_e( 'Optional add-ons', 'cpa-block' ); ?></legend>

				<?php foreach ( $addons as $cpa_addon ) : ?>
					<?php $cpa_input_id = 'cpa-addon-' . $service['id'] . '-' . $cpa_addon['id']; ?>
					<label class="cpa-switch" for="<?php echo esc_attr( $cpa_input_id ); ?>">
						<?php /* Native checkbox + role="switch": keyboard (Space) and aria-checked come for free. */ ?>
						<input
							type="checkbox"
							role="switch"
							class="cpa-switch__input"
							id="<?php echo esc_attr( $cpa_input_id ); ?>"
							data-addon-price="<?php echo esc_attr( $cpa_addon['price'] ); ?>"
						/>
						<span class="cpa-switch__track" aria-hidden="true"></span>
						<span class="cpa-switch__text">
							<span class="cpa-switch__label"><?php echo esc_html( $cpa_addon['label'] ); ?></span>
							<span class="cpa-switch__price">+<?php echo esc_html( cpa_format_price( $cpa_addon['price'] ) ); ?></span>
						</span>
					</label>
				<?php endforeach; ?>
			</fieldset>
		<?php elseif ( ! $cpa_available ) : ?>
			<p class="cpa-card__notice" role="note">
				<?php esc_html_e( 'Currently unavailable. Contact us for a custom quote.', 'cpa-block' ); ?>
			</p>
		<?php endif; ?>
	</div>

	<?php if ( $cpa_available ) : ?>
		<footer class="cpa-card__footer">
			<span class="cpa-total__label" id="<?php echo esc_attr( $cpa_total_id ); ?>-label"><?php esc_html_e( 'Estimated total', 'cpa-block' ); ?></span>
			<?php /* <output> is a live region: screen readers announce price changes politely. */ ?>
			<output class="cpa-total__value" id="<?php echo esc_attr( $cpa_total_id ); ?>" aria-labelledby="<?php echo esc_attr( $cpa_total_id ); ?>-label" aria-live="polite" aria-atomic="true"><?php echo esc_html( cpa_format_price( $service['base_price'] ) ); ?></output>
		</footer>
	<?php endif; ?>
</article>
