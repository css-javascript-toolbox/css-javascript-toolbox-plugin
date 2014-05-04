/**
* 
*/

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
		*/
		createLink : null,
		
		/**
		* put your comment there...
		* 
		*/
		tb_position : window.tb_position,
		
		/**
		* put your comment there...
		* 
		*/
		_oncreate : function() {
			// Confirm metabox creation!
			var confirmed = confirm(CJTMetaboxI18N.confirmCreateBlockMetabox);
			if (confirmed) {
				var metabox = {post : $('#post_ID').val()};
				this.server.send('metabox', 'create', metabox)
				.success($.proxy(
					function(blockView) {
						// Get metabox object just before the CJTBlocksPage take place.
						// If the CJTBlocksPage take place then we wont be able to get metabox object.
						var metabox = CJTBlocksPage.blocks.getBlock(CJTBlocksPage.blocks.getExistsIds()[0]);
						// Show Ajax loading progress.
						this.createLink.CJTLoading({ceHandler : this._oncreate});
						// Load CSS files required for metabox-block to work.
						(new StylesLoader(blockView.references.styles)).load();
						// Set tb_position to thickbox original so that metabox CJTBlocksPage can get it.
						var mediaHandlerTBPosition = window.tb_position;
						window.tb_position = this.tb_position;
						// Localize loaded scripts
						(new CJTWPScriptLocalizer(blockView.references.scripts)).localize();
						// Load Javascript files required for metabox-block to work.
						// After all metabox scripts are loaded display the view.
						(new ScriptsLoader(blockView.references.scripts)).loadAll().done($.proxy(
							function() {
								 // Make the new poxtbox toggle-able!
								// Dont apply toggler twice for the extsis metaboxes.
								var metaboxes = $('#normal-sortables .postbox').removeClass('postbox');
								// Replace post metabox with the recevied metabox content.
								metabox.replaceWith(blockView.view);
								// Apply toggler on the new metabox.
								postboxes.add_postbox_toggles(pagenow);
								// Reset things back so the other metaboxes has the correct CSS class.
								metaboxes.addClass('postbox');
								// Reset tb_position to the one created by media-handler script.
								window.tb_position = mediaHandlerTBPosition;
							}, this)
						);
					}, this)
				);
			}
			// For link to behave inactive.
			return false;
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize vars.
			this.blocksForm = $('form[name="post"]');
			this.blocks = new CJTBlocks();
			this.server = CJTServer;
			// Bind events.
			this.createLink = this.blocksForm.find('a#create-cjt-block').click($.proxy(this._oncreate, this));
		}
		
	};
})(jQuery);