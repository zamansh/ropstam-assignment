<?php
/**
 * Small shared helpers. Keeps data access and formatting in one place so the
 * admin list, single view and shortcode always agree with each other.
 *
 * @package CustomProductAddon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read and normalise a service's custom fields.
 *
 * A service with no (or zero) base price is treated as unavailable, so the
 * front end can show a graceful "on request" state instead of a broken $0.00.
 *
 * @param int $post_id Service post ID.
 * @return array{base_price: float, delivery_days: int, is_available: bool}
 */
function cpa_get_service_data( $post_id ) {
	$price = get_post_meta( $post_id, 'base_price', true );
	$days  = get_post_meta( $post_id, 'delivery_time_days', true );

	$price = is_numeric( $price ) ? (float) $price : 0.0;
	$days  = is_numeric( $days ) ? absint( $days ) : 0;

	return array(
		'base_price'    => $price,
		'delivery_days' => $days,
		'is_available'  => $price > 0,
	);
}

/**
 * Format an amount as a USD price string (not escaped, escape on output).
 *
 * @param float|int|string $amount Amount.
 * @return string
 */
function cpa_format_price( $amount ) {
	return '$' . number_format_i18n( (float) $amount, 2 );
}

/**
 * Format a number of days (not escaped, escape on output).
 *
 * @param int $days Number of days.
 * @return string
 */
function cpa_format_days( $days ) {
	$days = absint( $days );

	/* translators: %d: number of days. */
	return sprintf( _n( '%d day', '%d days', $days, 'cpa-block' ), $days );
}
