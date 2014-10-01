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
	 window.CJTPackageInstallationForm = {
				
		/**
		* put your comment there...
		* 
		*/
		fileUploader : null,
		
		/**
		* put your comment there...
		* 
		*/
		_oncomplete : function() {
			$('#install').prop('disabled', '');
		},

		/**
		* put your comment there...
		* 
		*/
		_onfail : function(error)	{
			alert(error.msg);
		},

		/**
		* put your comment there...
		* 
		*/
		_onsuccess : function()	{
			// Notify!
			alert(CJTInstallI18N.installationSuccessed);
			// Parent refresh.
			parent.parent.location.reload();
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize vars.
			this.fileUploader = $('#fileUploader')[0];
			// Set uploader form action when ready
			this.fileUploader.addEventListener('load', $.proxy(
				function() {
					// Repoint to module if required
					if (CJTPackageInstallationFormParams.uploaderModuleName) {
						// Get form
						var uploadForm = this.fileUploader.contentDocument.getElementsByTagName('form')[0];
						// Query string
						var queryString = {};
						queryString[CJTPackageInstallationFormParams.cjtModuleParamName] = CJTPackageInstallationFormParams.uploaderModuleName;
						// Repoint uploader frame to embeddeder action
						uploadForm.action = parent.CJTServer.getRequestURL(
							CJTPackageInstallationFormParams.uploaderControllerName,
							CJTPackageInstallationFormParams.uploaderActionName,
							queryString
						);
					}
				}, this)
			);
			// Set file uploaded src, load uploaded view!
			this.fileUploader.src = parent.CJTServer.getRequestURL('packageFile', 'install', {view : 'uploader/single'});
			// Install package!
			$('#install').click($.proxy(this.install, this));
		},
		
		/**
		* put your comment there...
		* 		
		*/
		install : function() {
			// Initialize.
			var fileUploader = this.fileUploader.contentWindow.CJTUploader;
			// Submit the uploader form.
			fileUploader.upload('CJTPackageInstallationForm', function() {
				// File being send to the server!
				// Show progress.
				$('#install').prop('disabled', 'disabled');
			});
			
		}
		
	} // End class.
	
	// Initialize form.
	$($.proxy(window.CJTPackageInstallationForm.init, window.CJTPackageInstallationForm));
})(jQuery);