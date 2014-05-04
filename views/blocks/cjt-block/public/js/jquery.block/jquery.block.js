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
		this.pagesPanel = null;
		
		/**
		* put your comment there...
		* 
		*/
		var _onload = function() {
			// Plug the assigment panel, get the jQuery ELement for it
			var assigmentPanelElement = this.block.box.find('#tabs-' + this.block.get('id'));
			this.pagesPanel = assigmentPanelElement.CJTBlockAssignmentPanel({block : this}).get(0).CJTBlockAssignmentPanel;
			// Add toolbox button.
			var tbIconsGroup = this.block.box.find('.editor-toolbox .icons-group')
			tbIconsGroup.children().first().after('<a href="#" class="cjt-tb-link cjttbl-toggle-objects-panel"></a>')
			var toggler = this.editorToolbox.add('toggle-objects-panel', {callback : this._ontogglepagespanel});
			// Close it if it were closed.
			this._ontogglepagespanel({target : toggler.jButton}, this.block.get('pagesPanelToggleState', ''));
			// More to Dock with Fullscreen mode!
			this.extraDocks = [
				{element : assigmentPanelElement.find('.ui-tabs-panel'), pixels : 78},
				{element : assigmentPanelElement.find('.ui-tabs-panel .pagelist'), pixels : 132},
				
				{element : assigmentPanelElement.find('.custom-posts-container'), pixels : 124},
				{element : assigmentPanelElement.find('.custom-posts-container .custom-post-list'), pixels : 156},
				{element : assigmentPanelElement.find('.custom-posts-container .custom-post-list .pagelist'), pixels : 178},
				
				{element : assigmentPanelElement.find('.advanced-accordion .ui-accordion-content'), pixels : 172},
				{element : assigmentPanelElement.find('.advanced-accordion .ui-accordion-content textarea'), pixels : 182}
			];
		};

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
			var aceEditor = block.aceEditor;
			var newState = '';
			// Hide pages panel when:
			if (tabs.css('display') != 'none') {
				// Hide if initial value == undefined or initial value == closed.
				if ((initialState != '') && (initialState != 'undefined')) {
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
				codeBlock.animate({'margin-right' : 320}, undefined, undefined,
					function() {
						// Use CSS class margin not inline style!
						codeBlock.css('margin-right', '');
						// Show panel!
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
		
		// Load block only when loaded by parent model.
		this.onLoad = _onload;
	
		/// Initialize parent class.
		// Add assigment panel fields to the restoreRevision args.
		args.restoreRevision = {fields : ['code', 'pages', 'posts', 'categories', 'pinPoint', 'links', 'expressions']};
		this.initCJTPluginBase(node, args);
		
	} // End class.
	
	// Extend CJTBLockPluginBase.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);