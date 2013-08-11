/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* jQuery namespace.
	*/
	$.fn.CJTBlockAssignmentPanel = function(options) {
		// Process all assigment panels
		return this.each(function() {
			// Assigment panel DOM element.
			var assigmentPanelElement = this;
			// Create a plugin for the element only if not already created.
			if (assigmentPanelElement.CJTBlockAssignmentPanel === undefined) {
				// Define assigment panel plugin.
				assigmentPanelElement.CJTBlockAssignmentPanel = new function() {
										
					/**
					* put your comment there...
					* 
					* @type CJTBLockPlugin
					*/
					this.block = options.block;

					/**
					* 
					*/
					this.jElement = $(assigmentPanelElement);
					
					/**
					* 
					*/
					this.pins = {};
					
					/**
					* 
					*/
					var _onadvancedaccordionchanged = function(event, ui) {
						// Activate textarea under the current selected item content!
						ui.newContent.find('textarea').focus();
					}

					/**
					* put your comment there...
					* 
					* @param type
					*/
					var loadTab = function(type) {
						
					}
					
					/**
					* put your comment there...
					* 
					*/
					var readPins = function () {
						
					}

					/*
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
					// Put select-childs checkboxes in action!
					this.jElement.find('.select-childs-checkbox-overlay').click($.proxy(this._onselectchilds, this));
					*/
					
					// Initialize.
					var mdlBlock = this.block.block;
					// Root and Custom Posts TAB.
					this.jElement.tabs()
							.find('#custom-posts-accordion-' + mdlBlock.get('id')).accordion({
								collapsibleType : true
							});
					// Initialize Advanced tab accordion.
					mdlBlock.box.find('#advanced-accordion-' + mdlBlock.get('id')).accordion({
							change : _onadvancedaccordionchanged,
							header: '.acc-header'
						}
					);
					// Read all pins associated with the block.
					readPins();
					// Load 'pages' tab as its selected when the block is initialized.
					// In the future we might save the selected tab but for now the initial state is the first (pages) tab
					var currentTab = 'pages';
					loadTab(currentTab);
				}
			}
		})
	}
})(jQuery);