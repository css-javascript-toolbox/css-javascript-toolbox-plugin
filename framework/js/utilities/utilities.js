/**
* 
*/

var CJTUtilities;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	CJTUtilities = {
		
		/**
		* put your comment there...
		* 
		* @param string
		*/
		parseString : function(str, exp) {
			// Initialize!
			var data = {};
			var property;				
			// Set default expression if not specified.
			exp = exp ? exp : /([^\=\&]+)\=([^\&]+)/g;
			// Get all properties!
			while (property = exp.exec(str)) {
				// Set single item!
				data[property[1]] = property[2];
			}
			return data;
		}
		
	} // End module!
	
})(jQuery);