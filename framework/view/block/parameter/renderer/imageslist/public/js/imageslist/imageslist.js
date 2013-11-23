/**
* 
*/

/**
* 
*/
(function($){
		
	/**
	* put your comment there...
	* 
	*/
	var wp = window.parent.wp;
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	var wpHacks = {
		attachmentCallback : null,
		tbRemove : null,
		inserting : false
	};

	/**
	* Image List button prootype
	* 
	*/
	$.fn.CJTImagesListParameterRenderer = function() {

		// Process all buttons.
		return $.each(this, function() {
			
			/**
			* Button Link NODE reference.
			* 
			*/
			var link = $(this);
			
			/**
			* put your comment there...
			* 
			*/
			var imagesList;
			
			/**
			* put your comment there...
			* 
			*/
			var inputField;
			
			/**
			* put your comment there...
			* 
			*/
			var _oninsert = function(props, attachment) {
				// SET INSERTING FLAG ON to tell tb_remove function
				// to talk no action when insert into post button is clicked.
				wpHacks.inserting = true;
				// PROGRESS.
				link.CJTLoading({loading : true});
				// Get user-selected attachment HTML.
				wpHacks.attachmentCallback(props, attachment).done($.proxy(
					function(userSelectedImageSrc) {
						// Get THUMBNAIL attachment HTML.
						props.size = 'thumbnail';
						wpHacks.attachmentCallback(props, attachment).done($.proxy(
							function(thumbnailImageSrc) {
								// Add image to preview-images-list
								var listItem = $('<li>' + thumbnailImageSrc + '</li>').appendTo(imagesList)
								// Cahce the original source.
								.data('userSelectedImageSrc', userSelectedImageSrc);
								// Add a remove button to the new listItem.
								$('<input type="button" class="imageslist-remove-image-button" value="' + ImageslistI18N.removeButtonText + '" />').appendTo(listItem)
								.data('listItem', listItem)
								.click($.proxy(_onremoveimage, this));
								// Append the new iumage HTML source to the exists sources
								// for submission.
								addImage(userSelectedImageSrc);
								// Stop PROGRESS
								link.CJTLoading({loading : false, ceHandler : _onclick});
							}, this)
						);
					}, this)
				);
			};

			/**
			* put your comment there...
			* 
			* @param event
			* 
			* @returns {Boolean}
			*/
			var _onclick = function(event) {
				// Close Media Editor Form without closing the thickbox
				// as it'll remove the Shortcode parameters form as well.
				// NOTE: Its intensionally hacked later when the button is clicked
				// for the first time as Wordpress is almost hacked it when the document is just loaded!!!
				if (!wpHacks.tbRemove) {
					wpHacks.tbRemove = parent.tb_remove;
					// Define new tbRemove.
					parent.tb_remove = _ontbremove;
					// Bind to close button click as it doesn't call the tb_remove
					// function after hacking the thickbox tb_remove function.
					parent.jQuery('#TB_closeWindowButton, #TB_overlay').bind('click.cjtimageslistthickboxform', _onclose);
				}
				// Get called when the images is inserted!
				wp.media.editor.send.attachment = _oninsert;
				// Open media form.
				wp.media.editor.open(link);
				return false;
			};
			
			/**
			* Called when:
			*  - Click he close button.
			*  - Click the overlay.
			* 
			*/
			var _onclose = function() {
				hackFree();
			};

			/**
			* Called when:
			*  - Press Shortcode parameters form close button
			*  - Press Shortcode parameters form done button.
			*  - Press Media Form 'Insert Into Post' button. (This is why we created this function
			*  We simply avoid calling tb_remove when insert into post button clicked!).
			* 
			*/
			var _ontbremove = function() {
				// Reset if the form is currently closed is the Shortcode parameters form.
				// When the media insert button is clicked this function is get called as well.
				if (!wpHacks.inserting) {
					// Hack FREE thickbox.
					hackFree();
					// Close the form.
					parent.tb_remove();
				}
				// RESET INSERTING FLAG OFF.
				wpHacks.inserting = false;
			};
			
			/**
			* put your comment there...
			* 
			* @param event
			*/
			var _onremoveimage = function(event) {
				// Initialize.
				var jButton = $(event.target);
				// Confirm
				if (confirm(ImageslistI18N.confirmRemove)) {
					// Remove list item that holds the image.
					jButton.data('listItem').remove();
					// Re-generate the input-field HTML.
					inputField.val('');
					imagesList.children('li').each($.proxy(
					  function(index, listItem) {
					  	// Get original source.
					  	addImage($(listItem).data('userSelectedImageSrc'));
					  }, this)
					)
				}
			};
        
			/**
			* Add image to the input field as string
			* 
			* @param src
			*/
			var addImage = function(src) {
				inputField.val(inputField.val() + src);
			};

			/**
			* put your comment there...
			* 
			*/
			var hackFree = function() {
				// RESET THE HACKS WHEN THE PARAMETERS FORM IS CLOSED.
				parent.tb_remove = wpHacks.tbRemove;
				// The Shortcode parameters form is currently closed!
				wp.media.editor.send.attachment = wpHacks.attachmentCallback;
				// Unbind close button event.
				parent.jQuery('#TB_closeWindowButton, #TB_overlay').unbind('click.cjtimageslistthickboxform');
			};

			// Initialize.
			imagesList = $('#' + link.prop('id') + 'imageslist');
			inputField = $('#' + link.prop('id') + 'input');

			// Bind events.
			link.click($.proxy(_onclick, this));
		});
		
	}; // End ImagesListButton.
	
	/**
	* Find all ImagesList link buttons 
	* in the document. Create ImagesListButton
	* for every link.
	* 
	*/
	var initialize = function() {
		// Original attachment callback.
		wpHacks.attachmentCallback = wp.media.editor.send.attachment;
		// Initialize buttons.
		$('.cjt-shortcodeparameter-type-field-imageslist').CJTImagesListParameterRenderer();
	};
	
	// RUN.
	$(document).ready(initialize);
	
})(jQuery);