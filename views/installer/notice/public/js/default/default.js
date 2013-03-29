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
	var CJTInstallerNotice = {
		
		/**
		* put your comment there...
		* 
		*/
		notice : null,
		
		/**
		* put your comment there...
		* 
		*/
		_ondismissnotice : function() {
			// Never  show dimisss message again!
			if (confirm(CJTInstallerNoticeDefaultI18N.confirmationMessage)) {
				CJTServer.send('installer', 'dismissNotice').success($.proxy(
				   function() {
				   	 // Hide notice!
			   		 this.notice.fadeOut('slow', function() {this.remove()});
				   }, this)
				);
			}
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize vars.
			this.notice = $('.cjt-installation-notice');
			// Bind events!
			this.notice.find('a.dismiss').click($.proxy(this._ondismissnotice, this));
		}

	} // End CJTInstallerNotice.
	
	// Initioalize form when document ready!
	$($.proxy(CJTInstallerNotice.init, CJTInstallerNotice));
		
})(jQuery);