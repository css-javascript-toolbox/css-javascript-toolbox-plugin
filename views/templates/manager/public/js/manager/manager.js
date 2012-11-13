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
		bulkAction : function(event) {
			// Get bulk action list element.
			var actionListName = event.target.id.replace('do', '');
			var actionList = $('form#templates-manager select[name="'+ actionListName + '"]');
			// Get selected action name.
			var action = actionList.val();
			// Do action.
			document.location.href = parent.CJTBlocksPage.server.switchAction(action, document.location.href);
			// Dont do the regular submittion!
			event.preventDefault();
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
				height: 600,
				TB_iframe : true
			};
			var uri = parent.CJTBlocksPage.server.getRequestURL('template', 'edit', query);
			tb_show(CJT_TEMPLATESManagerI18N[operation + 'TemplateDialogTitle'], uri);
		},

		/**
		* put your comment there...
		* 		
		* @param ids
		*/
		deleteTemplates : function(ids) {
			
		},
		
		/**
		* put your comment there...
		* 
		*/
		filter : function(event) {
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
		*/
		init : function() {
			// Create new Template.
			$('#create-new-template').click($.proxy(function() {
					this.createNewTemplate();
				}, this)
			);
			// Single row actions.
			$('.row-actions span a').click($.proxy(this.rowActions, this));
			// Bulk actions.
			$('#doaction, #doaction2').click($.proxy(this.bulkAction, this));
			// Chech/Uncheck rows.
			$('input:checkbox.select-all').change($.proxy(this.selectRows, this));
			// Filters.
			$('form#templates-manager select.filter').change($.proxy(this.filter, this));
		},

		/**
		* put your comment there...
		* 
		* @param event
		*/
		rowActions : function(event) {
			var actionInfo = event.target.href.match(/#(\w+)\((\d+)\)/);
			var params = event.target.parentNode.className; // Parent Class name has new state for changeState action!
			var action = actionInfo[1];
			var templateId = actionInfo[2];
			switch (action) {
				case 'info':
					var query = {
						view : 'templates/info',
						id : templateId,
						width : 528,
						height: 314
					};
					var uri = parent.CJTBlocksPage.server.getRequestURL('template', 'info', query);
					tb_show(CJT_TEMPLATESManagerI18N.InfoFormTitle, uri);
				break;
				case 'edit':
					this.createNewTemplate(templateId);
				break;
				case 'delete':
				case 'changeState':
					// Change state and 
					var query = {ids : [templateId], params: params};
					parent.CJTBlocksPage.server.send('templatesManager', action, query)
					.success($.proxy(function() {
						// Refresh the list!
						window.location.reload();
						}, this)
					);
				break;
			}
		},
		
		/**
		* put your comment there...
		* 
		*/
		selectRows : function(event) {
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
		}
		
	} // End form class.

	// Initialize Templates Manager form when document loaded!
	$($.proxy(CJTTemplatesManagerForm.init, CJTTemplatesManagerForm));
})(jQuery);