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
		* put your comment there...
		* 
		*/
		var inlineElement;
		
		/**
		* put your comment there...
		* 
		* @type String
		*/
		var onset = '';
		
		/**
		* 
		*/
		this.errors = [];
		
		/**
		* 
		*/
		this.fields = {};
				
		/**
		* 
		*/
		this.add = function(name, expression, message) {
			// Set fieldset name.
			name = onset + name;
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
				field = form.prop(field);
			}
			// Make sure its jQuery object!
			field = $(field);
			//Fetch info.
			info.text =  form.find('label[for=' + field.prop('id') + ']').text().replace('*', ''); 
			return info;
		}
		
		/**
		* 
		*/
		this.hasError = function() {
			return this.errors.length ? true : false;
		}
		
		/**
		* 
		*/
		this.onSet = function(name) {
			onset = name;
			return this;
		}
		
		/**
		* put your comment there...
		* 
		* @param tab_name
		* 
		* @returns {Boolean}
		*/
		this.show = function(tbParams, showName) {
			// Thick box URI.
			var thickBoxParameters = '?TB_inline&_TB-PARAMS_&inlineId=' + inlineElement.prop('id');
			// Add tbParams if defined!
			if (tbParams != undefined) {
				thickBoxParameters = thickBoxParameters.replace('_TB-PARAMS_', tbParams);
			}
			// Remove all child elements inside the Error inline element.
			inlineElement.find('ul').remove();
			// Add error list element.
			var errsList = $('<ul class="cjt-error-list"></ul>').appendTo(inlineElement);
			// Build Unordered list of all errors.
			$.each(this.errors, $.proxy(
				function(index, error) {
					var name = '';
					if (showName && error.bname) {
						name = '<span class="name">' + error.name + '</span>: ';
					}
					var message = '<span class="msg">' + error.message + '</span>';
					// Note: name mighy be empty!
					errsList.append('<li>' + name + message + '</li>');
				}, this)
			);
			// Display Thickbox dialog.
			tb_show(CJTSimpleErrorDialogI18N.dialogTitle, thickBoxParameters);
			return this;
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
		if (!(inlineElement = $(form).prop('id'))) {
			inlineElement = (new Date()).getTime();
		}
		// Prefix Dialog Id!
		inlineElement =  'CJTSimpleErrorDialog__' + inlineElement;
		$('<div id="' + inlineElement + '" class="cjt-error-dialog"><div class="cjt-error-dialog-icon"></div></div>').appendTo('body');
		inlineElement = $('#' + inlineElement);
	} // End class.
})(jQuery);
