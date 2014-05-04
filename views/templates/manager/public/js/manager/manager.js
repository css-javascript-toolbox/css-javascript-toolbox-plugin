/**
* 
*/
(function($) {
	
	/**
	* 
	*/
	CJTTemplatesManagerForm = {
		
		/**
		* put your comment there...
		* 
		*/
		_onbulkaction : function(event) {
			// Get bulk action list element.
			var actionListId = event.target.id.replace('do', '');
			var actionList = $('form#templates-manager select#'+ actionListId);
			// Get selected action name.
			var actionInfo = actionList.val().match(/^(\w+)(::(\w+))?$/);
			if (actionInfo) { // Only if action selected.
				var selectedTemplatesId = $('input:checkbox[name="id[]"]').serializeObject();
				this.bulkAction(actionInfo[1], selectedTemplatesId['id[]'], actionInfo[3]);
			}
			// Dont do the regular submission!
			event.preventDefault();
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onfilter : function(event) {
			var filterElement = event.target;
			// Get form input field for the filter field.
			var inputFieldName = filterElement.id;
			var inputField = $('form#templates-manager input:hidden[name="' + inputFieldName + '"]');
			// Set the value for the form field to the filter field.
			inputField.val(filterElement.value);
			// Submit the form.
			filterElement.form.submit();
		},

		/**
		* put your comment there...
		* 
		* @param event
		*/
		_onrowaction : function(event) {
			var actionInfo = event.target.href.match(/#(\w+)\((\d+)\)/);
			var action = actionInfo[1];
			var templateId = actionInfo[2];
			switch (action) {
				case 'info':
					var query = {
						view : 'templates/info',
						id : templateId,
						width : '528px',
						height: '385px'
					};
					var uri = parent.CJTBlocksPage.server.getRequestURL('template', 'info', query);
					tb_show(CJT_TEMPLATESManagerI18N.InfoFormTitle, uri);
				break;
				case 'edit':
					this.createNewTemplate(templateId);
				break;
				case 'delete':
					this.deleteTemplates(action, templateId);
				break;
				case 'changeState':
					this.bulkAction(action, templateId,  event.target.parentNode.className);
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
			$('form#templates-manager input[name="id[]"]').prop('checked', newState);
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
		createNewTemplate : function (templateId) {
			var templateId = (templateId === undefined) ? 0 : templateId;
			var operation = templateId ? 'edit' : 'create';
			var query = {
				view : 'templates/template',
				id : templateId,
				width : 800,
				height: 571,
				TB_iframe : true
			};
			var uri = parent.CJTBlocksPage.server.getRequestURL('template', 'edit', query);
			tb_show(CJT_TEMPLATESManagerI18N[operation + 'TemplateDialogTitle'], uri);
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
				return parent.CJTBlocksPage.server.send('templatesManager', action, {ids : ids, params: params})
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
		deleteTemplates : function(action, ids) {
			// Fake Ajax object in case deletion is not confirmed.
			var promised = window.parent.CJTBlocksPage.server.getDeferredObject();
			var confirmed = confirm(CJT_TEMPLATESManagerI18N.confirmDeleteTemplates);
			if (confirmed) {
				promised = this.bulkAction(action, ids);
			}
			return promised;
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Create new Template.
			$('#create-new-template').click($.proxy(function() {
					this.createNewTemplate();
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
			// Filters.
			$('form#templates-manager select.filter').change($.proxy(this._onfilter, this));
		}
		
	} // End form class.

	// Initialize Templates Manager form when document loaded!
	$($.proxy(CJTTemplatesManagerForm.init, CJTTemplatesManagerForm));
})(jQuery);