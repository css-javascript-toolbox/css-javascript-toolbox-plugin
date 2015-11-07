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
					* Assigment panel jQuery pLugin reference
					* 
					*/
					var apjp = this;
					
					/**
					* put your comment there...
					* 
					* @type Array
					*/
					this.objectsList = [ 'pages', 'posts', 'categories', 'pinPoint' ];
					
					// Allow Pluggable buttons
					$( assigmentPanelElement ).trigger( 'cjtblockassigninitobjectslist', [ this ] );
					
					/**
					* put your comment there...
					* 
					* @type CJTBLockPlugin
					*/
					this.block = options.block;

					/**
					* 
					*/
					this.buttons = ( function() {
						
						var buttons = {};

						$.each( apjp.objectsList, 
						
								function( index, name ) 
								{
									buttons[ name ] = [];
								}
							);
						
						return buttons;
						
					} )();
					
					/**
					* 
					*/
					this.checkboxDisabled = (this.block.state == 'restore')
					
					/**
					* 
					*/
					this.loadAssignedOnlyMode = this.checkboxDisabled;
					
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
					var map = ( function() {
						
						var map = {};

						$.each( apjp.objectsList, 
						
								function( index, name ) 
								{
									map[ name ] = { };
								}
							);

						return map;
						
					} )();;
					
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
						// Don't load unless not all items has been loaded.
						if (jList.data('itemsLoaded') === true) {
							return;
						}
						// Prevent multiple requests at the same time.
						var isLoading = jList.data('cjt_isObjectListLoading');
						// Scroll value.
						var scrollValue = list.scrollTop;
						// The hidden zone!
						var scrollZone = list.scrollHeight - jList.innerHeight();
						// If the ScrollValue = ScrollZone
						// then we need to load a new page.
						if ((scrollValue == scrollZone) && (isLoading === false)) {
							list.getCJTBlockAPOP(false);
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
							if (item.data('objectListActivated') !== true) {
								// In order to load the first page we need to get 'list' DOM node.
								var list = item.data('list');
								var params = list.data('params');
								// Load first page.
								list.get(0).getCJTBlockAPOP(true).success($.proxy(
									function(response) {
										// Mark item as activated after the page
										// is successfully loaded.
										item.data('objectListActivated', true);
										// Initialize TOTLAL COUNT.
										var infoPanel = list.data('infoPanel');
										// Cache total.
										list.data('totalItemsCount', response.total);
										// Show pagination info bar.
										infoPanel.css({display : 'block'}).find('.info-panel-total').text(response.total);
										// Reset Pagination List when the button is first time activated.
										item.data('paginationList').reset();
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
						var item = (map[groupName][objectId] === undefined) ? {sync : 0} : map[groupName][objectId];
						// Set value as checkbox.
						item.value = $(checkbox).prop('checked') ? 1 : 0;
						// If the item is not 'synced' (not saved on server)
						// and not checked then remove it from the map
						// , add it if checked.
						if (item.sync === 0) {
							if (item.value === 0) {
								delete map[groupName][objectId];
							}
							else {
								map[groupName][objectId] = item;	
							}
						}
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
					* @param typeParams
					* @param initialize
					* @param page
					*/
					var getAPOP = function(index, typeParams, initialize, page) {
						// Initialize.
						var server = CJTBlocksPage.server;
						// Use modeBlockId instead of original block id to be used in case of 
						// DISPLAY-REVISION mode or any other modes added in the future.
						var blockId = assignPanel.modeBlockId ? assignPanel.modeBlockId : assignPanel.block.block.get('id');
						var requestData = {
							block : blockId,
							index : index,
							iPerPage : (page * assignPanel.getIPerPage()),
							typeParams : typeParams,
							assignedOnly : assignPanel.loadAssignedOnlyMode,
							initialize : initialize
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
											map[typeParams.group][item.id] = {value : 1, sync : 1};
										}										
									}, this)
								);
							}, this)
						);
						// Return
						return promise;
					};

					/**
					* 
					*/
					this.activateTab = function(type) {
						// Activate the AUX tab by default.
						assignPanel.jElement.find('li.type-' + type + '>a').trigger('click');
						assignPanel.jElement.tabs({collapsible : false});
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
					this.getIPerPage = function() {
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
					* 
					*/
					this.list_displayItems = function(list, data) {
						// Initialize.
						var mdlBlock = assignPanel.block.block;
						// New items to add to the list.
						var items = data.items;
						// Get pins parameters.
						var typeParams = list.data('params')
						// Get the cached loaded pins count.
						var loadedPinsCount = list.data('loadedCount');
						// Update loaded count.
						list.data('loadedCount', (loadedPinsCount + data.count));
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
																.appendTo($('<label></label>').appendTo(itemLi))
																// If load-assigned-only-mode is activated then disable checkboxes.
																.prop('disabled', assignPanel.checkboxDisabled);
								// Add the Checkbox to notification save chnages elements.
								assignPanel.block.notifySaveChanges.initElement(checkbox.get(0));
								// Checkbox title container.
								var title = $('<span><span>')
														.attr('title', item.title)
														.appendTo(itemLi);
								// Checkbox title and link
								if (item.link) {
									$('<a href="' + item.link + '" target="_blank">' + item.title + '</a>').appendTo(title);	
								}
								else {
									title.text(item.title)
											 .css({'margin-left' : '6px'});
								}
								// Create Child Components IF: NOT-IN-REVISION-MODE AND THE ITEM-HAS-CHILD.
								if (!assignPanel.loadAssignedOnlyMode && item.hasChilds) {
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
					}
					
					/**
					* APOP -- Assigment Pabel Objects page
					* 
					*/
					this.list_GetAPOP = function(initialize, page) {
						// Initialize.
						var list = $(this);
						// Get pins parameters.
						var typeParams = list.data('params')
						// Get the cached loaded pins count.
						var loadedPinsCount = list.data('loadedCount');
						// Flag that the list is in 'loading' state.
						list.data('cjt_isObjectListLoading', true);
						// Show LOADING-PROGRESS as color switching.
						var button = list.data('button');
						var colorStyles = ['prog-normal', 'prog-colored'];
						var colorState = false;
						var tmrProg = window.setInterval($.proxy(
							function() {
								// Remove previous state class.
								button.removeClass(colorStyles[colorState ? 1 : 0]);
								// Switch STATE
								colorState = !colorState;
								// Add or Remove CSS class based on the current state.
								button.addClass(colorStyles[colorState ? 1 : 0]);
							}, this)
						, 150);
						// Load next page.
						var promise = getAPOP(loadedPinsCount, typeParams, initialize, page).success($.proxy(
							// Add the new items to the list.
							function(response) {
								// If there is no more items to load then exit
								// and don't try to load data for this list anymore.
								if (response.count == 0) {
									// Mark as full loaded
									list.data('itemsLoaded', true);
									// Exit as there is nothing to process.
									return;
								}
								// Display items.
								assignPanel.list_displayItems(list, response);
								// Display loaded items count.
								list.data('infoPanel').find('.info-panel-loaded-count').text(list.data('loadedCount'));
								// Cache TOTAL loaded pages.
								list.data('loadedPages', (list.data('loadedPages') + page));
							}, this)
						).complete($.proxy(
							function() {
								// Flag that the list is in 'not-loading' state.
								list.data('cjt_isObjectListLoading', false);
								// Stop progeess.
								window.clearInterval(tmrProg);
								// Remove STATE class.
								button.removeClass('prog-normal').removeClass('prog-colored');
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
		
					/**
					* put your comment there...
					* 
					*/
					this.getTypeObject = function() {
						// Default type object.
						var type = {
							button : {
								stateVars : {
									objectListActivated : false
								}
							},
							list : {
								stateVars : {
									cjt_isObjectListLoading : false,
									loadedCount : 0,
									itemsLoaded : false,
									loadedPages : 0,
									totalItemsCount : 0
								},
								items : null
							}
						};
						// Returs.
						return type;
					}
		
					/**
					* put your comment there...
					* 
					*/
					var onBlockSaved = function() {
						// Syncronize the map.
						var map = assignPanel.getMap();
						$.each(map, $.proxy(
							function(name, map) {
								$.each(map, $.proxy(
									function(id, item) {
										// Sync new-added items.
										if (!item.sync) {
											item.sync = 1;
										}
										else if (!item.value) {
											// Remove deleted item.
											delete map[id];
										}
									}, this)
								)
							}, this)
						);
					};

					/**
					* 
					*/
					this.setMapGroup = function(name, mapList) {
						map[name] = mapList;
					}

					/// CONSTRUCTTOR  ///
					var blockId = mdlBlock.get('id');
					var typeObjectDefaults = this.getTypeObject();
					
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
							listElementId = '#objects-list-' + listParams.type + '-' + blockId + '-0';
							listElement = this.jElement.find(listElementId).eq(0);
							// Initialize data register.
							listElement
							.data('loadedCount', 0)
							.data('loadedPages', 0)
							.data('totalItemsCount', 0)
							.data('itemsLoaded', false)
							// Cache button element reference.
							.data('button', objectListEle.find('>a'))
							// Get info-panel element.
							.data('infoPanel', listElement.next())
							// Push all input field values to the list.
							.data('params', listParams);
							// Set objects-list for later use.
							objectListEle.data('list', listElement)
							// Pagination list.
							.data('paginationList', new CJTBlockAssignPanelPaginationList(this, listElement))
							// Initialize default state-var
							.data('objectListActivated', false)
							// In order for the item to be processed on the 'activate' event
							// the item should be signed for that so it can determind
							// later when the activate event got fired!
							.data('objectListButton', true);
							// Delete those extra input fields from DOM tree.
							inputFields.remove();
							// Add fetchBlockPins and Detect Scrolling event handler method to the list object.
							listElementNode = listElement.get(0);
							listElementNode.getCJTBlockAPOP = function(initialize) {
								// List items.
								return assignPanel.list_GetAPOP.apply(this, [initialize, 1]);
							};
							listElementNode._ondetectlistscroll = _ondetectlistscroll;
							// Fetch objects from server with list scrolls event.
							listElement.bind('scroll.cjt', listElementNode._ondetectlistscroll);
							// Cache object-list-element reference for later use.
							this.buttons[listParams['group']].push(objectListEle);
						}, this)
					);
					
					// Initialize assign panel attachec components
					$( assigmentPanelElement ).trigger( 'cjtassignpanelattachedcomponents', [ assignPanel ] );
					
					// Initialize Assigment Panel tab.
					this.jElement.tabs(
					{
						activate : function(event, ui)
						{
							// Set ui.item to TAB reference.
							ui.item = ui.newTab;
							// Trigger real event handler.
							_onobjectlistactivate(event, ui);
						}
						
					} );
					
					this.jElement.tabs( 'option', 'active', this.jElement.find( '>.ui-tabs-panel' ).length - 1 );
					
					// Create custom posts toggle widget.
					var cpContainer = this.jElement.find('#custom-posts-container-' + blockId);
					// Toggle custom post list when the header link is clicked.
					cpContainer.find('.objects-list-button>a.custom-post-item-header').click($.proxy(
						function(event) {
							// Initialize.
							var button = $(event.target).parent();
							var list = button.next();
							// Toggle item.
							list.toggle();
							// Fire the activate event in case that the item
							// is not visible.
							if (list.css('display') != 'none') {
								// Activate event standard interface.
								var ui = {item : button};
								// Trigger real event handler.
								_onobjectlistactivate(event, ui);
							}
							// Make link inactive.
							return false;
						}, this)
					);
					
					// Initialize Advanced tab accordion.
					mdlBlock.box.find('#advanced-accordion-' + blockId).accordion({
							change : _onadvancedaccordionchanged,
							header: '.acc-header'
						}
					);
					
					// Syncronize assign panel map when block saved.
					options.block.onBlockSaved = onBlockSaved;
				}
			}
		})
	}
})(jQuery);