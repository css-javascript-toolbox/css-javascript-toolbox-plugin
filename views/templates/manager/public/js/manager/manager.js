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
		createNewTemplate : function (event, editId) {
			var editId = (editId === undefined) ? 0 : editId;
			var operation = editId ? 'edit' : 'create';
			var query = {
				view : 'templates/template',
				id : editId,
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
		filter : function(event) {
			var filterElement = event.target;
			// Get form input field for the filter field.
			var inputFieldName = filterElement.className.match(/filter_\w+$/)[0];
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
			$('#create-new-template').click($.proxy(this.createNewTemplate, this));
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
			var action = event.target.parentNode.className;
			var id = parseInt(event.target.href.match(/#(\w+)$/)[1]);
			switch (action) {
				case 'info':
					
				break;
				case 'edit':
					this.createNewTemplate(undefined, id);
				break;
				case 'delete':
				
				break;
				case 'changeState':
				
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