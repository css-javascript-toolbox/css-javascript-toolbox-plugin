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
	var CJTTinymceParamsView = {
		
		/**
		* 
		*/
		_onclose : function() {
			// Close Thickbox!
			parent.tb_remove();
		},
	
		/**
		* 
		*/
		_ondone : function() {
			parent.CJTServer.send('tinymceBlocks', 'getShortcode', this.form.serializeObject(), 'post')
			.success($.proxy(
				function(response) {
					switch (response.state) {
						case 'invalid':
							// Show invalid messages
							// @TODO Use Simple Error dialog.
							alert(response.content);
						break;
						case 'shortcode-notation':
							// @TODO :Wrap Shortcode content string with a span element for later references when updating is in need. Generate dynamic ID for the span too.
							var shortcodeInstance = response.content;
							alert(shortcodeInstance);
							// Get CJT editor instance.
							var editor = parent.CJT.codeEditor;
							// Place Shortcode string.
							editor.selection.setContent(shortcodeInstance);
							editor.focus();
							// End me!
							this._onclose();
						break;
					}
				}, this)
			);
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize.
			this.form = $('#shortcode-params-form');
			// Bind events!
			$('#btn-close').click($.proxy(this._onclose, this));
			$('#btn-done').click($.proxy(this._ondone, this));
		}
		
	} // End view class.
	
	// Load.
	$($.proxy(CJTTinymceParamsView.init, CJTTinymceParamsView));
})(jQuery);