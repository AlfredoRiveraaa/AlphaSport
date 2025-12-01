( function ( $ ) {
	const ignore_gb_notice = function () {
		$( '.wcf_notice_gutenberg_plugin button.notice-dismiss' ).on(
			'click',
			function ( e ) {
				e.preventDefault();

				const data = {
					action: 'cartflows_ignore_gutenberg_notice',
					security: cartflows_notices.ignore_gb_notice,
				};

				$.ajax( {
					type: 'POST',
					url: ajaxurl,
					data,

					success( response ) {
						if ( response.success ) {
							console.log( 'Gutenberg Notice Ignored.' );
						}
					},
				} );
			}
		);
	};

	const dismiss_weekly_report_email_notice = function () {
		$(
			'.weekly-report-email-notice.wcf-dismissible-notice button.notice-dismiss'
		).on( 'click', function ( e ) {
			e.preventDefault();

			const data = {
				action: 'cartflows_disable_weekly_report_email_notice',
				security: cartflows_notices.dismiss_weekly_report_email_notice,
			};

			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data,

				success( response ) {
					if ( response.success ) {
						console.log( 'Weekly Report Email Notice Ignored.' );
					}
				},
			} );
		} );
	};

	const dismiss_custom_offer_notice = function () {
		$( '.wcf-custom-notice button.notice-dismiss' ).on(
			'click',
			function ( e ) {
				e.preventDefault();

				const data = {
					action: 'cartflows_dismiss_custom_offer_notice',
					security: cartflows_notices.dismiss_custom_offer_notice,
				};

				$.ajax( {
					type: 'POST',
					url: ajaxurl,
					data,

					success( response ) {
						if ( response.success ) {
							console.info( 'Custom Notice Dismissed.' );
						} else if ( response.data && response.data.message ) {
							console.error( response.data.message );
						} else {
							console.error(
								'An unknown error occurred. Please try again.'
							);
						}
					},
					/* eslint-disable */
					error( xhr, status, error ) {
						console.error(
							'A server or network error occurred. Please try again.'
						);
					},
					/* eslint-enable */
				} );
			}
		);
	};

	$( function () {
		ignore_gb_notice();
		dismiss_weekly_report_email_notice();
		dismiss_custom_offer_notice();
	} );
} )( jQuery );
