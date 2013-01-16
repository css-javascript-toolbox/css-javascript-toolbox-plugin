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
	var CJTSetupActivationForm = {
		
		/**
		* put your comment there...
		* 
		*/
		activationForm : null,
		
		/**
		* put your comment there...
		* 
		*/
		errors : null,
		
		/**
		* put your comment there...
		* 
		*/
		todo : null,
		
		/**
		* put your comment there...
		* 
		*/
		server : CJTServer,
		
		/**
		* put your comment there...
		* 
		* @param event
		*/
		_onbeforesubmit : function(event) {
			// Get to do operation!
			this.todo = event.target.name;
		},
		
		/**
		* put your comment there...
		* 
		* @param event
		*/
		_onsubmit : function(event) {
			// validating.
			if (!this.errors.validate().hasError()) {
				// Show progress!
				
				// Requesting the Server!
				var formData = this.activationForm.serializeObject();
				this.server.send('setup', this.todo, formData)
				.success($.proxy(
				  function(state) {
				  	this.reflectState(state);
				  }, this)
				).complete($.proxy(
					function() {
						// Stop progress.
											
					}, this)
				);
			}
			else {
				this.errors.show('width=380&height=170');
			}
			// Reset request parameters.
			this.todo = null;
			// No default actions should be taked!
			event.preventDefault();
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize.
			this.activationForm = $('#cjt-setup-activation-form-view')
			this.errors = new CJTSimpleErrorDialog(this.activationForm)
			// Read current license state!
			this.server.send('setup', 'getComponentLicenseState', {'component[name]' : this.activationForm.prop('component[name]').value})
			.success($.proxy(
				function(state) {
					this.reflectState(state);
				}, this)
			);
			// Add Fields Rule.
			this.errors
				.add('license[name]', /.+/, CJTSetupActivationFormViewDefaultI18N.invalidName)
				.add('license[key]', /.+/, CJTSetupActivationFormViewDefaultI18N.invalidKey)
				.add('component[name]', /.+/, CJTSetupActivationFormViewDefaultI18N.componentNameIsAbsolute);
			// On submit check for activation!
			this.activationForm.find('input:submit').click($.proxy(this._onbeforesubmit, this));
			this.activationForm.submit($.proxy(this._onsubmit, this));
		},
		
		/**
		* put your comment there...
		* 
		* @param state
		*/
		reflectState : function(state) {
			if (state) {
				// Get state message.
				var stateMessage = CJTSetupActivationFormViewDefaultI18N[state.license + 'Activation'];
				// Show state image!
				$('#license-state').addClass(state.license)
				// Also set tooltip for license field element!
				.attr('title', stateMessage);
				// and for KEY input field!
				this.activationForm.find('input:text[name="license[key]"]').attr('title', stateMessage);
			}
		}

	} // End CJTInstallerNotice.
	
	// Initioalize form when document ready!
	$($.proxy(CJTSetupActivationForm.init, CJTSetupActivationForm));
		
})(jQuery);