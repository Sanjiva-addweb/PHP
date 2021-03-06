jQuery( function( $ ) {
	
	$(document).on('change', '#ac_disable_guest_cart_email', function() {
        $(this).closest('tbody').find('#ac_track_guest_cart_from_cart_page').prop('disabled', this.checked);
    });

    $('#ac_disable_guest_cart_email').click(function( event ){

		if ($(this).is(':checked')) {
			$.post( ajaxurl, {
				action    : 'wcap_is_atc_enable',
			}, function( wcap_is_atc_enable ) {
				if ( 'on' == wcap_is_atc_enable ) {
					$( "#wcap_atc_disable_msg" ).html( "The Enable Add to cart popup modal setting has been disabled. As the guest cart's are no longer captured !!" );
					$("#wcap_atc_disable_msg" ).css({ 'color': 'red' });

		            $( "#wcap_atc_disable_msg" ).fadeIn();
		            setTimeout( function(){$( "#wcap_atc_disable_msg" ).fadeOut();},3000);		
				}
			});
		}
	});
});