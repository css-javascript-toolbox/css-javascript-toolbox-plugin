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
	CJTPackageInstallationForm = {
				
		/**
		* put your comment there...
		* 
		*/
		form : null,
			
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize vars.
			this.form = $('form#package-installation-form');
			// Install package!
			$('#install').click($.proxy(this.install, this));
		},
		
		/**
		* put your comment there...
		* 		
		*/
		install : function() {
			
		}
		
	} // End class.
	
	// Initialize form.
	$($.proxy(CJTPackageInstallationForm.init, CJTPackageInstallationForm));
})(jQuery);