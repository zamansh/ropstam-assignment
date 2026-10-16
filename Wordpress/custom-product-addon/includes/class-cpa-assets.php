<?php
/**
 * Registers (and conditionally enqueues) the front-end CSS and JS.
 *
 * Everything goes through wp_register_* / wp_enqueue_* only. Assets are
 * loaded on pages that actually need them, never site-wide.
 *
 * @package CustomProductAddon
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

	/**
	 * Register only; nothing is printed until something enqueues it.
	 */
	public function register_assets() {
		wp_register_style(
			self::STYLE_HANDLE,
			CPA_PLUGIN_URL . 'assets/css/calculator.css',
			array(), // Dependencies: none, the stylesheet is self-contained.
			CPA_VERSION
		);

		wp_register_script(
			self::SCRIPT_HANDLE,
			CPA_PLUGIN_URL . 'assets/js/calculator.js',
			array(), // Dependencies: none on purpose. Vanilla JS, no jQuery.
			CPA_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer', // Never blocks rendering (WP 6.3+).
			)
		);
	}

	/**
	 * Enqueue in <head> when we can detect usage ahead of time, which avoids a
	 * flash of unstyled content. The shortcode also enqueues as a fallback for
	 * page builders (e.g. Elementor) where the shortcode is not in post_content.
	 */
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
