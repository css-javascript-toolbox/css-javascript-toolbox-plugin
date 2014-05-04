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
	window.CJTUploader = {
				
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
			this.form = document.forms[0];
		},
		
		/**
		* put your comment there...
		* 
		* @param Promise Object Name
		* @param callback
		*/
		upload : function(pon, callback) {
			// Don't submit unless a file is selected.
			if (this.form.fileToUpload.value != '') {
				// Hold pon object along with the request.
				this.form.pon.value = pon;
				// Notify requesting server.
				callback(true);
				// Submit the form.
				this.form.submit();
			}
		}
		
	} // End class.
	
	// Initialize form.
	$($.proxy(window.CJTUploader.init, window.CJTUploader));
})(jQuery);