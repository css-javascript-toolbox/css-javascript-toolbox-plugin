/**
* 
*/

var CJTBlockPropertyHTMLNodeOM;
var CJTBlockPropertyACEEditor;

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
		};

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

		/**
		* 
		*/
		this.getValueCache = function() {
			return this.get();
		};

		/**
		* 
		*/
		this.setValue = function(value) {
			// Initialize.
			var mdlBlock = block.block;
			// Get the value.
			var element = mdlBlock.box.find(property.selector);
			element.val(value);
		};
		
		/**
		* 
		*/
		this.reset = function() {
			var element = block.block.box.find(property.selector)[0];
			return element.cjtBlockSyncValue;
		};

	}
	
	
	/**
	* put your comment there...
	* 
	*/
	CJTBlockPropertyACEEditor = function() {
		
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
		};

		/**
		* 
		*/
		this.get = function() {
			return block.block.aceEditor.getSession().getValue();
		};
	  
		/**
		* 
		*/
		this.getValueCache = function() {
			return this.get();
		};

		/**
		* 
		*/
		this.setValue = function(value) {
			block.block.aceEditor.getSession().setValue(value);
		};

	}

})(jQuery);