jQuery(document).ready(function($){
	"use strict";
	var educrat_upload;
	var educrat_selector;

	function educrat_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		educrat_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( educrat_upload ) {
			educrat_upload.open();
			return;
		} else {
			// Create the media frame.
			educrat_upload = wp.media.frames.educrat_upload =  wp.media({
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
			educrat_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = educrat_upload.state().get('selection').first();

				educrat_upload.close();
				educrat_selector.find('.upload_image').val(attachment.attributes.url).change();
				if ( attachment.attributes.type == 'image' ) {
					educrat_selector.find('.educrat_screenshot').empty().hide().prepend('<img src="' + attachment.attributes.url + '">').slideDown('fast');
				}
			});

		}
		// Finally, open the modal.
		educrat_upload.open();
	}

	function educrat_remove_file(selector) {
		selector.find('.educrat_screenshot').slideUp('fast').next().val('').trigger('change');
	}
	
	$('body').on('click', '.educrat_upload_image_action .remove-image', function(event) {
		educrat_remove_file( $(this).parent().parent() );
	});

	$('body').on('click', '.educrat_upload_image_action .add-image', function(event) {
		educrat_add_file(event, $(this).parent().parent());
	});

});