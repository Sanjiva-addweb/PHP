jQuery(function( $ ) {

	$('.wcap_reset_button').click(function( event ){
		event.preventDefault();
		$.post( ajaxurl, {
			action    : 'wcap_atc_reset_setting',
			
		}, function( wcap_atc_enable_response ) {
			location.reload();
		});
	});
});