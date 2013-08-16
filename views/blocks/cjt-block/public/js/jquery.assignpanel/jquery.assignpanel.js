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
					};

					/**
					* put your comment there...
					* 
					* @param event
					*/
					var _ondetectlistscroll = function(event) {
						// Initialize.
						var list = this;
						// Scroll value.
						var scrollValue = list.scrollTop;
						// The hidden zone!
						var scrollZone = list.scrollHeight - $(list).innerHeight();
						// If the ScrollValue = ScrollZone
						// then we need to load a new page.
						if (scrollValue == scrollZone) {
							list.fetchBlockPins();
						}
					};

					/**
					*
					* 
					* @param event
					* @param ui
					*/
					var _onobjectlistactivate = function(event, ui) {
						// Initialize.
						var item = ui.item;
						// Process only elements that list assignment panel objects.
						if (item.data('objectListButton') === true) {
							// Load first objects page for the activated item
							// only once.
							if (item.data('objectListActivated') === undefined) {
								// In order to load the first page we need to get 'list' DOM node.
								var list = item.data('list');
								// Load first page.
								list.get(0).fetchBlockPins().success($.proxy(
									function() {
										// Mark item as activated after the page
										// is successfully loaded.
										item.data('objectListActivated', true);
									}, this)
								);
							}
						}
					};
		
					/**
					* put your comment there...
					* 
					*/
					var fetchBlockPins = function() {
						// Initialize.
						var list = $(this);
						// Get pins parameters.
						var params = list.data('params')
						// Get the loaded pins count.
						var loadedPinsCount = list.length;
						// Load next page.
						fetchObjects(loadedPinsCount, params.objectType, params.postType).success($proxy(
							// Add the new items to the list.
							function(newItems) {
								
							}, this)
						)
					}

					/**
					* put your comment there...
					* 
					* @param index
					* @param objType
					* @param type
					*/
					var fetchObjects = function(index, objType, type) {
						// Initialize.
						var server = CJTBlocksPage.server;
						var requestData = {index : index, objectType : objType, postType : postType};
						// Send request to server.
						var promise = server.send('controller', 'action', requestData).success($.proxy(
							function(response) {
								
							}, this)
						);
						// Return
						return ;
					}

					/**
					* put your comment there...
					* 
					*/
					var readAssignedPins = function () {
						
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
					// Read all pins associated with the block.
					readAssignedPins();
					// Initialize all 'objects-list'
					this.jElement.find('.objects-list-button').each(
						$.proxy(function(index, objectListEle) {
							// Initialize.
							var listElement = null;
							var listElementId = '';
							var listElementNode;
							var listParams = {};
							// objectListElement jQuery.
							objectListEle = $(objectListEle);
							// Get all input fields laying under the object-list container.
							var inputFields = objectListEle.find('input[type=hidden]').each($.proxy(
								function(index, inputEle) {
									listParams[inputEle.name] = inputEle.value;
								})
							);
							// Get objects-list DOM node.
							listElementId = '#objects-list-' + listParams.postType + '-' + mdlBlock.get('id');
							listElement = this.jElement.find(listElementId).eq(0);
							// Push all input field values to the list.
							listElement.data('params', listParams)
							// Set objects-list for later use.
							objectListEle.data('list', listElement)
							// In order for the item to be processed on the 'activate' event
							// the item should be signed for that so it can determind
							// later!
							.data('objectListButton', true);
							// Delete those extra input fields from DOM tree.
							inputFields.remove();
							// Add fetchBlockPins and Detect Scrolling event handler method to the list object.
							listElementNode = listElement.get(0);
							listElementNode.fetchBlockPins = fetchBlockPins;
							listElementNode._ondetectlistscroll = _ondetectlistscroll;
							// Fetch objects from server with list scrolls event.
							listElement.scroll(listElementNode._ondetectlistscroll);
						}, this)
					);
					// Initialize Assigment Panel tab.
					this.jElement.tabs({
						activate : function(event, ui) {
							// Set ui.item to accordion reference.
							ui.item = ui.newTab;
							// Trigger real event handler.
							_onobjectlistactivate(event, ui);
						}
					})
					// Initialize custom posts accordion.
					.find('#accordion-custom-posts-' + mdlBlock.get('id')).accordion({
						activate : function(event, ui) {
							// Set ui.item to accordion reference.
							ui.item = ui.newHeader;
							// Trigger real event handler.
							_onobjectlistactivate(event, ui);
						},
						collapsible : true
					});
					// Initialize Advanced tab accordion.
					mdlBlock.box.find('#advanced-accordion-' + mdlBlock.get('id')).accordion({
							change : _onadvancedaccordionchanged,
							header: '.acc-header'
						}
					);
					// Load 'pages' tab as its selected when the block is initialized.
					// In the future we might save the selected tab but for now the initial state is the first (pages) tab
					var defaultTab = 'pages';
					
				}
			}
		})
	}
})(jQuery);