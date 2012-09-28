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
			// Save state.
			block.set('pagesPanelToggleState', newState);
			// For link to behave inactive.
			return false;
		}
		
		// Initialize parent class.
		this.initCJTPluginBase(node, args);
		// Activate objects panel!
		this.block.box.find('.cjt-pages-tab').tabs();	
		// Add toolbox button.
		var tbIconsGroup = this.block.box.find('.editor-toolbox .icons-group')
		tbIconsGroup.children().first().after('<a href="#" class="cjt-tb-link cjttbl-toggle-objects-panel"></a>')
		var toggler = this.editorToolbox.add('toggle-objects-panel', {callback : this._ontogglepagespanel});
		// Close it if it were closed.
		this._ontogglepagespanel({target : toggler.jButton}, this.block.get('pagesPanelToggleState', ''));
		
	} // End class.
	
	// Extend CJTBLockPluginBase.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);