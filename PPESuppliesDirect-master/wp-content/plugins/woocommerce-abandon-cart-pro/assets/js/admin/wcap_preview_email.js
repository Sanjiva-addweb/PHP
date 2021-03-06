jQuery( function( $ ) {

	//$( "#wcap_wc_preview" ).click( function( event ) {
        //event.preventDefault();


        //var wcap_image_url = wcap_test_email_params.wcap_test_email_sent_image_path;

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        // $.post( ajaxurl, data, function( response ) {
        //     $( "#preview_email_sent_msg" ).html( "<img src="+ wcap_image_url +">&nbsp;Email has been sent successfully." );
        //     $( "#preview_email_sent_msg" ).fadeIn();
        //     setTimeout( function(){$( "#preview_email_sent_msg" ).fadeOut();},3000);
        // } );
    //} );



    /**
 * Abandoned cart detail Modal
 */

var Modal;
var wcap_clicked_cart_id;
var $wcap_get_email_address;
var $wcap_customer_details;
var $wcap_cart_total;
var $wcap_abandoned_date;
var $wcap_abandoned_status;
var email_body;
var $wcap_cart_status;
var $wcap_show_customer_detail;

jQuery(function($) {

    Modal = {
        wcap_init_preview: function(){

            $(document.body).on( 'click', '.wcap-js-close-modal', this.wcap_close_preview );
            $(document.body).on( 'click', '.wcap-modal-overlay',  this.wcap_close_preview );
            $(document.body).on( 'click', '.wcap_wc_preview',     this.wcap_preview_handle_link );
            $(document.body).on( 'click', '.wcap_preview',        this.wcap_preview_handle_link );
            $(document.body).on( 'click', '#preview_test_email',  this.wcap_send_test_email );

            $(window).resize(function(){
                Modal.wcap_position_preview();
            });

            $(document).keydown(function(e) {
                if (e.keyCode == 27) {
                    Modal.wcap_close_preview();
                }
            });

        },
        wcap_preview_handle_link: function( e ){
            e.preventDefault();

            var $a = $( this );

            var current_page   = '';
            var wcap_get_currentpage = window.location.href;
            var type = $a.data('modal-type');
            var url = $a.attr('href');
            var wcap_class = $a.data('email-type');;

            if ( type == 'wcap_preview_ajax' )
            {

                Modal.wcap_open_preview( 'type-ajax' );
                Modal.wcap_loading_preview();
                var email_body            = '';
                if ( $("#wp-woocommerce_ac_email_body-wrap").hasClass( "tmce-active" ) ){
                    email_body =  tinyMCE.get('woocommerce_ac_email_body').getContent();
                }else{
                    email_body =  jQuery('#woocommerce_ac_email_body').val();
                }
                var body_email_preview      = email_body;
                var send_email_id           = $( '#send_test_email' ).val();
                var wc_template_header      = $( '#wcap_wc_email_header' ).val() != '' ? $( '#wcap_wc_email_header' ).val() : 'Abandoned cart reminder';


                var wcap_action = '';
                if ( 'wcap_wc_preview' == wcap_class ) {
                    wcap_action = 'wcap_preview_wc_email';
                }else if ( 'wcap_preview' == wcap_class ) {
                    wcap_action = 'wcap_preview_email';
                }

                var data = {

                    body_email_preview : body_email_preview,
                    send_email_id      : send_email_id,
                    wc_template_header : wc_template_header,
                    action             : wcap_action
                };

                $.post( ajaxurl, data , function( response ){

                    Modal.wcap_preview_contents( response );
                });
            }
        },
        wcap_open_preview: function( classes ) {
            email_body = '';
            $(document.body).addClass('wcap-modal-open').append('<div class="wcap-modal-overlay"></div>');
            $(document.body).append('<div class="wcap-modal ' + classes + '"><div class="wcap-modal__contents"> <div class="wcap-modal__header"><h1>Email preview</h1> </div> <div class="wcap-modal__body"> <div class="wcap-modal__body-inner">  </div> </div> <div class = "wcap-modal-cart-content-hide" id ="wcap_remove_class">  </div> </div>  <div class="wcap-icon-close wcap-js-close-modal">    </div>');

            this.wcap_position_preview();
        },

        wcap_loading_preview: function() {
            $(document.body).addClass('wcap-modal-loading');
        },

        wcap_preview_contents: function ( wcap_contents ) {
            $(document.body).removeClass('wcap-modal-loading');

            $('.wcap-modal__contents').html( wcap_contents );

            this.wcap_position_preview();
        },

        wcap_close_preview: function() {
            $(document.body).removeClass('wcap-modal-open wcap-modal-loading');

            $('.wcap-modal, .wcap-modal-overlay').remove();
        },

        wcap_position_preview: function() {

            $('.wcap-modal__body').removeProp('style');

            var modal_header_height = $('.wcap-modal__header').outerHeight();
            var modal_height = $('.wcap-modal').height();
            var modal_width = $('.wcap-modal').width();
            var modal_body_height = $('.wcap-modal__body').outerHeight();
            var modal_contents_height = modal_body_height + modal_header_height;

            $('.wcap-modal').css({
                'margin-left': -modal_width / 2,
                'margin-top': -modal_height / 2
            });

            if ( modal_height < modal_contents_height - 5 ) {
                $('.wcap-modal__body').height( modal_height - modal_header_height );
            }
        },
        wcap_send_test_email: function ( event ) {

            var $wcap_get_selected_button = $( this );
            var type = $wcap_get_selected_button.data('wcap-email-type');

            var email_body            = '';
            if ( $("#wp-woocommerce_ac_email_body-wrap").hasClass( "tmce-active" ) ){
                email_body =  tinyMCE.get('woocommerce_ac_email_body').getContent();
            }else{
                email_body =  jQuery('#woocommerce_ac_email_body').val();
            }

            var subject_email_preview   = $( '#woocommerce_ac_email_subject' ).val();
            var body_email_preview      = email_body;
            var send_email_id           = $( '#send_test_email_preview' ).val();
            var is_wc_template          = '';
            var wc_template_header      = $( '#wcap_wc_email_header' ).val() != '' ? $( '#wcap_wc_email_header' ).val() : 'Abandoned cart reminder';

            if ( 'wc_preview' == type ) {
                is_wc_template = 'true';
            }else if ( 'normal_preview' == type ) {
                is_wc_template = 'false';
            }

            var data = {

                subject_email_preview   : subject_email_preview,
                body_email_preview      : body_email_preview,
                send_email_id           : send_email_id,
                is_wc_template          : is_wc_template,
                wc_template_header      : wc_template_header,
                action                  : 'wcap_preview_email_sent'
            };

            var wcap_image_url = wcap_preview_email_params.wcap_email_sent_image_path;

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post( ajaxurl, data, function( response ) {
                $( "#preview_test_email_sent_msg" ).html( "<img style = 'height: 18px; width:20px;' src="+ wcap_image_url +"> &nbsp;Email has been sent successfully." );
                $( "#preview_test_email_sent_msg" ).fadeIn();
                setTimeout( function(){$( "#preview_test_email_sent_msg" ).fadeOut();},3000);
            } );
        }
    };
    Modal.wcap_init_preview();
});

});
