<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = get_option( 'wpzzdd_settings', array() );

$defaults = array(
    'countdown_seconds' => 10,
    'show_progress_bar' => 1,
    'header_message' => '',
    'footer_message' => '',
    'auto_download' => 1
);

$settings = wp_parse_args( $settings, $defaults );

        $target = isset( $_GET['target'] )
		? wp_unslash( $_GET['target'] )
		: '';
                            if ( empty( $target ) ) {
                    ?>
                    <div class="wpzzdd-wrap">
                        <h2><?php esc_html_e( 'Invalid Download Request', 'wpzigzag-delayed-downloads' ); ?></h2>
                        <p><?php esc_html_e( 'No download link was provided.', 'wpzigzag-delayed-downloads' ); ?></p>
                        <p>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <?php esc_html_e( 'Return to Homepage.', 'wpzigzag-delayed-downloads' ); ?>
                            </a>
                        </p>
                    </div>
                    <?php
                    return;
                }

        $target = html_entity_decode($target, ENT_QUOTES, 'UTF-8');

        $site_host = wp_parse_url(home_url(), PHP_URL_HOST);
        $target_host = wp_parse_url($target, PHP_URL_HOST);

if ($target_host && $target_host !== $site_host) {
    wp_die(
	esc_html__( 'Invalid download URL.', 'wpzigzag-delayed-downloads' )
);

}

$seconds = absint(
    $settings['countdown_seconds']
);
?>

<div class="wpzzdd-wrap">
                

    <h2><?php esc_html_e( 'Preparing Your Download', 'wpzigzag-delayed-downloads' ); ?></h2>

    <?php if ( ! empty( $settings['header_message'] ) ) : ?>
        <p><?php echo esc_html( $settings['header_message'] ); ?></p>
    <?php endif; ?>

    <?php if ( $settings['show_progress_bar'] ) : ?>

        <div class="wpzzdd-progress">
            <div id="wpzzdd-bar"></div>
        </div>

    <?php endif; ?>

    <div id="wpzzdd-counter" aria-live="polite">
        <?php echo esc_html( $seconds ); ?>
    </div>

    <p>
	<?php esc_html_e( 'Your download will start automatically.', 'wpzigzag-delayed-downloads' ); ?>
	</p>

    <p>
        <a
	id="wpzzdd-download"
	href="<?php echo esc_url( htmlspecialchars_decode( $target ) ); ?>"
	style="display:none;"
	>
	<?php esc_html_e( 'Download Now', 'wpzigzag-delayed-downloads' ); ?>
	</a>
    </p>

    <?php if ( ! empty( $settings['footer_message'] ) ) : ?>
        <p><?php echo esc_html( $settings['footer_message'] ); ?></p>
    <?php endif; ?>

</div>

<script>

let seconds =
<?php echo (int)$seconds; ?>;

const counter =
document.getElementById('wpzzdd-counter');

const bar =
document.getElementById('wpzzdd-bar');

const downloadBtn =
document.getElementById('wpzzdd-download');

const originalSeconds = seconds;

let timer = setInterval(function(){

    seconds--;

    counter.textContent = seconds;

    if ( bar ) {

        const progress =
        ( ( originalSeconds - seconds ) / originalSeconds ) * 100;

        bar.style.width =
        progress + '%';
    }

            if ( seconds <= 0 ) {

                clearInterval( timer );

                downloadBtn.style.display =
                'inline-block';

                <?php if ( ! empty( $settings['auto_download'] ) ) : ?>

		const downloadUrl =
		<?php echo wp_json_encode( htmlspecialchars_decode( $target ) ); ?>;

		setTimeout( function () {

		window.location.replace( downloadUrl );

		setTimeout( function () {
			downloadBtn.click();
		}, 1500 );

		}, 500 );

		<?php endif; ?>
            }

}, 1000 );

</script>