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
	
	var CJTSettingsForm = {
		
		/**
		* put your comment there...
		* 
		*/
		form : null,
		
		/**
		* put your comment there...
		* 
		*/
		server : window.parent.CJTBlocksPage.server,
		
		/**
		* 
		*/
		tabs : null,
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Use jQuery Tabs Plugin for settings page.
			this.tabs = $('#settings-tabs').tabs();
			this.form = $('form#settings-page');
			// Bind submit button event.
			this.form.submit($.proxy(this._onsubmit, this));
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onsubmit : function() {
			// Show loading.
			this.tabs.tabs('option', 'disabled', true);
			// Send request to server.
			var f = this.form.serialize();
			this.server.send('settings', 'save', this.form.serializeObject(), 'post')
			.complete(
				function() {
					CJTSettingsForm.tabs.tabs('option', 'disabled', false);
					window.parent.tb_remove();
				}
			);
			// Supress normal form submission.
			return false;
		}
		
	} // End CJTSettingsForm class.
	
	// Initialize settings form.
	$($.proxy(CJTSettingsForm.init, CJTSettingsForm));
	
})(jQuery);