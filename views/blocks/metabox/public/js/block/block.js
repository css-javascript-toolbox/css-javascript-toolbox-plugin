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
	var CJTBlock = function(element) {
		
		// Constructor.
		this.CJTBlock = function() {
			// Initialize parent.
			this.CJTBlockBase(element);
		}
		
		// Construct!
		this.CJTBlock();
	}
	
	// Extend CJTBlockBase class.
	CJTBlock.prototype = CJTBlockBase;
	
})(jQuery)