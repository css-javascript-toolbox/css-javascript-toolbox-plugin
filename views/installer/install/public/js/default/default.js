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
	var CJTInstallerForm = {

		/**
		* put your comment there...
		* 
		*/
		installationForm : null,
		
		/**
		* Start installation process.
		* 
		* @return void
		*/
		_oninstall : function() {
			var operations = [];
			// Don't process if not confirmed!
			if (!confirm(CJTInstallerDefaultI18N.confirmMessage)) {
				return;
			}
			// Operations to process.
			this.installationForm.find('ul.installation-list').each($.proxy(
				function(listIndex, list) {
					var typeName = list.className.split(' ').pop();
					$(list).find('li').each($.proxy(
						function(index, li) {
							var operation = li.className.split(' ');
							// Include operation if not installed (has no'installed' class ),
							// AND  if the operation is mandatory OR its optional with the checkbox checked!
							var operationCheckbox = $(li).find('input:checkbox');
							var included = (operation[1] === undefined) && // Not insatlled (not installed class!)
																						 	((operationCheckbox.get(0).className != 'optional') || (operationCheckbox.prop('checked')));
							// Build operation object!
							if (included) {
								// Add to operations list!
								operations.push({type : typeName, name : operation[0]});
							}
						}, this)
					);
				}, this)
			);
			// Create installer object!
			var installer = new CJTInstaller(operations);
			// Disable form to disallow repeating actions!
			this.installationForm.find('input').prop('disabled', 'disabled');
			//For each operation progress the installation!
			installer.install($.proxy(
				function(promise, operationId, operation) {
					// Get li node from operation details.
					var li = this.installationForm.find('ul.installation-list.' + operation.type + ' li.' + operation.name);
					// Show loading progress and hide checkbox.
					li.addClass('progress').find('input:checkbox').hide();
					// We need to know when operation faild or success.
					promise.done($.proxy( // Operation installation successed.
						function() {
							// Mark as installed!
							li.addClass('installed');
						}, this)
					).fail($.proxy( // Operation installation faild!
						function() {
							console.log(operation.name + ' is faild');
						}, this)
					).complete($.proxy(
					  function() {
					  	// Stop progressing!
					  	li.removeClass('progress');
					  }, this)
					);
					// Always do the operation!
					return true;
				}, this)
			).done($.proxy( // Overall installation completed with success!
				function() {
					// Show success message!
					alert(CJTInstallerDefaultI18N.successMessage);
					// Setup START button!
					this.installationForm.find('input:button')
					// Enable button.
					.prop('disabled', '')
					// Change button caption to done!
					.val(CJTInstallerDefaultI18N.startButtonText)
					// Unbind uninstall method!
					.unbind('click', this._oninstall)
					// Bind to start method.
					.bind('click', this._onstart);
				}, this)
			).fail($.proxy( // Overall installation completed with error!
				function() {
					// Enable form to accept other actions!
					this.installationForm.find('input').prop('disabled', 'disabled');
				}, this)
			);
		},
		
		/**
		* 
		*/
		_onstart : function() {
			// Just refresh window and server will do the rest!
			window.location.reload();
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize vars!
			this.installationForm = $('form[name="installation-form"]');
			// Bind events!
			this.installationForm.find('input:button').click($.proxy(this._oninstall, this));
		}

	} // End CJTInstallerform.
	
	// Initioalize form when document ready!
	$($.proxy(CJTInstallerForm.init, CJTInstallerForm));
		
})(jQuery);