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
		this._onrestoredone = function() {
			// Clear original cache except of 'code' property.
			this.original = {code : this.revision.code};
		}

		/**
		* put your comment there...
		* 
		* @param block
		* @param Revision
		*/
		this.CJTBlockOptionalRevision = function() {
			// Initialize parent.
			this.CJTBlockOptionalRevisionBase(block, revision);
			// Hook2Events
			this.onRestoreDone = this._onrestoredone;
		}

		// Construct parent.
		this.CJTBlockOptionalRevision(block, revision);
		
	} // End Prototype.

	// Extend CJTBlockOptionalRevisionBase prototype.
	CJTBlockOptionalRevision.prototype = new CJTBlockOptionalRevisionBase();
	
})(jQuery);
