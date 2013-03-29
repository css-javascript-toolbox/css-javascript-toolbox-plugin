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
		this._onadvancedaccordionchanged = function(event, ui) {
			// Activate textarea under the current selected item content!
			ui.newContent.find('textarea').focus();
		}
		
		/**
		* 
		*/
		this._onselectchilds = function(event) {
			// Initialize vars.
			var overlay = $(event.target);
			var checkbox = overlay.parent().find('.select-childs');
			var state = checkbox.prop('checked') ? '' : 'checked';
			// Work only if select-child checkbox is interactive!
			if (checkbox.attr('disabled') != 'disabled') {
				// Revert checkbox state.
				checkbox.prop('checked', state);
				// Clone state to parent checkbox.
				checkbox.parent().find('label>input:checkbox').prop('checked', state).trigger('change');
				//Clone state to all child checkboxes
				checkbox.parent().find('.children input:checkbox').prop('checked', state).trigger('change');
			}
			// For link to behave inactive.
			return false;
		}
		
		// Initialize parent class.
		this.initCJTPluginBase(node, args);
		// Activate objects panel!
		var pagesPanel = this.block.box.find('.cjt-pages-tab').tabs();
		// Accordion menu for Advanced TAB.
		this.block.box.find('#advanced-accordion-' + this.block.get('id')).accordion({
				change : this._onadvancedaccordionchanged,
				header: '.acc-header'
			}
		);
		// Put select-childs checkboxes in action!
		pagesPanel.find('.select-childs-checkbox-overlay').click($.proxy(this._onselectchilds, this));
	} // End class.
	
	// Extend CJTBLockPluginBase.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);