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
		* @param event
		*/
		_ontypechanged : function(event) {
			// Read vars!
			var list = event.target;
			var typeName = list.value;
			// Type name once set cannot be changed again unless the dialog is cl0sed and re-opened!
			// It was better to ask user for the type before showing the form like 'creating javascript template,
			// or creating 'css template' or any specific type! doesn't make sense to allow changing the type more than one time!
			if (typeName) {
				// Ask for confirmation only when creating new template only!
				if (this.form.prop('item[template][id]').value || confirm(CJTTemplateI18N.confirmSetType)) {
					// Set mode name.
					var modePath =  'ace/mode/' + typeName;
					this.aceEditor.getSession().setMode(modePath);
					// Disable the list.
					$(list).prop('disabled', 'disabled');
					// Disable fields doesn't be in the submission! 
					// Add hidden field with the selected value
					$(list).after('<input type="hidden" name="' + list.name + '" value="' + list.value + '" />');
					// Don't leave two items with the same names!
					list.name = '';
				}
				else {
					// Clear selection and allow changing it!
					 typeName = event.target.value = '';
				}
			}
			// Enable editor if there is a type selected or disable it if non types is selected!
			this.aceEditor.setReadOnly(typeName ? false : true);
		},
		
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
		  	.add('[name]', /^.+$/, CJTTemplateI18N.invalidName)
		  	.add('[type]', /^.+$/, CJTTemplateI18N.languageIsNotSelected)
		  	.add('[state]', /^.+$/, CJTTemplateI18N.stateIsNotSelected)
		  .onSet('');
			// for TABS!!
			this.form.find('#form-tabs').tabs();
			// ACE Editor.
			this.aceEditor = ace.edit('code');
			this.aceEditor.setShowPrintMargin(false);
			// Update button.
			$('#save').click($.proxy(this.save, this));
			// Close button.
			$('#cancel').click($.proxy(this.close, this));
			// Change AceEditor Language when type changed!
			$('select#type').change($.proxy(this._ontypechanged, this))
			// Set mode when page startup!
			.trigger('change');
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
							// Refresh only if not running on the main CJT manager page!
							if (window.parent.CJTBlocksPage === undefined) {
								window.parent.location.reload();
							}
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