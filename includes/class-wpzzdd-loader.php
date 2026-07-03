<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin loader.
 *
 * Loads plugin components and checks WooCommerce dependency.
 */
class WPZZDD_Loader {

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init() {

    if ( ! $this->woocommerce_active() ) {

        add_action(
            'admin_notices',
            array( $this, 'woocommerce_missing_notice' )
        );

        return;
    }

	require_once WPZZDD_PLUGIN_PATH . 'includes/class-wpzzdd-activator.php';
	require_once WPZZDD_PLUGIN_PATH . 'includes/class-wpzzdd-settings.php';
	require_once WPZZDD_PLUGIN_PATH . 'includes/class-wpzzdd-frontend.php';   
        new WPZZDD_Settings();
        new WPZZDD_Frontend();
    }


/**
 * Check whether WooCommerce is active.
 *
 * @return bool
 */

    private function woocommerce_active() {

        return class_exists( 'WooCommerce' );
    }

/**
 * Display admin notice when WooCommerce is missing.
 *
 * @return void
 */


    public function woocommerce_missing_notice() {
        ?>
        <div class="notice notice-error">
           	<p>
	<?php
	printf(
		/* translators: %s: Plugin name. */
		esc_html__( '%s requires WooCommerce to be installed and active.', 'wpzigzag-delayed-downloads' ),
		'<strong>' . esc_html__( 'WP ZigZag Delayed Downloads for WooCommerce', 'wpzigzag-delayed-downloads' ) . '</strong>'
	);
	?>
</p>
        </div>
        <?php
    }
}