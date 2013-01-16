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
	var CJTSetupSetupForm = {
		
		/**
		* 
		*/
		server : CJTServer,
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize vars!
			this.activationForm = $('#activation-form');
			// Load activation form.
			var request = {view : 'setup/activation-form', component : {name : $('input:hidden#component').val()}};
			this.activationForm.prop('src', this.server.getRequestURL('setup', 'activationFormView', request))
			// Load/Show frame!
			.css({display : 'block'});
		}

	} // End CJTInstallerNotice.
	
	// Initioalize form when document ready!
	$($.proxy(CJTSetupSetupForm.init, CJTSetupSetupForm));
		
})(jQuery);