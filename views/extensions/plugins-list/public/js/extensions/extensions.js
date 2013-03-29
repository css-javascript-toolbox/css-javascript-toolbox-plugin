/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* 
	*/
	var CJTExtensionsPluginsListViewExtensions = {
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// work with title
			$('.wrap>h2')
			// Change Plugins title to Extensions!
			.text(CJTExtensionsPluginsListViewExtensionsI18N.title)
			// Show title!
			.css({visibility : 'visible'});
		}

	}
	
	// Initioalize form when document ready!
	$($.proxy(CJTExtensionsPluginsListViewExtensions.init, CJTExtensionsPluginsListViewExtensions));
		
})(jQuery);