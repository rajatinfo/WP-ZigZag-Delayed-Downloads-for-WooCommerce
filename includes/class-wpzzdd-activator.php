<?php
/**
 * Plugin Activator.
 *
 * @package WP_ZigZag_Delayed_Downloads
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin activator class.
 */
class WPZZDD_Activator {

	/**
	 * Plugin activation.
	 *
	 * @return void
	 */
	public static function activate() {

		self::create_default_settings();
		self::create_waiting_page();

		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation.
	 *
	 * @return void
	 */
	public static function deactivate() {

		flush_rewrite_rules();
	}

	/**
	 * Create default plugin settings.
	 *
	 * @return void
	 */
	private static function create_default_settings() {

		$defaults = array(
			'enabled'            => 1,
			'countdown_seconds'  => 10,
			'auto_download'      => 1,
			'show_progress_bar'  => 1,
			'show_product_image' => 1,
			'show_product_title' => 1,
			'telegram_url'       => '',
			'whatsapp_number'    => '',
			'header_message'     => '',
			'footer_message'     => '',
		);

		$existing = get_option( 'wpzzdd_settings', array() );

		if ( ! is_array( $existing ) ) {
			$existing = array();
		}

		$settings = wp_parse_args( $existing, $defaults );

		update_option( 'wpzzdd_settings', $settings );
	}

	/**
	 * Create the waiting page if it does not already exist.
	 *
	 * @return void
	 */
	private static function create_waiting_page() {

		$page = get_page_by_path( 'download-wait' );

		if ( $page ) {

			update_option( 'wpzzdd_waiting_page_id', $page->ID );

			return;
		}

		$page_id = wp_insert_post(
			array(
				'post_title'     => esc_html__( 'Download Wait', 'wpzigzag-delayed-downloads' ),
				'post_name'      => 'download-wait',
				'post_content'   => '[wpzzdd_waiting_page]',
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			)
		);

		if ( ! is_wp_error( $page_id ) ) {
			update_option( 'wpzzdd_waiting_page_id', $page_id );
		}
	}
}