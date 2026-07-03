<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Frontend functionality.
*/

class WPZZDD_Frontend {
	/**
	 * Constructor.
	 */
    public function __construct() {

        add_shortcode(
            'wpzzdd_waiting_page',
            array( $this, 'waiting_page' )
        );

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'assets' )
        );

        add_action(
            'wp_footer',
            array( $this, 'inject_download_interceptor' )
        );

        add_filter(
            'woocommerce_customer_download_url',
            array( $this, 'modify_download_url' ),
            999,
            1
        );
    }
/**
 * Enqueue frontend assets.
 *
 * @return void
 */
    public function assets() {

	$page_id = get_option( 'wpzzdd_waiting_page_id' );

	$wait_page = $page_id ? get_post( $page_id ) : null;

	if ( ! $wait_page || ! is_page( $wait_page->ID ) ) {
		return;
	}

        wp_enqueue_style(
            'wpzzdd-frontend',
            WPZZDD_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            WPZZDD_VERSION
        );
    }
/**
 * Modify WooCommerce download URL.
 *
 * @param string $url Download URL.
 * @return string
 */
    
public function modify_download_url( $url ) {

	if ( empty( $url ) ) {
		return $url;
	}

	$settings = get_option( 'wpzzdd_settings', array() );

	if ( empty( $settings['enabled'] ) ) {
		return $url;
	}

	$wait_page = get_permalink( get_page_by_path( 'download-wait' ) );

	if ( ! $wait_page ) {
		return $url;
	}

	return add_query_arg(
		'target',
		rawurlencode( $url ),
		$wait_page
	);
}
/**
 * Inject JavaScript to intercept download links.
 *
 * @return void
 */
public function inject_download_interceptor() {

	$settings = get_option( 'wpzzdd_settings', array() );

	if ( empty( $settings['enabled'] ) ) {
		return;
	}

	$wait_page = get_permalink( get_page_by_path( 'download-wait' ) );

			if ( ! $wait_page ) {

			WPZZDD_Activator::create_waiting_page();

			$page_id = get_option( 'wpzzdd_waiting_page_id' );

			$wait_page = $page_id ? get_post( $page_id ) : null;
		}

		if ( ! $wait_page ) {
			return;
		}

	?>

	<script>

	document.addEventListener( 'click', function( e ) {

		const link = e.target.closest(
			'a.woocommerce-MyAccount-downloads-file'
		);

		if ( ! link ) {
			return;
		}

		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();

		const href = link.href;

		window.location.href =
			"<?php echo esc_url( $wait_page ); ?>?target=" +
			encodeURIComponent( href );

		return false;

	}, true );

	</script>

	<?php
}
/**
 * Render waiting page shortcode.
 *
 * @return string
 */
    public function waiting_page() {

        ob_start();

        include WPZZDD_PLUGIN_PATH .
            'templates/waiting-page.php';

        return ob_get_clean();
    }
}