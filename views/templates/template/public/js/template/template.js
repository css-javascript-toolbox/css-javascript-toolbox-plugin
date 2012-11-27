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
		errors : null,
				
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
		  this.errors = new CJTSimpleErrorDialog(this.form)
		  .onSet('item[template]') // Avoid writing set/array/group name multipe times!
		  	.add('[name]', /^[\w\d\-\_\x20]+$/, CJTTemplateI18N.invalidName)
		  	.add('[type]', /^.+$/, CJTTemplateI18N.cannotBeNull)
		  	.add('[state]', /^.+$/, CJTTemplateI18N.cannotBeNull)
		  .onSet('');
			// for TABS!!
			this.form.find('#form-tabs').tabs();
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
			// Update button.
			$('#save').click($.proxy(this.save, this));
			// Close button.
			$('#cancel').click($.proxy(this.close, this));
		},
		
		/**
		* put your comment there...
		* 		
		*/
		save : function() {
			// initialize!
			var data = $('#item-form').serializeObject();
			// Only SAVE if the form is valid!
			this.isValid(data).done($.proxy(
				function() {
					var server = window.top.CJTBlocksPage.server;
					data['item[revision][code]'] = this.aceEditor.getSession().getValue();
					server.send('template', 'save', data, 'post')
					.success($.proxy(
						function(response) {
							window.parent.location.reload();
							this.close();
						}, this)
					);
				}, this)
			).fail($.proxy(
				function() {
					this.errors.show('width=380&height=170');
				}, this)
			);
		},
		
		/**
		* put your comment there...
		* 
		* @param data
		*/
		isValid : function(data) {
			var promising = $.Deferred();
			// Client side validation
			if (!this.errors.validate().hasError()) {
				// If we're editing the template dont check name uniquness!
				if (data['item[template][id]']) {
					promising.resolve();
					return promising;
				}
				// Otherwise check nane uniqueness!
				var server = window.top.CJTBlocksPage.server;
				// Make sure that the Block name is not taked by antoher Block!
				var request = {
					returns : ['id'],
					filter : {field : 'name', value : this.form.prop('name').value}
				};
				// Make sure that the name is not being used!
				server.send('template', 'getTemplateBy', request)
				.success($.proxy(
					function(response) {
						// FAIL -- Name is being used by antoher Block!!!
						if (response.id) {
							var error = {
									name : this.errors.fetchFieldInfo('item[template][name]').text,
									message:  CJTTemplateI18N.AlreadyInUse
							};
							this.errors.errors.push(error);
							promising.reject();
						}
						else {
							// Successed -- Name is not taken yet!
							promising.resolve();
						}
					}, this)
				);
			}
			else {
				// Client side validatiom faild!
				promising.reject();
			}
			return promising;
		}
		
	} // End class.
	
	// Initialize form.
	$($.proxy(CJTTemplateForm.init, CJTTemplateForm));
})(jQuery);