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
			// Initialize.
			this.assignPanel = block.pagesPanel;
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
			// Whenever we're in revision mode enter the load-assigned-only-mode.
			this.assignPanel.loadAssignedOnlyMode = (this.state == 'revision');
			switch (this.state) {
				case 'revision':
					// If we're in revision mode set modeBlockId
					this.assignPanel.modeBlockId = revision['id'];
				break;
				default : // Revert back to normal state.
					// Reset alternate block Id.
				  this.assignPanel.modeBlockId = null;
				break;
			}
		}

		// Construct parent.
		this.CJTBlockOptionalRevision(block, revision);
		
	} // End Prototype.

	// Extend CJTBlockOptionalRevisionBase prototype.
	CJTBlockOptionalRevision.prototype = new CJTBlockOptionalRevisionBase();
	
})(jQuery);
