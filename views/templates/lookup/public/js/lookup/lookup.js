/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	*/
	var templatesLookupFormNS = window.parent.CJTToolBox.forms.templatesLookupForm;
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	templatesLookupFormNS.form = {

	/**
	* put your comment there...
	* 		
	* @param event
	*/
		_ontemplateaction : function(event) {
			// Get author name from the clicked link.
			var actionInfo = event.target.href.match(/#(\w+)\((\d+)\)/);
			var block = templatesLookupFormNS.inputs.block;
			var popupButton = templatesLookupFormNS.inputs.button;
			var request = {templateId : actionInfo[2], blockId : block.get('id')};
			window.parent.CJTBlocksPage.server.send('templatesLookup', actionInfo[1], request)
			.success(
				function(trro) { // Template Revision Response Object
					// Insert template at cursor.
					block.aceEditor.getSession().replace(block.aceEditor.getSelectionRange(), trro.code);
					// Close the Popup after completing!
					popupButton.close();
					// Set focus to ace editor.
					block.aceEditor.focus();
				}
			)
		},
		
		/**
		* put your comment there...
		* 
		*/
		_ontoggletemplates : function(event) {
			// Get author name from the clicked link.
			var authorIdentified = event.target.href.match(/#(\w+)/)[1];
			// Get list id.
			var templatesListId = '#' + authorIdentified + '-author-templates';
			// Toggle the list.
			$(templatesListId).toggle();
		},
		
		/**
		* put your comment there...
		* 
		*/
		init : function() {
			// Initialize accordion Plugin.
			$('#templates-list').accordion()
			// Make author templates list toggle-able.
			.find('.author-name .name').click($.proxy(this._ontoggletemplates, this));
			// Actions!!
			$('.templates .template-action').click($.proxy(this._ontemplateaction, this));
			// Apply other elements when the form is loaded.
			this.refresh();
		},
		
		/**
		* Refresh state when iframe is already loaded.
		* 
		*/
		refresh : function() {
			// Use accordion menu for templates types list.
			// Select type corresponding to editor language type
			// (e.g if editor-lang = 'css' then select 'CSS', etc...).
			var activeTypeSelector = '#templates-type-header-' + templatesLookupFormNS.inputs.block.get('editorLang');
			$('#templates-list').accordion('activate', activeTypeSelector);
		}
		
	}	// End class.
	
	// Initialize form.
	$($.proxy(templatesLookupFormNS.form.init, templatesLookupFormNS.form));
	
})(jQuery);