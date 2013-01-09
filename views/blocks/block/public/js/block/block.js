/**
* @version $ Id; cjtserver.js 21-03-2012 03:22:10 Ahmed Said $
* 
* Block model class.
*/

/**
* Put CJTBlock class at global scope.
*/
var CJTBlock;

/**
* JQuery wrapper for the CJTBlock class.
*/
(function($) {

	/**
	* Blocks property that allowed to be read or write.
	*
	* flags:
	*	- r : read.
	*	- w : write.
	*	- c : cookie.
	*	- l : checkbox list.
	*
	* @var object
	*/
	var properties = {
		name 									: {flags: 'rw', 	selector : 'input:hidden[name="cjtoolbox[{blockId}][name]"]'},
		editorLang						: {flags: 'rwc'},
		pagesPanelToggleState	: {flags: 'rwc'},
		location 							: {flags: 'rw', 	selector : 'input:hidden[name="cjtoolbox[{blockId}][location]"]'},
		state 								: {flags: 'rw', 	selector : 'input:hidden[name="cjtoolbox[{blockId}][state]"]'},
		code 									: {flags: 'rw', 	selector : 'div#editor-{blockId}'},
		links 								: {flags: 'rw', 	selector : 'textarea[name="cjtoolbox[{blockId}][links]"]'},
		expressions 					: {flags: 'rw', 	selector : 'textarea[name="cjtoolbox[{blockId}][expressions]"]'},
		pinPoint 							: {flags: 'rwl', 	selector : 'input:checkbox[name="cjtoolbox[{blockId}][pinPoint][]"]'},
		pages 								: {flags: 'rwl', 	selector : 'input:checkbox[name="cjtoolbox[{blockId}][pages][]"]'},
		posts 								: {flags: 'rwl', 	selector : 'input:checkbox[name="cjtoolbox[{blockId}][posts][]"]'},
		categories 						: {flags: 'rwl', 	selector : 'input:checkbox[name="cjtoolbox[{blockId}][categories][]"]'}
	};
	
	/**
	* Flags values.
	*
	* @var object 
	*/
	var flags = {
		location : ['header', 'footer'],
		state : ['active', 'inactive']
	};
	
	/**
	* Provide simple access to single block properties.
	*
	* @author Ahmed Said
	* @version 6
	* @param DOMElement Block element.
	*/
	CJTBlock = function(element) {
		
		/**
		* 
		*
		*
		*/
		this.aceEditor = null;
		
		/**
		* Block JQuery object.
		*
		* @var JQuery object.
		*/
		this.box = null;
		
		/**
		* Block id.
		*
		* @var integer
		*/
		this.id = 0;
		
		/**
		*
		*
		*
		*
		*
		*/
		this.CJTBlock = function() {
			var block = this; // To be used inside each().
			this.box = $(element);
			this.id = parseInt(this.box.find('input:hidden[name="blocks[]"]').val())
			this.aceEditor = ace.edit('editor-' + this.id);
			// Create bridge through "code" field
			// so that div element can set/get aceEditor real object.
			var codeDiv = $(this.property('code').selector).get(0);
			codeDiv.getValue = function() {
				return block.aceEditor.getSession().getValue();
			};
			codeDiv.setValue = function(value) {
				block.aceEditor.getSession().setValue(value)
			};			
		}
		
		/**
		* Get block property cvalue.
		*
		* @param string Property name.
		* @return mixed
		*/
		this.get = function(name, _default) {
			var value = null;
			// Just return the exists properties.
			if (this[name] != undefined) {
				value = this[name];
			}
			// Properties need to be fetched from HTML structure or from another JS objects.
			else {
				var property = this.property(name);
				// There are two types of properties, cookie and element.
				if (property.flag('c')) {
					// Get cookie value.
					var cookieName = name + '-' + this.id;
					value = $.cookies.get(cookieName);
				}
				else { // Not cookies, it may be saved throught JS object or inside HTML elements.
					// Custom implemetation.
					switch (name) {
						case 'code': // Code is throught ACE-Editor Object.
							value = this.aceEditor.getSession().getValue();
						break;
						default: // Get property value from html elements.
							var element = this.box.find(property.selector);
							switch (property.flag('l')) {
								// Custom implementation for reading checkboxes list.
								case true:
									value = [];
									element.each(
										function(index, element) {
											element = $(element);
											if (element.prop('checked')) {
												value.push(element.val());
											}
										}
									)
								break;
								default:
									value = element.val();
								break;
							}
						break;
					}
				}
				// If empty and _default is provided, return _default.
				if (!value && (_default != undefined)) {
					value = _default;
				}
			}
			return value;
		}
		
		/**
		*
		*
		*
		*
		*
		*/
		this.getDIFields = function() {
			var fieldsNames = ['links', 'expressions', 'pinPoint', 'pages', 'posts', 'categories'];
			var diFieldsSelector = [];
			var diFields;
			var block = this; // To be used inside each().
			$.each(fieldsNames,
				function(index, field) {
					diFieldsSelector.push(block.property(field).selector);
				}
			);
			diFields = block.box.find(diFieldsSelector.join(','));
			return diFields;
		}
		
		/**
		* Get operation queue object for the block.
		*
		* The method call CJTServer.getQueue method with the name parameter
		* generated from the operation name and the block id. The result is each block has
		* an object for each operation it may process.
		* 
		* @param string Operation name.
		* @return CJTBlocksServerQueue Operation queue object.
		*/		
		this.getOperationQueue = function(operation) {
			var name = (operation + '-operation-' + this.get('id').toString());
			var queue = CJTBlocksPage.server.getQueue('Blocks', name, 'blocksPage', 'save_blocks');
			return queue;
		}
		
		/**
		* @internal
		* 
		* Prepare property selector object.
		*
		* @param string Property name.
		* @return object Property selector object.
		*/		
		this.property = function(name) {
			if (properties[name] == undefined) {
				$.error('Property ' + name + ' is not found');
			}
			else {
				// Copy/Clone property object.
				var property = $.extend({}, properties[name]);
				// Add flag checker method to property object.
				property.flag = function(flag) {
					var flagIndex = this.flags.indexOf(flag);
					return (flagIndex == -1) ? false : true;
				};
				// Place block id in the selector.
				if (property.selector != undefined) {
					property.selector = property.selector.replace('{blockId}', this.id);
					// Implement set/get common interface through all fields.
					if (property.flag('l')) {
						property.setValue = function(values) {
							var fields = $(this.selector);
							// If values is integer then each bit represent single field value.
							if ($.isNumeric(values)) {
								var flag;
								var arrayValues = [];
								// Convert bit-map to array.
								for (weight = 0; weight < 32; weight++) {
									flag = Math.pow(2, weight);
									if (flag & values) {
										arrayValues.push(flag.toString(16));
									}
								}
								values = arrayValues;
							}
							else if (values == undefined) {
								// We need all fields to be unchecked is pin is not passed,
								values = [];
							}
							// Check/Uncheck fields.
							fields.each(
								function(index, field) {
									var field = $(field);
									var checked = (values.indexOf(field.val()) == -1) ? false : true;
									field.prop('checked', checked);
									// Fire change event so that
									// notification save change action can take place.
									field.change();
								}
							);
						};
					}
					else if (property.flag('c')) {
						property.setValue = function() {

						};					
					}
					else {
						property.setValue = function(value) {
							var jNode = $(this.selector);
							var node = jNode.get(0);
							if (node.value == undefined) {
								node.setValue(value);
							}
							else {
								node.value = value;
							}
							// Fire change event so that
							// notification save change action can take place.
							jNode.change();
						};
					}
				}
			}
			return property;
		}
		
		/**
		* Set block property value.
		*
		*
		* @param {string} Property name.
		* @param {string} Property value.
		* @return CJTServer.getDeferredObject().promise()
		*/
		this.set = function(name, newValue) {
			// In case (value != newValue) is FALSE
			// return Dummy Promise object.
			var promise = CJTServer.getDeferredObject().promise();
			var property = this.property(name);
			// Check if the property is writable.
			if (!property.flag('w')) {
				$.error('Could not write to read-only property');
			}
			else {
				// There are two types of properties, cookie and element.
				if (property.flag('c')) {
					var expires = new Date((new Date()).getTime() + ((30 * 24 * 60 * 60) * 1000)); // Live for 1 month.
					// Set cookie value.
					var cookieName = name + '-' + this.id;
					$.cookies.set(cookieName, newValue, {expiresAt : expires});
				}
				else {
					// Get element value.
					var element = this.box.find(property.selector);
					var value = element.val();
					// Update only if not same.
					if ((newValue != undefined) && (value != newValue)) {
						// Update on the server.
						var data = {
							id : this.get('id'),
							property : name,
							value : newValue
						};
						// Save property at the server.
						queue = this.getOperationQueue(name);
						promise = queue.add(data)
						.success(
							function(rProperty) {
								// Change local value to new value.
								element.val(rProperty.value);
							}
						);
					}
				}
			}
			return promise;
		}
		
		/**
		* Send block Ajax queues to the server.
		*
		* This method calls CJTServerQueue.send method.
		* The result is sending all updated properties queue to the server.
		* 
		* Please note: CJTServerQueue.send method will do nothing if the queue is locked.
		* 
		* @return jqxhr
		*/
		this.sync = function(name, data) {
			var ajaxPromise = this.getOperationQueue(name).send('post', data);
			return ajaxPromise;
		}
		
		/**
		* Switch block flag property.
		*
		* Because flag can represent only two states. This method
		* is to simplify changing flag value. It automatically detect 
		* current flag value and the new value OR simply the value can be 
		* forced to a specific state throught the newValue parameter. 
		*
		* @param string Flag name.
		* @param [mixed] Force flag value to newValue.
		* @return CJTServer.getDeferredObject().promise()
		*/
		this.switchFlag = function(flag, newValue) {
			var promise;
			// Automatically switch the value if not specified.
			if (newValue == undefined) {
				var value = this.get(flag);
				var possibleValues = flags[flag];
				var currentIndex = possibleValues.indexOf(value);
				//// Truth Table ////
				// currentIndex(0) XOR 1 = 1
				// currentIndex(1) XOR 1 = 0
				var newIndex = currentIndex ^ 1;
				var newValue = possibleValues[newIndex];			
			}
			promise = this.set(flag, newValue);
			return promise;
		}
		
		/**
		* Save Direct Interact Fields.
		*
		* @return void
		*/
		this.queueDIFields = function() {
			var dIFields = ['code', 'pinPoint', 'pages', 'posts', 'categories', 'links', 'expressions'];
			var queue = this.getOperationQueue('saveDIFields');
			var block = this; // To use inside .each().
			// For every field create queue request as a single property for the block.
			$.each(dIFields,
				function(index, property) {
					var field = {
						id : block.get('id'),
						property : property,
						value : block.get(property)
					};
					// Add to queue list.
					queue.add(field);
				}
			);
		}
		
		// Initialize object.
		this.CJTBlock();
		
	} // End class.
	
})(jQuery);