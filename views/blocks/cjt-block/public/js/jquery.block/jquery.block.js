/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* Override CJTBlockPlugin class.
	* 
	* @param node
	* @param args
	*/
	CJTBlockPlugin = function(node, args) {			
		
		/**
		* 
		*/
		this._onselectchilds = function(event) {
			// Initialize vars.
			var overlay = $(event.target);
			var checkbox = overlay.parent().find('.select-childs');
			var state = checkbox.prop('checked') ? '' : 'checked';
			// Revert checkbox state.
			checkbox.prop('checked', state);
			// Clone state to parent checkbox.
			checkbox.parent().find('label>input:checkbox').prop('checked', state).trigger('change');
			//Clone state to all child checkboxes
			checkbox.parent().find('.children input:checkbox').prop('checked', state).trigger('change');
			// For link to behave inactive.
			return false;
		}
		
		/**
		*
		*
		*
		*
		*/		
		this._ontogglepagespanel = function(event, initialState) {
			var toggler = $(event.target);
			var block = this.block;
			var tabs = block.box.find('.cjt-pages-tab');
			var pagesBlock = block.box.find('.cjpageblock');
			var codeBlock = block.box.find('.cjcodeblock');
			var aceEditor = block.box.find('.code-editor');
			var newState = '';
			// Hide pages panel when:
			if (tabs.css('display') != 'none') {
				// Hide if initial value == undefined or initial value == closed.
				if (initialState != '') {
					// Hide elements.
					tabs.hide();
					pagesBlock.css('width', '0px');
					codeBlock.animate({'margin-right' : 0}, undefined, undefined, 
						function() {
							toggler.addClass('closed');
							// Refresh editor.
							aceEditor.resize();
						}
					);
					// Save state.
					newState = 'closed';
				}
			}
			else {
				// Show elements.
				codeBlock.animate({'margin-right' : 317}, undefined, undefined,
					function() {
						pagesBlock.css('width', '');
						tabs.show();
						toggler.removeClass('closed');
						// Refresh editor.
						aceEditor.resize();
					}
				);
			}
			// Set title based on the new STATE!
			toggler.attr('title', CJT_CJT_BLOCKJqueryBlockI18N['assigmentPanel_' + newState + 'Title']);
			// Save state.
			block.set('pagesPanelToggleState', newState);
			// For link to behave inactive.
			return false;
		}
		
		// Initialize parent class.
		this.initCJTPluginBase(node, args);
		// Activate objects panel!
		var pagesPanel = this.block.box.find('.cjt-pages-tab').tabs();	
		// Add toolbox button.
		var tbIconsGroup = this.block.box.find('.editor-toolbox .icons-group')
		tbIconsGroup.children().first().after('<a href="#" class="cjt-tb-link cjttbl-toggle-objects-panel"></a>')
		var toggler = this.editorToolbox.add('toggle-objects-panel', {callback : this._ontogglepagespanel});
		// Close it if it were closed.
		this._ontogglepagespanel({target : toggler.jButton}, this.block.get('pagesPanelToggleState', ''));
		// Accordion menu for Advanced TAB.
		this.block.box.find('#advanced-accordion').accordion();
		// Put select-childs checkboxes in action!
		pagesPanel.find('.select-childs-checkbox-overlay').click($.proxy(this._onselectchilds, this));
		// More to Dock with Fullscreen mode!
		this.extraDocks = [
			{element : pagesPanel.find('.ui-tabs-panel'), pixels : 89},
			{element : pagesPanel.find('.ui-tabs-panel .pagelist'), pixels : 132},
			{element : pagesPanel.find('#advanced-accordion .ui-accordion-content'), pixels : 169},
			{element : pagesPanel.find('#advanced-accordion .ui-accordion-content textarea'), pixels : 177}
		];
	} // End class.
	
	// Extend CJTBLockPluginBase.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);