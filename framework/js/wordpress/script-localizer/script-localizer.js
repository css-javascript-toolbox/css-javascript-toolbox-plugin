/**
* 
*/

var CJTWPScriptLocalizer;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	*/
	CJTWPScriptLocalizer = function(scripts) {
		
		/**
		* 
		*/
		this.localize = function() {
			var localization = '';
			// For every script file get JS localization array.
			$.each(scripts, $.proxy(
				function(index, script) {
					// Is the Script localized?
					if (script.extra.data != undefined) {
						// Merge all localization vars into single string!
						localization += script.extra.data + ";\n";
					}
				}, this)
			);
			// Add single script tag for all the localization variables.
			var scriptTag = document.createElement('script');
			scriptTag.type = "text/javascript";
			scriptTag.innerHTML = localization;
			// Evaluation!
			document.getElementsByTagName('head')[0].appendChild(scriptTag);
		}
		
	} // End class.
	
})(jQuery);