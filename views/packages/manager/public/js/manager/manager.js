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
				this.bulkAction(actionInfo[1], selectedPackagesId['id[]'], actionInfo[3]);
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
		*/
		installPackage : function (templateId) {
			var query = {
				view : 'packages/install',
				width : 470,
				height: 115,
				TB_iframe : true
			};
			var uri = CJTServer.getRequestURL('packages', 'display', query);
			tb_show('Install Package File', uri);
		},
		
		/**
		* put your comment there...
		* 
		* @param action
		* @param ids
		*/
		bulkAction : function(action, ids, params) {
			// Action and Ids must be valid!
			if (action && ids) {
				// Allow passing single id!
				if (!$.isArray(ids)) {
					ids = [ids];
				}
				return CJTServer.send('packagesManager', action, {ids : ids, params: params})
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
		* @param ids
		*/
		uninstallPackage : function(action, ids) {
			
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
			// Single row actions.
			$('.row-actions span a').click($.proxy(this._onrowaction, this));
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