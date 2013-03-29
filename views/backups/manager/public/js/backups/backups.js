/**
*
*/

/**
*
*
*
*
*/
(function($) {

	/**
	*
	*
	*
	*
	*/
	var CJTBackupsForm = {

		/**
		* put your comment there...
		* 
		*/
		backupsForm : null,
		
		/**
		* put your comment there...
		* 
		* @type String
		*/
		controllerName : '',
		
		/**
		* put your comment there...
		* 
		*/
		createBackupForm : null,

		/**
		* put your comment there...
		* 
		*/
		server : null,
		
		/**
		* put your comment there...
		* 
		*/
		_oncreate : function() {
			var backupName = this.createBackupForm.find('input[name="name"]');
			if (!backupName.val()) {
				alert(CJTBackupsI18N.BackupNameCannotBeNull);
			}
			else {
				// Prepare request data.
				var request = {
					name : backupName.val(), // Backup name.
					rowIndex : this.backupsForm.backupsList.find('.backup-row').length
				};
				// Disable fields + show progress.
				var formElements = this.createBackupForm.find('input');
				formElements.prop('disabled', 'disabled');
				backupName.addClass('loading');
				// Initialize vars used inside callbacks.
				var noBackupsMessage = this.backupsForm.find('#no-backups');
				// Send request to server.
				this.server.send(this.controllerName, 'create', request, 'get', 'html')
				.success(
					function(backupRowHTML) {
						// If this is the first backup hide "No Backups" message.
						if (noBackupsMessage.css('display') != 'none') {
							noBackupsMessage.css('display', 'none');
						}
						// Add backup row.
						var backupRow = CJTBackupsForm.backupsForm.backupsList.append(backupRowHTML).children().last();
						// Bind/Activate backup row tasks.
						CJTBackupsForm.backupsForm.backupsList.bindBackupsTasks(backupRow);
						// Clear backup name field.
						backupName.val('');
					}
				)
				.complete(
					function() {
						// Enable fields + Hide loading Image.
						formElements.prop('disabled', '');
						backupName.removeClass('loading');
					}
				);
			}
		},
				
		/**
		*
		*
		*
		*
		*/
		_ondelete : function(event) {
			// Get backup Id from link href attribute, cast to Integer.
		  var backupId = parseInt(event.target.href.match(/#(\d+)/)[1]);
		  var backupRow = CJTBackupsForm.backupsForm.backupsList.find('#backup-row-' + backupId);
		  // Confirm delete backup.
		  var backupName = backupRow.find('.backup-name').text();
		  var confirmDelete = confirm(CJTBackupsI18N.confirmDelete.replace('{BACKUP-NAME}', backupName));
			if (confirmDelete) {
			  // Show loading image.
			  var jLink = $(event.target);
			  jLink.CJTLoading();
				// Send request to server.
				var request = {id : backupId};
				this.server.send(this.controllerName, 'delete', request, 'get', 'html')
				.success(
					function() { 
						  // Hide Backup row slowely and then delete it.
						  backupRow.fadeOut(1000, 
				  			function() {
				  				// Iterate over next backups row in reverve order.
				  				// Swap all classes start from the deleted row.
				  				var followingBackups = $(backupRow.nextAll().get().reverse());
				  				// Refresh list color (alternate and normal rows)!
				  				followingBackups.each(
				  					function() {
				  						// Get previous row CSS classes.
											this.className = $(this).prev().get(0).className;
				  					}
				  				);
				  				// Delete it after fading out complete.
				  				backupRow.remove();
				  				// If all rows has been deleted show no-backups message.
				  				var rowsCount = CJTBackupsForm.backupsForm.backupsList.find('.backup-row').length;
				  				if (!rowsCount) {
				  					var noBackupsMessage = CJTBackupsForm.backupsForm.find('#no-backups');
				  					noBackupsMessage.css({display : 'block'});
				  				}
				  			}
						  );
					}
				)
				.error(
					function() {
						// Hide loading Image.
						jLink.CJTLoading({loading : false, ceHandler : CJTBackupsForm._ondelete});
					}
				);		
			}
		},

		/**
		*
		*
		*
		*
		*/
		_onrestore : function(event) {
			// Get backup Id from link href atrribute.
			var backupId = event.target.href.match(/#(\d+)/)[1];
			var blocksPageWindow = window.parent;
			// Redirect with backup id attached to query string parameters.
			var backupIdParameter = 'backupId=' + backupId;
			// If backupId parameter found replace id, if not add it.
			if (/backupId=\d+/.test(blocksPageWindow.location.href)) {
				blocksPageWindow.location.href = blocksPageWindow.location.href.replace(/backupId=\d+/, backupIdParameter);
			}
			else {
			  blocksPageWindow.location.href += ('&' + backupIdParameter);
			}
			// Destroy backup manager form.
			blocksPageWindow.tb_remove();
		},
		
		/**
		*
		*
		*
		*
		*/
		init : function() {
			// Initialize vars.
			this.server = window.parent.CJTBlocksPage.server;
			// Get create backup form #ref.
			this.createBackupForm = $('#cjt-backups-form');
			// Initialize backups list elements.
			this.backupsForm = $('#backups');
			this.backupsForm.backupsList = this.backupsForm.find('#backups-list');
			// Define method to bind backup rows tasks (e.g delete, restore) events.
			this.backupsForm.backupsList.bindBackupsTasks = function(context) {
				// If context is undefined bind all rows inside backups list.
				context = (context == undefined) ? CJTBackupsForm.backupsForm.backupsList : context;
				// Bind events for all rows inside $context.
				context.find('.restore').click($.proxy(CJTBackupsForm._onrestore, CJTBackupsForm));
				context.find('.delete').click($.proxy(CJTBackupsForm._ondelete, CJTBackupsForm))
			};
			// Other state variables.
			this.controllerName = this.createBackupForm.find('input#controllerName').val();
			// Create backup form event.
			this.createBackupForm.find('#createBackup').click($.proxy(CJTBackupsForm._oncreate, CJTBackupsForm));
			// Activate backups row tasks (eg delete, restore).
			this.backupsForm.backupsList.bindBackupsTasks();
		}
						
	};
	
	// Initialize form script. Call init method without change context.
	$($.proxy(CJTBackupsForm.init, CJTBackupsForm));
	
})(jQuery);