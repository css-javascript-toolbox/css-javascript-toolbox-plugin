/**
* 
*/

var CJTBlockPropertyHTMLNodeOM;

/**
* 
*/
(function($) {

	/**
	* put your comment there...
	* 
	*/
	CJTBlockPropertyHTMLNodeOM = function() {
		
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
			var mdlBlock = block.block;
			// Get the value.
			var element = mdlBlock.box.find(property.selector);
			value = element.val();
			// Returns
			return value;
		}

	}
	
})(jQuery);