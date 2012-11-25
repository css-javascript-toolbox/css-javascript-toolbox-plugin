/**
* 
*/

var CJTSimpleErrorDialog;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @param form
	*/
	CJTSimpleErrorDialog = function(form) {
		
		/**
		* 
		*/
		this.errors;
		
		/**
		* 
		*/
		this.fields = {};
		
		/**
		* 
		*/
		this.inlineElement;
				
		/**
		* 
		*/
		this.add = function(name, expression, message) {
			this.fields[name] = {
					name: name,
					expression: expression,
					message : message
			}
			return this;
		}
		
		/**
		* 
		*/
		this.clear = function() {
			this.errors = [];
			return this;
		}
		
		/**
		* 
		*/
		this.fetchFieldInfo = function(field) {
			// Initialize vars!
			var info = {};
			// Use Object element directly of get it if the name is passed!
			if (typeof field != 'object') {
				if (!this.fields[field]) {
					throw 'Field name doesn\'t exists';
				}
				// Fetch field by name!
				field = this.fields[field][element];
			}
			// Make sure its jQuery object!
			field = $(field);
			//Fetch info.
			info.text =  form.find('label[for=' + field.prop('id') + ']').text(); 
			return info;
		}
		
		/**
		* 
		*/
		this.hasError = function() {
			return this.errors.length ? true : false;
		}
		
		/**
		* put your comment there...
		* 
		* @param tab_name
		* 
		* @returns {Boolean}
		*/
		this.show = function(tbParams, promising) {
			// Dont show unless we've error!
			if (this.hasError()) {
				promising.reject();
				// Thick box URI.
				var thickBoxParameters = '?TB_inline&_TB-PARAMS_&inlineId=' + this.inlineElement.prop('id');
				// Add tbParams if defined!
				if (tbParams != undefined) {
					thickBoxParameters = thickBoxParameters.replace('_TB-PARAMS_', tbParams);
				}
				// Remove all child elements inside the Error inline element.
				this.inlineElement.empty();
				// Add error list element.
				var errsList = $('<ul class="cjt-error-list"></ul>').appendTo(this.inlineElement);
				// Build Unordered list of all errors.
				$.each(this.errors, $.proxy(
					function(index, error) {
						var message = (error.name ? (error.name + ': ') : '') + error.message;
						errsList.append('<li>' + message + '</li>');
					}, this)
				);
				// Display Thickbox dialog.
				tb_show(CJTSimpleErrorDialogI18N.dialogTitle, thickBoxParameters);
			}
			else {
				promising.resolve();
			}
			return promising;
		}

		/**
		* 		
		*/
		this.validate = function() {
			// Clear errors!
			this.clear();
			// Validate fields.
			$.each(this.fields, $.proxy(
				function(key, field) {
					var element = $(form.prop(field.name));
					var value;
					// Handle various HTML element types!
					switch (element.type) {
						case 'checkbox':
							value = element.prop('checked');
						break;
						default:
							value = element.val();
						break;
					}
					// Check the value matched the expression!
					if (!value.match(field.expression)) {
						// use label text as name!
						var name = this.fetchFieldInfo(element).text;
						// Add error!
						this.errors.push({name : name, message: field.message});
					}
				}, this)
			);
			return this;
		}
		
		// If there is no form id use current time as unique Id.
		if (!(this.inlineElement = $(form).prop('id'))) {
			this.inlineElement = (new Date()).getTime();
		}
		// Prefix Dialog Id!
		this.inlineElement =  'CJTSimpleErrorDialog__' + this.inlineElement;
		$('<div id="' + this.inlineElement + '" class="cjt-error-dialog"></div>').appendTo('body');
		this.inlineElement = $('#' + this.inlineElement);
	} // End class.
})(jQuery);
