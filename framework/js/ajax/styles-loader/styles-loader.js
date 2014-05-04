/**
* 
*/

var StylesLoader;
/**
* 
*/
(function($) {
	
	/**
	* 
	*/
	StylesLoader = function(styles) {
		
		/**
		* 
		*/
		this.load = function() {
			$.each(styles,
				$.proxy(function(index, style) {
					var link = '<link href="' + style.src + '" rel="stylesheet" type="text/css" />';
					$('head').append(link);
				}, this)
			);
		}
		
	} // End class.
	
})(jQuery);