<?php

/**
 * Plugin Name: WP ZigZag Delayed Downloads for WooCommerce
 * Plugin URI: https://github.com/rajatinfo/WP-ZigZag-Delayed-Downloads-for-WooCommerce
 * Description: Add a customizable countdown page before WooCommerce downloadable products. Compatible with WooCommerce native downloads and S3-compatible storage.
 * Version: 1.0.0
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * WC requires at least: 8.5
 * WC tested up to: 10.0
 * Author: WP ZigZag
 * Author URI: https://github.com/rajatinfo
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpzigzag-delayed-downloads
 * Domain Path: /languages
*/

/**
 * Main plugin bootstrap file.
 *
 * @package WP_ZigZag_Delayed_Downloads
 */



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPZZDD_VERSION', '1.0.0' );
define( 'WPZZDD_PLUGIN_FILE', __FILE__ );
define( 'WPZZDD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPZZDD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WPZZDD_PLUGIN_PATH . 'includes/class-wpzzdd-activator.php';
require_once WPZZDD_PLUGIN_PATH . 'includes/class-wpzzdd-loader.php';

/**
 * Register activation and deactivation hooks.
*/

register_activation_hook(
    WPZZDD_PLUGIN_FILE,
    array( 'WPZZDD_Activator', 'activate' )
);

register_deactivation_hook(
    WPZZDD_PLUGIN_FILE,
    array( 'WPZZDD_Activator', 'deactivate' )
);


/**
 * Boot the plugin.
 *
 * @return void
 */

function wpzzdd_boot_plugin() {
	$loader = new WPZZDD_Loader();
	$loader->init();
}

add_action(
    'plugins_loaded',
    'wpzzdd_boot_plugin'
);

/**
 * Load plugin text domain.
 *
 * @return void
 */
function wpzzdd_load_textdomain() {

	load_plugin_textdomain(
		'wpzigzag-delayed-downloads',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}

add_action( 'init', 'wpzzdd_load_textdomain' );