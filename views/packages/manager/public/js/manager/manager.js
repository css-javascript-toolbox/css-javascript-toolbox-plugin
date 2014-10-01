/**
* 
*/
(function($) {
	
	/**
	* 
	*/
	CJTPackagesManagerForm = {
		
		/**
		* put your comment there...
		* 
		*/
		_onbulkaction : function(event) {
			// Get bulk action list element.
			var actionListId = event.target.id.replace('do', '');
			var actionList = $('form#packages-manager select#'+ actionListId);
			// Get selected action name.
			var actionInfo = actionList.val().match(/^(\w+)(::(\w+))?$/);
			if (actionInfo) { // Only if action selected.
				var selectedPackagesId = $('input:checkbox[name="id[]"]').serializeObject();
				this.bulkAction(actionInfo[1], selectedPackagesId['id[]']);
			}
			// Dont do the regular submission!
			event.preventDefault();
		},

		/**
		* put your comment there...
		* 
		* @param event
		*/
		_onrowaction : function(event) {
			var actionInfo = event.target.href.match(/#(\w+)\((\d+)\)/);
			var action = actionInfo[1];
			var packageId = actionInfo[2];
			switch (action) {
				case 'delete':
					this.deletePackages(action, packageId);
				break;
				 // View package file in new window.
				case 'getLicenseFile':
				case 'getReadmeFile':
					// Initialize request params!
					var requestParams = {packageId : packageId, view : 'packages/raw-file'};
					// Get file content from server.
					CJTServer.send('package', action, requestParams, 'get', 'html')
					.success($.proxy(
						function(content) {
							// Open in new window.
							var wndFile = window.open('', '', 'width=700, height=550, left=160');
							wndFile.document.write(content);
						}, this)
					)
				break;
			}
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onselectrows : function(event) {
			var newState = $(event.target).prop('checked');			
			// Check/Uncheck all rows.
			$('form#packages-manager input[name="id[]"]').prop('checked', newState);
			// Check/Uncheck the other select-all checkbox.
			$('input:checkbox.select-all').each($.proxy(
				function(index, item) {
					if (item !== event.target) {
						$(item).prop('checked', newState);
					}
				}
			, this )
			);
		},
		
		/**
		* put your comment there...
		* 
		* @param actionName
		* @param id
		*/
		deletePackages : function(actionName, ids) {
			// Fake Ajax object in case deletion is not confirmed.
			var promised = CJTServer.getDeferredObject();
			var confirmed = confirm(CJT_PACKAGESManagerI18N.confirmDelete);
			if (confirmed) {
				promised = this.bulkAction(actionName, ids);
			}
			return promised;
		},
		
		/**
		* put your comment there...
		* 
		*/
		installPackage : function (templateId) {
			var query = {
				view : 'packages/install',
				uploaderControllerName : 'packageFile',
				uploaderActionName : 'install',
				width : 470,
				height: 115,
				TB_iframe : true
			};
			var uri = CJTServer.getRequestURL('packages', 'display', query);
			tb_show(CJT_PACKAGESManagerI18N.installPackageFormTitle, uri);
		},
		
		/**
		* put your comment there...
		* 
		* @param action
		* @param ids
		*/
		bulkAction : function(action, ids) {
			// Action and Ids must be valid!
			if (action && ids) {
				// Allow passing single id!
				if (!$.isArray(ids)) {
					ids = [ids];
				}
				return CJTServer.send('packages', action, {ids : ids})
				.success($.proxy(function() {
					// Refresh the list!
					window.location.reload();
					}, this)
				);
			}
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Create new Template.
			$('#install-package').click($.proxy(function() {
					this.installPackage();
				}, this)
			);
			// Single row actions mixed with regular actions
			// plus view packages file action!
			$('.row-actions span a, .view-package-file').click($.proxy(this._onrowaction, this));
			// Because we're using 'action' query variable for Controller Request purposes
			// bulk actions lists should use name='action' name!
			// Set Id instead of name so value can be retrieved too!
			$('select[name=action]').removeAttr('name').prop('id', 'action');
			$('select[name=action2]').removeAttr('name').prop('id', 'action2');
			// Bulk actions.
			$('#doaction, #doaction2').click($.proxy(this._onbulkaction, this));
			// Chech/Uncheck rows.
			$('input:checkbox.select-all').change($.proxy(this._onselectrows, this));
		}
		
	} // End form class.

	// Initialize Templates Manager form when document loaded!
	$($.proxy(CJTPackagesManagerForm.init, CJTPackagesManagerForm));
})(jQuery);