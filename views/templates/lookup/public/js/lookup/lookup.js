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
	var blockId =parseInt(window.location.href.match(/\&blockId\=(\d+)/)[1]);
	
	/**
	* put your comment there...
	* 
	*/
	var templatesLookupFormNS = window.parent.CJTToolBox.forms.templatesLookupForm[blockId];
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	templatesLookupFormNS.form = {

		/**
		* put your comment there...
		* 
		*/
		server : window.parent.CJTBlocksPage.server,
		
		/**
		* put your comment there...
		* 
		*/
		_onsweepactions : function(event) {
			var action = event.target.href.match(/#([\w\-]+)/)[1];
			var block = templatesLookupFormNS.inputs.block;
			switch (action) {
				case 'unlink-all':
					var confirmed = confirm(CJT_TEMPLATESLookupI18N.confirmUnlinkAll);
					if (confirmed) {
						this.server.send('templatesLookup', 'unlinkAll', {blockId : block.get('id')})
						.success(
							function(response) {
								var newState = response.newState;
								// Update links to reflect new state!
								$('.templates .unlink-template').text(newState.text)
								.prop('href', '#' + newState.action)
								.removeClass().addClass(newState.className);
								// Notify user!
								alert(CJT_TEMPLATESLookupI18N.allTemplatesHasBeenUnlinkedSuccessful);
							}
						)
					}
				break;
			}
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onsweepfilters : function(event) {
			// Get author name from the clicked link.
			var filter = event.target.href.match(/#(\w+)/)[1];
			var classes = {show : '', hide : ''};
			switch (filter) {
				case 'linked':
					classes.show = '.unlink-template';
					classes.hide = '.link-template';
				break;
				case 'unlinked':
					classes.show = '.link-template';
					classes.hide = '.unlink-template';
				break;
				case 'all':
				  classes.show = '.link-template, .unlink-template';
				break;
			}
			// Show AND Hide Templates!
			$(classes.show).parents('.template-item').show();
			$(classes.hide).parents('.template-item').hide();
			// Mark as active.
			$('.filters a').removeClass('active-filter');
			$(event.target).addClass('active-filter');
			return false;
		},
		
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
			this.server.send('templatesLookup', actionInfo[1], request)
			.success(
				function(response) {
					// Special actions
					switch (actionInfo[1]) {
						case 'embedded':
							// Insert template at cursor.
							block.aceEditor.getSession().replace(block.aceEditor.getSelectionRange(), response.code);
						break;
					}
					// If the changes required reflect a state.
					var newState = response.newState;
					if (newState) {
						$(event.target).text(newState.text)
																						.prop('href', '#' + newState.action + '(' + request.templateId  + ')')
																						.get(0).className = newState.className;
					}
					// Close the Popup after completing!
					//popupButton.close();
					// Set focus to ace editor.
					//block.aceEditor.focus();
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
			// Sweep filters.
			$('.sweep .actions a').click($.proxy(this._onsweepactions, this));
			// Sweep Actions.
			$('.sweep .filters a').click($.proxy(this._onsweepfilters, this));
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