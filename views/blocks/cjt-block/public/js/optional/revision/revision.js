/**
* 
*/

var CJTBlockOptionalRevision = null;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	*/
	CJTBlockOptionalRevision = function(block, revision) {
	
		/**
		* 
		*/
		this.assignPanel = null;

		/**
		* put your comment there...
		* 
		* @param block
		* @param Revision
		*/
		this.CJTBlockOptionalRevision = function() {
			// Initialize parent.
			this.CJTBlockOptionalRevisionBase(block, revision);
			// Handle switch state event.
			this.onSwitchState = this._onswitchstate;
			this.onRestoreDone = this._onrestoredone;
			this.onBeforeSwitchState = this._onbeforeswitchstate;
			// Initialize.
			this.assignPanel = block.pagesPanel;
		}
	
		/**
		* 
		*/
		this._onbeforeswitchstate = function() {
			// deactivate all tabs while processing.
			block.pagesPanel.jElement.tabs({collapsible : true, active : false});
		}

		/**
		* 
		*/
		this._onrestoredone = function() {
			// Clear original cache except of 'code' property.
			this.original = {code : this.revision.code};
		}

		/**
		* 
		*/
		this._onswitchstate = function() {
			switch (this.state) {
				case 'revision':
					// If we're in revision mode set modeBlockId
					this.assignPanel.modeBlockId = revision['id'];
					// Force assignment panel revision mode.
					this.assignPanel.loadAssignedOnlyMode = true;
					this.assignPanel.checkboxDisabled = true;
				break;
				default : // Revert back to normal state.
					// Reset alternate block Id.
				  this.assignPanel.modeBlockId = null;
				break;
			}
			
			block = this.assignPanel.block;
			
			block.block.box.trigger( 'cjtblockrevisionswitchstate', [ block, this.state ] );
			
			// Reset assignpanel selected TAB.
			this.assignPanel.activateTab('advanced');
		}

		// Construct parent.
		this.CJTBlockOptionalRevision(block, revision);
		
	} // End Prototype.

	// Extend CJTBlockOptionalRevisionBase prototype.
	CJTBlockOptionalRevision.prototype = new CJTBlockOptionalRevisionBase();
	
})(jQuery);
