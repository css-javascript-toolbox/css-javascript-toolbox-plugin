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
		action : null,
		
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
			// validating.
			if (!this.errors.validate().hasError()) {
				this.todo = event.target.name;
				//Reset last executed action!
				this.action = null;
				// User must confirm if its acivation?
				switch (this.todo) {
					case 'reset':
						// Request reset action instead of default action 'license'!
						this.action = this.todo;
					case 'activate' :
					case 'deactivate':
						// Don't submit if not confirmed!
						if (!confirm(CJTSetupActivationFormViewDefaultI18N['confirm' + this.todo])) {
							// Prevent submitting!
							event.preventDefault();
						}
					break;
				}
			}
			else {
				// Show error dialog!
				this.errors.show('width=380&height=170');
				// Prevent submitting!
				event.preventDefault();
			}
		},
		
		/**
		* put your comment there...
		* 
		* @param event
		*/
		_onsubmit : function(event) {
			// Gathering data!
			var formData = this.activationForm.serializeObject();
			// Show progress.
			var progress = {};
			$('#license-state').removeClass().addClass('loading')
			// Show element!
			.css({visibility : 'visible'});
			// Reset request state element!
			$('#request-state').removeClass().text(CJTSetupActivationFormViewDefaultI18N[this.todo + 'ActionStateName'] + ' ...')
			// Show element!
			.css({visibility : 'visible'});
			// Disable form fields!
			progress.inputs = this.activationForm.find('input').prop('disabled', 'disabled').addClass('disabled');
			// Pass licene action along out with the form data!
			var request = $.extend(formData, {licenseAction : this.todo});
			// Requesing server!
			var action = this.action ? this.action : 'license';
			this.server.send('setup', action, request)
			.success($.proxy(
				function(state) {
				  this.reflectState(state);
				}, this)
			).complete($.proxy(
				function() {
					// Stop progress & enable form!
					progress.inputs.prop('disabled', '').removeClass('disabled');
				}, this)
			);
			// Prevent normal submission!
			event.preventDefault();
		},

		/**
		* Switch action button names (activate, deactivate)
		* based on the current license state!
		* 
		* @param state
		*/
		buttonsState : function(state) {
			// Action button should be filled inside Switch statement
			// to be used after the statement!
			var actionButtonName;
			switch (state) {
				case 'deactivate':
					// Switch to activate!
					actionButtonName = 'activate';					
					// Add reset button after the action button!
					$('<input type="submit" id="reset-button" name="reset" />').insertAfter($('#action-button'))
					// Configure!
					.prop('value', CJTSetupActivationFormViewDefaultI18N.resetButtonCaption)
					.click($.proxy(this._onbeforesubmit, this));
				break;
				case 'reset':
					// Clear fields value!
					this.activationForm.find('input:text').val('');
				case 'activate':
					var map = {reset : 'activate', activate : 'deactivate'};
					// Switch to mapped action name!
					actionButtonName = map[state];
					// Remove reset button.
					$('#reset-button').remove();
				break;
			}
			// Set action button name and caption.
			$('#action-button').prop('name', actionButtonName)
			.prop('value', CJTSetupActivationFormViewDefaultI18N[actionButtonName + 'ActionButtonCaption'])
			//show button.
			.css({visibility : 'visible'});
		},
				
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize.
			this.activationForm = $('#cjt-setup-activation-form-view')
			this.errors = new CJTSimpleErrorDialog(this.activationForm)
			// Read state!
			this.readState().success($.proxy(
				function() {
					// Add Fields Rule.
					this.errors
						.add('license[name]', /.+/, CJTSetupActivationFormViewDefaultI18N.invalidName)
						.add('license[key]', /.+/, CJTSetupActivationFormViewDefaultI18N.invalidKey)
						.add('component[name]', /.+/, CJTSetupActivationFormViewDefaultI18N.componentNameIsAbsolute);
					// On submit check for activation!
					this.activationForm.find('input#action-button, input#check-button').click($.proxy(this._onbeforesubmit, this));
					this.activationForm.submit($.proxy(this._onsubmit, this));
				}, this)
			)
		},
		
		/**
		* put your comment there...
		* 
		*/
		readState : function() {
			// Gathering request parameters!
			var request = {'component[pluginBase]' : this.activationForm.prop('component[pluginBase]').value};
			// Read current license state!
			var promise = this.server.send('setup', 'getState', request)
			.success($.proxy(
				function(state) {
					this.reflectState(state);
				}, this)
			);
			return promise;
		},
		
		/**
		* put your comment there...
		* 
		* @param state
		*/
		reflectState : function(state) {
			// Empty state means that there is no request made or its has been rested by user!
			if (state) {
				// If the state changed then state.action will be available thereforth buttons state must be reflect that too!
				if (state.action !== undefined) {
					this.buttonsState(state.action);
				}
				// Reflect the request state (valid license, invalid license and request error) by text message.
				var currentActionName = this.todo ? this.todo : state.action;
				var requestStateMessage = CJTSetupActivationFormViewDefaultI18N[currentActionName + state.response.license + 'RequestMessage'];
				$('#request-state').text(requestStateMessage)
				.css({visibility : 'visible'});
				// Reflect License Key state (request: valid, request: invalid, request:error, key: activated and key: deactivated).
				// This can be done by using 'action' (it will eb available only with success activate and success deactivate!)
				// if not action then use request state!
				var licenseState = (state.action !== undefined) ? state.action : state.response.license;
				var stateNameCaption = CJTSetupActivationFormViewDefaultI18N[licenseState + 'StateName'];
				// Show state image!
				$('#license-state').removeClass().addClass(licenseState)
				// License state title/tooptip to reflect the state name.
				.prop('title', stateNameCaption)
				.css({visibility : 'visible'});
				// and for KEY input field!
				this.activationForm.find('input:text[name="license[key]"]').attr('title', requestStateMessage);
			}
			else {
				// Reset
				this.buttonsState('reset');
			}
		}

	} // End CJTInstallerNotice.
	
	// Initioalize form when document ready!
	$($.proxy(CJTSetupActivationForm.init, CJTSetupActivationForm));
		
})(jQuery);