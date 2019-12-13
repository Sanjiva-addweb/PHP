jQuery(document).ready(function($){
	"use strict";
	var yozi_upload;
	var yozi_selector;

	function yozi_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		yozi_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( yozi_upload ) {
			yozi_upload.open();
			return;
		} else {
			// Create the media frame.
			yozi_upload = wp.media.frames.yozi_upload =  wp.media({
				// Set the title of the modal.
				title: "Select Image",

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: "Selected",
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			yozi_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = yozi_upload.state().get('selection').first();

				yozi_upload.close();
				yozi_selector.find('.upload_image').val(attachment.attributes.url).change();
				if ( attachment.attributes.type == 'image' ) {
					yozi_selector.find('.yozi_screenshot').empty().hide().prepend('<img src="' + attachment.attributes.url + '">').slideDown('fast');
				}
			});

		}
		// Finally, open the modal.
		yozi_upload.open();
	}

	function yozi_remove_file(selector) {
		selector.find('.yozi_screenshot').slideUp('fast').next().val('').trigger('change');
	}
	
	$('body').on('click', '.yozi_upload_image_action .remove-image', function(event) {
		yozi_remove_file( $(this).parent().parent() );
	});

	$('body').on('click', '.yozi_upload_image_action .add-image', function(event) {
		yozi_add_file(event, $(this).parent().parent());
	});

});