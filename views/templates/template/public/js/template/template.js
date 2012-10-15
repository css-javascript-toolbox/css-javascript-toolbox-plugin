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
	var CJTTemplateForm = {
		
		/**
		* put your comment there...
		* 		
		*/
		close : function() {
			
		},
			
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Update button.
			$('#update').click($.proxy(this.update, this));
			// Close button.
			$('#close').click($.proxy(this.close, this));
		},

		/**
		* put your comment there...
		* 		
		*/
		update : function() {
			var server = window.top.CJTBlocksPage.server;
			server.send('template', 'update', $('#item-form').serializeObject(), 'post')
			.success($.proxy(
				function(response) {
					if (!response.guid) {
						alert('ERROR SAVIN TEMPLATE');
					}
					else {
						
					}
				}, this)
			)
			.error($.proxy(
				function() {
					
				}, this)
			)
		}
		
	} // End class.
	
	// Initialize form.
	$($.proxy(CJTTemplateForm.init, CJTTemplateForm));
})(jQuery);