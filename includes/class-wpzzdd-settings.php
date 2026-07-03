<?php
/**
 * Plugin Settings
 *
 * @package WP_ZigZag_Delayed_Downloads
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin settings page.
 */
class WPZZDD_Settings {

	/**
	 * Settings option name.
	 *
	 * @var string
	 */
	private $option_name = 'wpzzdd_settings';

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action(
			'admin_menu',
			array( $this, 'admin_menu' )
		);

		add_action(
			'admin_init',
			array( $this, 'register_settings' )
		);
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function admin_menu() {

		add_submenu_page(
			'woocommerce',
			esc_html__( 'Delayed Downloads', 'wpzigzag-delayed-downloads' ),
			esc_html__( 'Delayed Downloads', 'wpzigzag-delayed-downloads' ),
			'manage_options',
			'wpzzdd-settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @return void
	 */
	public function register_settings() {

		register_setting(
			'wpzzdd_settings_group',
			$this->option_name,
			array( $this, 'sanitize' )
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Raw settings.
	 * @return array
	 */
	public function sanitize( $input ) {

		return array(

			'enabled' => ! empty(
				$input['enabled']
			) ? 1 : 0,

			'countdown_seconds' => isset(
				$input['countdown_seconds']
			)
				? min(
					300,
					max(
						1,
						absint(
							$input['countdown_seconds']
						)
					)
				)
				: 10,

			'auto_download' => ! empty(
				$input['auto_download']
			) ? 1 : 0,

			'show_progress_bar' => ! empty(
				$input['show_progress_bar']
			) ? 1 : 0,

			'show_product_image' => ! empty(
				$input['show_product_image']
			) ? 1 : 0,

			'show_product_title' => ! empty(
				$input['show_product_title']
			) ? 1 : 0,

			'header_message' => isset(
				$input['header_message']
			)
				? sanitize_textarea_field(
					$input['header_message']
				)
				: '',

			'footer_message' => isset(
				$input['footer_message']
			)
				? sanitize_textarea_field(
					$input['footer_message']
				)
				: '',
		);
	}

	/**
	 * Settings page.
	 *
	 * @return void
	 */
	public function settings_page() {

		$defaults = array(
			'enabled'            => 1,
			'countdown_seconds'  => 10,
			'auto_download'      => 1,
			'show_progress_bar'  => 1,
			'show_product_image' => 1,
			'show_product_title' => 1,
			'header_message'     => '',
			'footer_message'     => '',
		);

		$settings = wp_parse_args(
			get_option(
				$this->option_name,
				array()
			),
			$defaults
		);

		?>
        <div class="wrap">

	<h1>
		<?php esc_html_e( 'WP ZigZag Delayed Downloads for WooCommerce', 'wpzigzag-delayed-downloads' ); ?>
	</h1>

	<p>
		<?php esc_html_e( 'Configure how delayed downloads work for WooCommerce digital products.', 'wpzigzag-delayed-downloads' ); ?>
	</p>

	<form method="post" action="options.php">

		<?php settings_fields( 'wpzzdd_settings_group' ); ?>

		<table class="form-table" role="presentation">

			<tr>

				<th scope="row">
					<?php esc_html_e( 'Enable Plugin', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<label>

						<input
							type="checkbox"
							name="<?php echo esc_attr( $this->option_name ); ?>[enabled]"
							value="1"
							<?php checked( $settings['enabled'], 1 ); ?>
						>

						<?php esc_html_e( 'Enable delayed downloads.', 'wpzigzag-delayed-downloads' ); ?>

					</label>

				</td>

			</tr>

			<tr>

				<th scope="row">
					<?php esc_html_e( 'Countdown Seconds', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<input
						type="number"
						min="1"
						max="300"
						class="small-text"
						name="<?php echo esc_attr( $this->option_name ); ?>[countdown_seconds]"
						value="<?php echo esc_attr( $settings['countdown_seconds'] ); ?>"
					>

					<p class="description">
						<?php esc_html_e( 'How long users wait before download starts.', 'wpzigzag-delayed-downloads' ); ?>
					</p>

				</td>

			</tr>
            			<tr>

				<th scope="row">
					<?php esc_html_e( 'Auto Download', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<label>

						<input
							type="checkbox"
							name="<?php echo esc_attr( $this->option_name ); ?>[auto_download]"
							value="1"
							<?php checked( $settings['auto_download'], 1 ); ?>
						>

						<?php esc_html_e( 'Start download automatically after countdown.', 'wpzigzag-delayed-downloads' ); ?>

					</label>

				</td>

			</tr>

			<tr>

				<th scope="row">
					<?php esc_html_e( 'Show Progress Bar', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<label>

						<input
							type="checkbox"
							name="<?php echo esc_attr( $this->option_name ); ?>[show_progress_bar]"
							value="1"
							<?php checked( $settings['show_progress_bar'], 1 ); ?>
						>

						<?php esc_html_e( 'Display a countdown progress bar.', 'wpzigzag-delayed-downloads' ); ?>

					</label>

				</td>

			</tr>

			<tr>

				<th scope="row">
					<?php esc_html_e( 'Show Product Image', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<label>

						<input
							type="checkbox"
							name="<?php echo esc_attr( $this->option_name ); ?>[show_product_image]"
							value="1"
							<?php checked( $settings['show_product_image'], 1 ); ?>
						>

						<?php esc_html_e( 'Display the WooCommerce product image on the waiting page.', 'wpzigzag-delayed-downloads' ); ?>

					</label>

				</td>

			</tr>

			<tr>

				<th scope="row">
					<?php esc_html_e( 'Show Product Title', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<label>

						<input
							type="checkbox"
							name="<?php echo esc_attr( $this->option_name ); ?>[show_product_title]"
							value="1"
							<?php checked( $settings['show_product_title'], 1 ); ?>
						>

						<?php esc_html_e( 'Display the WooCommerce product title on the waiting page.', 'wpzigzag-delayed-downloads' ); ?>

					</label>

				</td>

			</tr>
            			<tr>

				<th scope="row">
					<?php esc_html_e( 'Header Message', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<textarea
						name="<?php echo esc_attr( $this->option_name ); ?>[header_message]"
						rows="4"
						cols="60"
					><?php echo esc_textarea( $settings['header_message'] ); ?></textarea>

					<p class="description">
						<?php esc_html_e( 'Message displayed above the countdown.', 'wpzigzag-delayed-downloads' ); ?>
					</p>

				</td>

			</tr>

			<tr>

				<th scope="row">
					<?php esc_html_e( 'Footer Message', 'wpzigzag-delayed-downloads' ); ?>
				</th>

				<td>

					<textarea
						name="<?php echo esc_attr( $this->option_name ); ?>[footer_message]"
						rows="4"
						cols="60"
					><?php echo esc_textarea( $settings['footer_message'] ); ?></textarea>

					<p class="description">
						<?php esc_html_e( 'Message displayed below the download button.', 'wpzigzag-delayed-downloads' ); ?>
					</p>

				</td>

			</tr>

		</table>

		<?php
		submit_button(
			esc_html__( 'Save Settings', 'wpzigzag-delayed-downloads' )
		);
		?>

	</form>

</div>

<?php
	}
}
