/**
*
*/

/**
*
*
*
*/
(function($) {

	/**
	*
	*
	*
	*
	*/
	var blockRevisions = {
		
		/**
		*
		*
		*
		*
		*/
		block : null,
		
		/**
		*
		*
		*
		*
		*/		
		server : null,
		
		/**
		*
		*
		*
		*
		*/
		_onrestore : function(event) {
			// Get revision id.
			var link = event.target;
			var revisionId = parseInt(link.href.match(/#(\d+)$/)[1]);
			// Request revision data from server.
			this.server.send('block', 'get_revision', {id : revisionId})
			.success(
				function(revisionData) {
					// Copy revision data.
					blockRevisions.block.restoreRevision(revisionId, revisionData);
					// Remove Popup.
					window.parent.tb_remove();
				}
			);
		},
		
		/**
		*
		*
		*
		*
		*/	
		init : function() {
			var blocksPage = window.parent.CJTBlocksPage;
			// Get block object.
			var blockId = $('#block-revisions-form input:hidden#blockId').val();
			this.block = blocksPage.blocks.getBlock(blockId).get(0).CJTBlock;
			// Initialize vars.
			this.server = blocksPage.server;
			// Activate 'Restore' links.
			$('#block-revisions-form a.restore-link').click($.proxy(this._onrestore, this));
		}
		
	};
	
	// Wait form to be loaded.
	$($.proxy(blockRevisions.init, blockRevisions));
	
})(jQuery);