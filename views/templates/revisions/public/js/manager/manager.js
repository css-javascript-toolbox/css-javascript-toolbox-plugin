/**
* 
*/
(function($) {
	
	/**
	* 
	*/
	CJTTemplateRevisionsForm = {
		
		/**
		* put your comment there...
		* 
		*/
		bulkAction : function(event) {
			// Get bulk action list element.
			var actionListName = event.target.id.replace('do', '');
			var actionList = $('form#manage-form select[name="'+ actionListName + '"]');
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
			var inputField = $('form#manage-form input:hidden[name="' + inputFieldName + '"]');
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
			$('#revision').click($.proxy(this.revision, this));
			// Bulk actions.
			$('#doaction, #doaction2').click($.proxy(this.bulkAction, this));
			// Chech/Uncheck rows.
			$('input:checkbox.select-all').change($.proxy(this.selectRows, this));
			// Filters.
			$('form#manage-form select.filter').change($.proxy(this.filter, this));
		},
		
		/**
		* put your comment there...
		* 
		*/
		revision : function () {
			var query = {
				width : 570,
				height: 500,
				view : 'templates/revision',
				id : 0
			};
			var uri = parent.CJTBlocksPage.server.getRequestURL('templateRevisions', 'revision', query);
			tb_show(CJT_TemplateRevisionsI18N.revisionDialogTitle, uri);
		},
		
		/**
		* put your comment there...
		* 
		*/
		selectRows : function(event) {
			var newState = $(event.target).prop('checked');			
			// Check/Uncheck all rows.
			$('form#manage-form input[name="guid[]"]').prop('checked', newState);
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
	$($.proxy(CJTTemplateRevisionsForm.init, CJTTemplateRevisionsForm));
})(jQuery);