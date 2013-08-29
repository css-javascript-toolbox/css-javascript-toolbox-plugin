/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* Hold the items per page to load
	* at a time.
	* 
	* The variable is set the first time
	* getIPerPage method is called.
	* 
	* @
	*/
	var iPerPage = null;
					
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
					* Hold reference for 'this' object to
					* be accessed by 'private' methods when called
					* from DOM element event handler.
					* 					
					*/
					var assignPanel = this;
					
					/**
					* put your comment there...
					* 
					* @type Object
					*/
					var map = {pages : [], posts : [], categories : []};
					
					/**
					* put your comment there...
					* 
					*/
					var mdlBlock = this.block.block;
					
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
						var jList = $(list);
						// Prevent multiple requests at the same time.
						var isLoading = jList.data('cjt_isObjectListLoading');
						// Scroll value.
						var scrollValue = list.scrollTop;
						// The hidden zone!
						var scrollZone = list.scrollHeight - jList.innerHeight();
						// If the ScrollValue = ScrollZone
						// then we need to load a new page.
						if ((scrollValue == scrollZone) && (isLoading === false)) {
							list.getCJTBlockAPOP();
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
								list.get(0).getCJTBlockAPOP().success($.proxy(
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
					* @param event
					*/
					var _onobjectstatechanged = function() {
						// Initialize.
						var checkbox = this;
						var groupName = checkbox.name.match(/cjtoolbox\[\d+\]\[(\w+)\]/)[1];
						var objectId = parseInt(checkbox.value);
						// Get item reference is available (sync=true, sync=false&value=true)
						// or create new one (select not synced item).
						var item = (map[groupName][objectId] === undefined) ? {sync : false} : map[groupName][objectId];
						// Set value as checkbox.
						item.value = $(checkbox).prop('checked');
						// If the item is not 'synced' (not saved on server)
						// and not checked then remove it from the map
						// , add it if checked.
						if (item.sync == false) {
							if (item.value == false) {
								delete map[groupName][objectId];
							}
							else {
								map[groupName][objectId] = item;	
							}
						}
						console.log(map[groupName]);
					};

					/**
					* put your comment there...
					* 
					* @param event
					* 
					* @returns {Boolean}
					*/
					var _onselectchilds = function(event) {
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
					};

					/**
					* put your comment there...
					* 
					* @param index
					* @param objType
					* @param type
					*/
					var getAPOP = function(index, typeParams) {
						// Initialize.
						var server = CJTBlocksPage.server;
						var requestData = {
							block : assignPanel.block.block.get('id'),
							index : index,
							iPerPage : getIPerPage(),
							typeParams : typeParams
						};
						// Send request to server.
						var promise = server.send('block', 'getAPOP', requestData).success($.proxy(
							// User Interface Component Independent Routines
							function(response) {
								// Initialize.
								var items = response.items;
								// Create Assigment Panel objects MAP used for saving
								// the assigned pins.
								$.each(items, $.proxy(
									function(index, item) {
										// Cache only 'assigned' items.
										if (item.assigned === true) {
											map[typeParams.group][item.id] = {value : true, sync : true};
										}										
									}, this)
								);
							}, this)
						);
						// Return
						return promise;
					};

					/**
					* Get items count to load per
					* page.
					* 
					* The items to load per page is calculated by
					* determing the maximum height that the objects-list element
					* can reach divided by the single item height.
					* 
					* The maximum objects-list height is determined by
					* the available screen-height - the other elements
					* (TAB Nav, margins, padding, etc...).
					* above and bottom the objects-list.
					* 
					*/
					var getIPerPage = function() {
						// Calculate iPerPage only if not calculated.
						if (iPerPage === null) {
							// Initialize.
							var screenHeight = screen.availHeight;
							var listItemHeight = 21;
							// BLK_HEADER + NAV(MENU + (SPACE * 2)) + LIST((PAD * 10) + BORDER + (MARGIN * 2)) + BUTTOM-SPACE.
							var reserved = 33 + (62 + (5 * 2)) + ((10 * 2) + 1 + (12 * 2)) + 14;
							// Get the maximum objects-list height.
							var maxListHeight = screenHeight - reserved;
							// Divide the max height by the item height.
							// and always add 5 items for makr the full screen
							// mode scrolled too.
							// Scrollbar must be available in all cases
							// as long as there is items not loaded yet.
							iPerPage = parseInt(maxListHeight / listItemHeight) + 5;
						}
						return iPerPage;
					};

					/**
					* APOP -- Assigment Pabel Objects page
					* 
					*/
					var list_GetAPOP = function() {
						// Initialize.
						var list = $(this);
						// Get pins parameters.
						var typeParams = list.data('params')
						// Get the cached loaded pins count.
						var loadedPinsCount = list.data('loadedCount');
						// Flag that the list is in 'loading' state.
						list.data('cjt_isObjectListLoading', true);
						// Load next page.
						var promise = getAPOP(loadedPinsCount, typeParams).success($.proxy(
							// Add the new items to the list.
							function(response) {
								// Initialize.
								var mdlBlock = assignPanel.block.block;
								// New items to add to the list.
								var items = response.items;
								// If there is no more items to load then exit
								// and don't try to load data for this list anymore.
								if (response.count == 0) {
									// Remove scroll event that handle the dynamic loading.
									list.unbind('scroll.cjt');
									// Exit as there is nothing to process.
									return;
								}
								// Update loaded count.
								list.data('loadedCount', (loadedPinsCount + response.count));
								// Add items to list using
								$.each(items, $.proxy(
									function(index, item) {
										// Get parent list DOM Node.
										var listId = '#objects-list-' + typeParams.type + '-' + mdlBlock.get('id') + '-' + item.parent;
										var targetList = assignPanel.jElement.find(listId);
										// Item list LI element.
										var itemLi = $('<li></li>').appendTo(targetList);
										// Item assignment panel checkbox.
										var checkbox = $('<input type="checkbox" />')
																		 // Set name
																		.prop('name', 'cjtoolbox[' + mdlBlock.get('id') + '][' + typeParams.group + '][]')
																		 // Submit object-ID when saving.
																		.val(item.id)
																		 // Update the map once the assigned pin checkbox
																		 // checked value changed.
																		.change(_onobjectstatechanged)
																		.prop('checked', item.assigned)
																		.appendTo($('<label></label>').appendTo(itemLi));
										// Checkbox title container.
										var title = $('<span><span>')
																.attr('title', item.title)
																.appendTo(itemLi);
										// Checkbox title and link
										$('<a href="' + item.link + '" target="_blank">' + item.title + '</a>').appendTo(title);
										// In case that the current item has child items,
										// Add DOM Nodes to server child items.
										if (item.hasChilds) {
											// Add 'select childs' checkbox just before te title container element.
											var link = $('<a href="#" class="select-childs-checkbox-overlay"></a>')
											.click($.proxy(_onselectchilds, this))
											.insertBefore(title);
											// Overlay checkbox.
											link.after('<input type="checkbox" class="select-childs">');
											// Add child items list below the title container.
											$('<ul class="children"></ul>')
											.prop('id', ('objects-list-' + typeParams.type + '-' + mdlBlock.get('id') + '-' + item.id))
											.insertAfter(title);
										}
									}, this)
								);
							}, this)
						).complete($.proxy(
							function() {
								// Flag that the list is in 'not-loading' state.
								list.data('cjt_isObjectListLoading', false);
							}, this)
						);
						return promise;
					};
					
					/**
					* 
					*/
					this.getMap = function() {
						return map;						
					}

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
							listElementId = '#objects-list-' + listParams.type + '-' + mdlBlock.get('id') + '-0';
							listElement = this.jElement.find(listElementId).eq(0);
							// Initialize data register.
							listElement.data('loadedCount', 0);
							// Push all input field values to the list.
							listElement.data('params', listParams)
							// Set objects-list for later use.
							objectListEle.data('list', listElement)
							// In order for the item to be processed on the 'activate' event
							// the item should be signed for that so it can determind
							// later when the activate event got fired!
							.data('objectListButton', true);
							// Delete those extra input fields from DOM tree.
							inputFields.remove();
							// Add fetchBlockPins and Detect Scrolling event handler method to the list object.
							listElementNode = listElement.get(0);
							listElementNode.getCJTBlockAPOP = list_GetAPOP;
							listElementNode._ondetectlistscroll = _ondetectlistscroll;
							// Fetch objects from server with list scrolls event.
							listElement.bind('scroll.cjt', listElementNode._ondetectlistscroll);
						}, this)
					);
					// Initialize Assigment Panel tab.
					this.jElement.tabs({
						activate : function(event, ui) {
							// Set ui.item to accordion reference.
							ui.item = ui.newTab;
							// Trigger real event handler.
							_onobjectlistactivate(event, ui);
						},
						active : false,
						collapsible : true,
					})
					// Initialize custom posts accordion.
					.find('#accordion-custom-posts-' + mdlBlock.get('id')).accordion({
						activate : function(event, ui) {
							// Set ui.item to accordion reference.
							ui.item = ui.newHeader;
							// Trigger real event handler.
							_onobjectlistactivate(event, ui);
						},
						active : false,
						collapsible : true
					});
					// Initialize Advanced tab accordion.
					mdlBlock.box.find('#advanced-accordion-' + mdlBlock.get('id')).accordion({
							change : _onadvancedaccordionchanged,
							header: '.acc-header'
						}
					);
				}
			}
		})
	}
})(jQuery);