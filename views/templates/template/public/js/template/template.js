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
	CJTTemplateForm = {

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
			window.parent.tb_remove();
		},
			
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize vars.
			this.form = $('form#item-form');
			// for TABS!!
			this.form.find('#form-tabs').tabs();
			this.initACEEditor();
			// Update button.
			$('#save').click($.proxy(this.save, this));
			// Close button.
			$('#cancel').click($.proxy(this.close, this));
		},

		/**
		* put your comment there...
		* 
		*/
		initACEEditor : function() {
			// ACE Editor.
			this.aceEditor = ace.edit('code');
			this.aceEditor.setTheme('ace/theme/chrome');
			this.aceEditor.getSession().setMode('ace/mode/php');
			this.aceEditor.setShowPrintMargin(false);
			// Use Code-Auto-Completion plugin with the aceEditor.
			ace.pluggable.plugins.cac.apply(this.aceEditor, {
				parser : {modesBaseURI : window.top.CJTBlocksPage.server.ajaxURL.replace('wp-admin/admin-ajax.php', 'wp-content/plugins/css-javascript-toolbox/framework/js/ace/plugins/cac/modes')},
				dialog : {element : this.form.find('.cac')}}
			);
		},
		
		/**
		* put your comment there...
		* 		
		*/
		save : function() {
			var server = window.top.CJTBlocksPage.server;
			var data = $('#item-form').serializeObject();
			data['item[revision][code]'] = this.aceEditor.getSession().getValue();
			server.send('template', 'save', data, 'post')
			.success($.proxy(
				function(response) {
					if (!response.revision.templateId) {
						alert('ERROR SAVIN TEMPLATE');
					}
					else {
						window.parent.location.reload();
						this.close();
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