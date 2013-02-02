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
	var CJTExtensionsPluginsListViewDefault = {
		
		/**
		* put your comment there...
		* 
		*/
		server : CJTServer,
		
		/**
		* put your comment there...
		* 
		* @param event
		*/
		_onsetup : function(event) {
			// Getting component name from clicked link!
			var component = CJTUtilities.parseString(decodeURI(/\#(.+)/.exec(event.target.href)[1]));
			// Build request parameters.
			var request = {
				view : 'setup/activation-form',
				component : component,
				/* Thickbox */
				TB_iframe : true,
				height : 280
			};
			// Get URL to activation form.
			var url = this.server.getRequestURL('setup', 'activationFormView', request);
			// Show dialog!
			tb_show(CJTExtensionsPluginsListViewDefaultI18N.activationFormTitle, url);
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			$('.license-key a').click($.proxy(this._onsetup, this));
		}

	}
	
	// Initioalize form when document ready!
	$($.proxy(CJTExtensionsPluginsListViewDefault.init, CJTExtensionsPluginsListViewDefault));
		
})(jQuery);