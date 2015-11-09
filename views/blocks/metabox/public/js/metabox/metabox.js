/**
* 
*/

var CJTToolBox = {
	forms : {templatesLookupForm : []}
}; // Application name spaces structure.

/**
* put your comment there...
* 
*/
var CJTBlocksPage;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	CJTBlocksPage = {
		
		/**
		* put your comment there...
		* 
		*/
		blocks : null,
		
		/**
		* put your comment there...
		* 
		*/
		blocksForm : null,
		
		/**
		* put your comment there...
		* 
		* @type Boolean
		*/
		isContentChanged : false,
		
		/**
		* put your comment there...
		* 
		*/
		metaboxBlockToolbox : null,
		
		/**
		* put your comment there...
		* 
		*/
		server : null,
		
		/**
		* Reference to original Thickbox tb_position function.
		* 
		* 
		*/
		tb_position : window.tb_position,
		
		/**
		* 
		*/
		wpAutoSave : {timer : null, wpHandler : null},
		
		/**
		* put your comment there...
		* 
		*/
		_onbeforeunload : function() {
			var msg = '';
			// If autosave detect no changes on the post content check if CJT block has changed.
			if (!(msg = this.wpAutoSave.wpHandler())) {
				if (this.isContentChanged)  {
					 msg = CJTMetaboxI18N.notifySaveChanges;
				}
			}
			return msg;
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onshowthickbox : function(event) {
			var mediaUploadTbPosition = window.tb_position;
			// Use our custom method for positioning the Thickbox.
			window.tb_position = function() {
				// Call Thickbox original tb_position!
				CJTBlocksPage.tb_position();
				// Restore to media-upload tb_position.
				window.tb_position = mediaUploadTbPosition;
			};
			// Call target function.
			CJTBlocksPage.blocks.getBlocks().get(0).CJTBlock[this.event](event);
		},
		
		/**
		* Dummy method in order for block-metabox to work.
		*/
		blockContentChanged : function(id, isChanged) {
			this.isContentChanged = isChanged;
		},
		
		/**
		* put your comment there...
		* 
		* @param block
		*/
		deleteBlocks : function(block) {
			// Pack request data.
			var requestData = {
				id : block.get(0).CJTBlock.block.get('id'),
				post : $('#post_ID').val()
			};
			// Disable block while deleting and show Ajax loading progress.
			this.metaboxBlockToolbox.buttons['delete'].loading(true);
			this.metaboxBlockToolbox.enable(false);
			block.get(0).CJTBlock.enable(false);
			// Delete block.
			this.server.send('metabox', 'delete', requestData)
			.success($.proxy(
				function(createMetaboxView) {
					// Get metabox object just before the CJTBlocksPage take place.
					// If the CJTBlocksPage take place then we wont be able to get metabox object.
					var metabox = this.blocks.getBlock(this.blocks.getExistsIds()[0]);
					// Load CSS files required for metabox-block to work.
					(new StylesLoader(createMetaboxView.references.styles)).load();
					// Set tb_position to thickbox original so that metabox CJTBlocksPage can get it.
					var mediaHandlerTBPosition = window.tb_position;
					window.tb_position = this.tb_position;
					// Load Javascript files required for metabox-block to work.
					// After all metabox scripts are loaded place the view.
					(new ScriptsLoader(createMetaboxView.references.scripts)).loadAll().done($.proxy(
						function() {
							// Localize loaded scripts
							(new CJTWPScriptLocalizer(createMetaboxView.references.scripts)).localize();
								// Make the new poxtbox toggle-able!
								// Dont apply toggler twice for the exiss metaboxes.
								var metaboxes = $('#normal-sortables .postbox').removeClass('postbox');
								// Replace post metabox with the recevied metabox content.
								metabox.replaceWith(createMetaboxView.view);
								// Apply toggler on the new metabox.
								postboxes.add_postbox_toggles(pagenow);
								// Reset things back so the other metaboxes has the correct CSS class.
								metaboxes.addClass('postbox');
								// Reset tb_position to the one created by media-handler script.
								window.tb_position = mediaHandlerTBPosition;
								// Reset wordpress Autosave handler.
								if (this.wpAutoSave.wpHandler != undefined) {
									window.onbeforeunload	= this.wpAutoSave.wpHandler;
								}
						}, this)
					);
				}, this)
			);
		},
		
		/**
		* 
		*/
		detectWordpressAutoSaveAlertEvent : function() {
			// Wordpress event detected! Or the autosave file has been processed!
			if (window.onbeforeunload != undefined) {
				// No more check!
				clearInterval(this.wpAutoSave.timer);
				// Get reference from Wordpress unlaod event handler.
				this.wpAutoSave.wpHandler = window.onbeforeunload;
				// Take the control
				window.onbeforeunload = $.proxy(this._onbeforeunload, this);
			}
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() 
		{
			// Initialize vars.
			this.blocksForm = $('form[name="post"]');
			
			// Use plural getBlocks() and dont worry, we've only one block metabox.
			this.blocks = new CJTBlocks();
			this.server = CJTServer;
			
			// Initilize Global-Blocks Conponents.
			CJTBlockCodeFileView.initialize();
			
			$( document ).trigger( 'cjtmanagerpreloadblocks', [ this.blocksForm, this.blocks.getBlocks() ] );
			
			// Put CJT code block into actions!
			var blocks = this.blocks.getBlocks().CJTBlock({calculatePinPoint : 0});
			
			// Fix thickbox issue caused by media-upload.js script.
			this.metaboxBlockToolbox = blocks.get(0).CJTBlock.toolbox;
			
			this.metaboxBlockToolbox.buttons['info'].callback = $.proxy(this._onshowthickbox, {event : '_ongetinfo'});
			
			// Notify saving changes.
			this.wpAutoSave.timer = window.setInterval($.proxy(this.detectWordpressAutoSaveAlertEvent, this), 100);
			
			this.blocksForm.trigger( 'cjtblocksinitmetaboxpage', [ this, blocks.get(0).CJTBlock ] );
		}
		
	};
	
})(jQuery);