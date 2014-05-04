/**
* @version $ Id; cjtserver.js 21-03-2012 03:22:10 Ahmed Said $
* 
* Block model class.
*/

/**
* Put CJTBlock class at global scope.
*/
var CJTBlockBase;

/**
* JQuery wrapper for the CJTBlock class.
*/
(function($) {
	
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
	CJTBlockBase = function() {
		
		/**
		* 
		*
		*
		*/
		this.aceEditor = null;
		
		/**
		* 
		*/
		this.blockPlugin = null;
		
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
		* Blocks property that allowed to be read or write.
		*
		* flags:
		*	- r : read.
		*	- w : write.
		*	- c : cookie.
		*	- S : Save when block is saved.
		*
		* @var object
		*/
		this.properties = {};

		/**
		* 
		*/
		this.addProperties = function(properties) {
			// Prepare properties, add them to model list.
			$.each(properties, $.proxy(
				function(name, propertyDefinition) {
					// Add flag checker method to property object.
					propertyDefinition.flag = function(flag) {
						var flagIndex = this.flags.indexOf(flag);
						return (flagIndex == -1) ? false : true;
					};
					// Add name to the property object.
					propertyDefinition.name = name;
					// Place block id in the selector.
					if (propertyDefinition.selector != undefined) {
						propertyDefinition.selector = propertyDefinition.selector.replace('{blockId}', this.id);
					}
					// If OM is supported bind it.
					if (propertyDefinition.om !== undefined) {
						propertyDefinition.om.bind(this.blockPlugin, propertyDefinition);
					}
					// Cache property object.
					this.properties[name] = propertyDefinition;
				}, this)
			);
		};

		/**
		* put your comment there...
		* 
		* @param element
		*/
		this.CJTBlockBase = function(blockPlugin, element, properties) {
			// Initialize.
			this.blockPlugin = blockPlugin;
			this.box = $(element);
			this.id = parseInt(this.box.find('input:hidden[name="blocks[]"]').val())
			this.properties = {};
			// Define base properties.
			properties.name = {om : new CJTBlockPropertyHTMLNodeOM(), flags: 'rw', selector : 'input:hidden[name="cjtoolbox[{blockId}][name]"]'};
			properties.location = {om : new CJTBlockPropertyHTMLNodeOM(), flags: 'rw', selector : 'input:hidden[name="cjtoolbox[{blockId}][location]"]'};
			properties.state = {om : new CJTBlockPropertyHTMLNodeOM(), flags: 'rw', selector : 'input:hidden[name="cjtoolbox[{blockId}][state]"]'};
			// Initialize ALL (BASE, DERIVDED) properties.
			this.addProperties(properties);
		}
		
		/**
		* Get block property cvalue.
		*
		* @param string Property name.
		* @return mixed
		*/
		this.get = function(name, _default) {
			// Initialize.
			var value = null;		
			var property = this.property(name);
			// Check member variables first.
			if (this[name] != undefined) {
				value = this[name];
			}
			// There are two types of properties, cookie and element.
			else if (property.flag('c')) {
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
						value = property.om.get();
					break;
				}
			}
			// If empty and _default is provided, return _default.
			if (!value && (_default != undefined)) {
				value = _default;
			}
			// Returns
			return value;
		}
		
		/**
		* 
		*/
		this.getDIFields = function() {
			// Initialize.
			var diFields = null;
			// Query DIFields selectors nodes.
			diFields = this.box.find(this.getDIProperties().selector.join(','));
			// Returns diFields
			return diFields;
		}
		
		/**
		* 
		*/
		this.getDIProperties = function() {
			// Initialize.
			var diProperties = {selector : [], list : {}};
			// Collect DIFields from the properties list.
			$.each(this.properties, $.proxy(
				function(name, property) {
					// All fields with 's' flag is a DIField.
					if (property.flag('s')) {
						// Add property to the list.
						diProperties.list[name] = property;
						// Add the selector as well.
						diProperties.selector.push(property.selector);
					}
				}, this)
			);
			// Return DIProperties.
			return diProperties;
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
		* 
		*/
		this.loadBase = function(properties) {
			// Enable ACE Editor.
			this.aceEditor = ace.edit('editor-' + this.id);
			// Add ACE Editor Propety definition.
			properties.code = {om : new CJTBlockPropertyACEEditor(), flags: 'rws', selector : 'div#editor-{blockId}'};
			properties.editorLang = {flags: 'rwc'};
			properties.editorTheme = {flags: 'rwc'};
			properties.aceEditorMenuSettings = {flags: 'rwc'};
			// Initialize ALL (BASE, DERIVDED) properties.
			this.addProperties(properties);
			// Create bridge through "code" field
			// so that div element can set/get aceEditor real object.
			var codeDiv = $(this.property('code').selector).get(0);
			codeDiv.getValue = function() {
				return this.aceEditor.getSession().getValue();
			};
			// Define aceEditor extension method for setting Editor Text with the possibility
			// of UNDO.
			this.aceEditor.setValuePossibleUndo = function(value) {
				// Directly clear using setValue('') prevent 'undo' action!
				// Select all text.
				this.selectAll();
				// Replace content with empty string!
				this.getSession().replace(this.getSelectionRange(), value);
				this.focus();
			};
		};

		/**
		* @internal
		* 
		* Prepare property selector object.
		*
		* @param string Property name.
		* @return object Property selector object.
		*/		
		this.property = function(name) {
      // Get property object from the cache.
      var property = this.properties[name];
      // Returns property cached object used to
      // get and set property values.
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
			// Initialize.
			var dIFields = this.getDIProperties().list;
			var queue = this.getOperationQueue('saveDIFields');
			var blockId = this.get('id');
			// For every field create queue request as a single property for the block.
			$.each(dIFields, $.proxy(
				function(name) {
					var field = {
						id : blockId,
						property : name,
						value : this.get(name)
					};
					// Add to queue list.
					queue.add(field);
				}, this)
			);
		}
		
	} // End class.
	
})(jQuery);