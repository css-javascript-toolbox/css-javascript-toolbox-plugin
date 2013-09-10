/**
* 
*/

var CJTBlockOptionalRevisionBase = null;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	*/
	CJTBlockOptionalRevisionBase = function() {
    
		/**
		* put your comment there...
		* 
		* @type Boolean
		*/
		this.block;

		/**
		* 
		*/
		this.original;
		
		/**
		* put your comment there...
		* 
		* @type Boolean
		*/
		this.revision;

		/**
		* 
		*/
		this.state;
		
		/**
		* Switch state event
		*/
		this.onDoneRestore;
	
		/**
		* Switch state event
		*/
		this.onSwitchState;
		
		/**
		* 
		*/
		this.cancel = function() {
			// Initialize
			var block = this.block;
			var toolbox = block.toolbox;
			var saveButton = toolbox.buttons['save'];
			var mdlBlock = block.block;
			var properties = block.features.restoreRevision.fields;
			var pom;
			// Copy original data back to the block.
			$.each(properties, $.proxy(
				function(index, propertyName) {
					// Get property om
					pom = mdlBlock.property(propertyName).om;
					// Set the original value.
					pom.setValue(this.original[propertyName]);
				}, this)
			);
			// Remove cancel button.
			toolbox.remove('cancel-restore');
			// Enable block components.
			block.enable(true);
			toolbox.enable(true);
			// Set notification changes back.
			block.changes = this.original.changes;
			var isChanged = CJTBlocksPage.blocks.calculateChanges(block.changes, 0, false);
			// Enable button is there is a change not saved yet, disable it if not.
			saveButton.enable(isChanged);
			// Reset save buttons properties back.
			saveButton.jButton.text('Save');
			saveButton.callback = saveButton._callback;
			block._onsavechanges = block.__onsavechanges;
			saveButton._callback = null;
			// Notify blocks page.
			CJTBlocksPage.blockContentChanged(this.block.id, isChanged);
			// Change state / Enter revision mode.
			this.onSwitchState(this.state = null);
		}
		
		/**
		* put your comment there...
		* 
		*/
		this.CJTBlockOptionalRevisionBase = function(block, revision) {
			// Initialize instance.
			this.block = block;
			this.revision = revision;
			this.original = {};
			this.state = 'normal';
			// Events.
			this.onSwitchState = function() {};
			this.onDoneRestore = function() {};
		}
	
		/**
		* 
		*/
		this.display = function() {
			// Initialize.
			var block = this.block;
			var toolbox = block.toolbox;
			var saveButton = toolbox.buttons['save'];
			var mdlBlock = block.block;
			var properties = block.features.restoreRevision.fields;
			var pom;
			// Cache 'Changes' array, don't reference it from block.changes
			// as both got the same variable reference.
			this.original.changes = block.changes; block.changes = [];
			// Display revision data
			$.each(properties, $.proxy(
				function(index, propertyName) {
					// Get property om
					pom = mdlBlock.property(propertyName).om;
					// Get original property value.
					this.original[propertyName] = pom.getValueCache();
					// Display Revision value.
					pom.setValue(this.revision[propertyName]);
				}, this)
			);
			// Empty changes array. As it affected by the copy code above!
			block.changes = [];
			// Force Notification Changes to detect that current block has changes.
			CJTBlocksPage.blockContentChanged(mdlBlock.get('id'), true);
			// DISABLE-ALL toolbox buttons
			toolbox.enable(false);
			// Except save button!
			saveButton.enable(true);
			saveButton._callback = saveButton.callback;
			saveButton.__onsavechanges = block._onsavechanges;
			block._onsavechanges = saveButton.callback = $.proxy(this.restore, this);
			// Rename it to 'Restore'
			saveButton.jButton.text('Restore')
			// Reflect new style
			.addClass('cjttblSW-restore');
			// Add cancel restore button.
			$('<a href="#" class="cjt-tb-link cjttbl-cancel-restore il-60x23"></a>')
			.text('Cancel')
			.insertBefore(saveButton.jButton);
			toolbox.add('cancel-restore', {callback : $.proxy(this.cancel, this)});
			// Disable block.
			block.enable(false);
			// Change state / Enter revision mode.
			this.onSwitchState(this.state = 'revision');
		}
		
		/**
		* 
		*/
		this.restore = function() {
			// Initialize.
			var block = this.block;
			var toolbox = block.toolbox;
			var saveButton = toolbox.buttons['save'];			
			var server = CJTBlocksPage.server;
			var request = {rid : this.revision['id'], bid : block.block.get('id')};
			// Restore revision via server.
			saveButton.loading(true);
			server.send('block', 'restoreRevision', request)
			.success($.proxy(
				function() {
					// Fire restore done event.
					this.onRestoreDone();
					// Clear notify-save-changes list.
					this.original.changes = [];
					// Stop loading
					saveButton.loading(false);
					// Cancel restore action will reset
					// the block UI elements to the normal state.
					this.cancel();
				}, this)
			);
		}
	
	} // End Prototype.

})(jQuery);
