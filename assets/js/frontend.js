/**
 * WP ZigZag Delayed Downloads
 * Frontend Script
 */

'use strict';

document.addEventListener(
	'click',
	function ( event ) {

		const link = event.target.closest( 'a' );

		if ( ! link ) {
			return;
		}

		const href = link.getAttribute( 'href' );

		if ( ! href ) {
			return;
		}

		if ( ! href.includes( 'download_file=' ) ) {
			return;
		}

		if ( ! window.wpzzdd || ! wpzzdd.waitPage ) {
			return;
		}

		event.preventDefault();
		event.stopPropagation();

		window.location.href =
			wpzzdd.waitPage +
			'?target=' +
			encodeURIComponent( href );

	},
	true
);