<?php
/**
 * Plugin Name:       Custom Product Add-on Block
 * Description:       Services custom post type with a recent-services shortcode and a live vanilla-JS add-on price calculator.
 * Version:           1.1.0
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Author:            Sher Zaman
 * License:           GPL-2.0-or-later
 * Text Domain:       cpa-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'CPA_VERSION', '1.1.0' );
define( 'CPA_PLUGIN_FILE', __FILE__ );
define( 'CPA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CPA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once CPA_PLUGIN_DIR . 'includes/class-cpa-post-type.php';
require_once CPA_PLUGIN_DIR . 'includes/helpers.php';
require_once CPA_PLUGIN_DIR . 'includes/class-cpa-meta-boxes.php';
require_once CPA_PLUGIN_DIR . 'includes/class-cpa-assets.php';
require_once CPA_PLUGIN_DIR . 'includes/class-cpa-admin-columns.php';
require_once CPA_PLUGIN_DIR . 'includes/class-cpa-single-view.php';
require_once CPA_PLUGIN_DIR . 'includes/class-cpa-shortcode.php';

/**
 * Boot every component once all plugins are loaded.
 */
function cpa_init_plugin() {
	new CPA_Post_Type();
	new CPA_Meta_Boxes();
	new CPA_Assets();
	new CPA_Admin_Columns();
	new CPA_Single_View();
	new CPA_Shortcode();
}
add_action( 'plugins_loaded', 'cpa_init_plugin' );

/**
 * Register the CPT first, then flush rewrite rules so /services/ works immediately.
 */
function cpa_activate_plugin() {
	( new CPA_Post_Type() )->register_post_type();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cpa_activate_plugin' );

/**
 * Remove our rewrite rules on deactivation.
 */
function cpa_deactivate_plugin() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cpa_deactivate_plugin' );
