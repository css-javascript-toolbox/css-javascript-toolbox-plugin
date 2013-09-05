/**
* 
*/

var CJTBlockPropertyAPItemsList;

/**
* 
*/
(function($) {

	/**
	* put your comment there...
	* 
	* @param assignPanel
	*/
	CJTBlockPropertyAPItemsList = function(assignPanel) {
		
		/**
		* put your comment there...
		* 
		*/
		var block = null;
		
		/**
		* put your comment there...
		* 
		*/
		var property = null;
		
		/**
		* 
		*/
		this.bind = function(blk, prprty) {
			// Bind to property object.
			block = blk;
			property = prprty;
		}

		/**
		* 
		*/
		this.get = function() {
			// Initialize.
			var map = block.pagesPanel.getMap();
			// Get values directy from the assignment panel
			var value = map[property.name];
			// Returns
			return value;
		}
		
		/**
		* 
		*/
		this.setValue = function(value) {
			
		}
	}
	
})(jQuery);