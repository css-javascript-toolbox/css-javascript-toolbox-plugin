/**
* 
*/
var CJTBlock;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @param element
	*/
	CJTBlock = function(blockPlugin, element) {
		
		// Constructor.
		this.CJTBlock = function() {
			// Initialize parent.
			this.CJTBlockBase(blockPlugin, element, {});
		}
		
		/**
		* 
		*/
		this.load = function() {
			// Load base model.
			this.loadBase({});
		}
		
		// Construct!
		this.CJTBlock();
	}
	
	// Extend CJTBlockBase class.
	CJTBlock.prototype = new CJTBlockBase();
	
})(jQuery)