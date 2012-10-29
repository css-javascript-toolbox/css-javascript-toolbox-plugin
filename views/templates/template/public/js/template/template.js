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
		* 
		*/
		aceEditor : null,
				
		/**
		* put your comment there...
		* 
		*/
		form : null,

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
			// Initialize vars.
			this.form = $('form#item-form');
			// Accordion.
			this.form.find('#form-accordion').accordion();
			// ACE Editor.
			this.aceEditor = ace.edit('code');
			this.aceEditor.setTheme('ace/theme/chrome');
			this.aceEditor.getSession().setMode('ace/mode/css');
			this.aceEditor.setShowPrintMargin(false);
			// Use Code-Auto-Completion plugin with the aceEditor.
			ace.pluggable.plugins.cac.apply(this.aceEditor, {
				parser : {modesBaseURI : window.top.CJTBlocksPage.server.ajaxURL.replace('wp-admin/admin-ajax.php', 'wp-content/plugins/css-javascript-toolbox/framework/js/ace/plugins/cac/modes')},
				dialog : {element : this.form.find('.cac')}}
			);
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