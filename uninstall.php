<?php
/**
 * Uninstall WP ZigZag Delayed Downloads for WooCommerce.
 *
 * @package WP_ZigZag_Delayed_Downloads
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/*
 * Remove plugin settings.
 */
delete_option( 'wpzzdd_settings' );

/*
 * Remove plugin settings from every site in a multisite network.
 */
if ( is_multisite() ) {

	global $wpdb;

	$blog_ids = $wpdb->get_col(
		"SELECT blog_id FROM {$wpdb->blogs}"
	);

	foreach ( $blog_ids as $blog_id ) {

		switch_to_blog( $blog_id );

		delete_option( 'wpzzdd_settings' );

		restore_current_blog();
	}
}