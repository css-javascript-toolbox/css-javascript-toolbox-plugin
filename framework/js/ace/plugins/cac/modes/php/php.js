/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @param id
	* @param deps
	* @param factory
	*/
	define('ace/plugins/cac/modes/php', 
		
		/**
		* 
		*/
		function() {
			
			/**
			* 
			*/
			var mode = function() {/* No Custom initialization/construction required yet */}
			
			// Extend mode base prototype.
			mode.prototype = new ace.pluggable.plugins.cac.prototypes.mode();
			
			// Return CSS object.
			return new mode();
		}
	)
})(jQuery);