<?php
/**
 * Registers and enqueee the front-end CSS and JS.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPA_Assets {

	const STYLE_HANDLE  = 'cpa-calculator-style';
	const SCRIPT_HANDLE = 'cpa-calculator-script';

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_early' ), 20 );
	}

	public function register_assets() {
		wp_register_style(
			self::STYLE_HANDLE,
			CPA_PLUGIN_URL . 'assets/css/calculator.css',
			array(), 
			CPA_VERSION
		);

		wp_register_script(
			self::SCRIPT_HANDLE,
			CPA_PLUGIN_URL . 'assets/js/calculator.js',
			array(), 
			CPA_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer', 
			)
		);
	}

	public function maybe_enqueue_early() {
		if ( is_singular( 'service' ) ) {
			wp_enqueue_style( self::STYLE_HANDLE );
			return;
		}

		if ( is_singular() ) {
			$post = get_post();
			if ( $post && has_shortcode( $post->post_content, 'cpa_recent_services' ) ) {
				wp_enqueue_style( self::STYLE_HANDLE );
				wp_enqueue_script( self::SCRIPT_HANDLE );
			}
		}
	}
}
