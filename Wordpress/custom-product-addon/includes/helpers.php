<?php
/**
 * Small shared helpers. Keeps data access and formatting in one place so the
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


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

function cpa_format_price( $amount ) {
	return '$' . number_format_i18n( (float) $amount, 2 );
}


function cpa_format_days( $days ) {
	$days = absint( $days );

	
	return sprintf( _n( '%d day', '%d days', $days, 'cpa-block' ), $days );
}
