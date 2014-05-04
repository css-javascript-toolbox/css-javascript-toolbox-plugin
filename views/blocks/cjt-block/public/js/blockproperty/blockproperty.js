/**
* 
*/

var CJTBlockPropertyAPItemsList;

/**
* 
*/
(function($) {

	/**
	* put your comment there...
	* 
	* @param assignPanel
	*/
	CJTBlockPropertyAPItemsList = function(assignPanel) {
		
		/**
		* put your comment there...
		* 
		*/
		var block = null;
		
		/**
		* put your comment there...
		* 
		*/
		var property = null;
	
		/**
		* 
		*/
		this.bind = function(blk, prprty) {
			// Bind to property object.
			block = blk;
			property = prprty;
		}

		/**
		* 
		*/
		this.get = function() {
			// Initialize.
			var map = block.pagesPanel.getMap();
			// Get values directy from the assignment panel
			var value = map[property.name];
			// Returns
			return value;
		}
		
		/**
		* 
		*/
		this.getValueCache = function() {
			// Initialize.
			var pagesPanel = block.pagesPanel;
			var buttons = pagesPanel.buttons[property.name];
			var value = {};
			var type, list;
			// Get the map.
			value.map = this.get();
			// Get all types.
			value.types = {};
			// Get all types values for current type.
			$.each(buttons, $.proxy(
				function(index, button) {
					// List.
					list = button.data('list');
					// Get type object prototype
					type = pagesPanel.getTypeObject();
					// Read button state-vars.
					$.each(type.button.stateVars, $.proxy(
						function(name) {
							type.button.stateVars[name] = button.data(name);
						}, this)
					);
					// Read list state-vars.
					$.each(type.list.stateVars, $.proxy(
						function(name) {
							type.list.stateVars[name] = list.data(name);
						}, this)
					);
					// Get list items, deattach them from the list.
					type.list.items = list.children().detach();
					// Push to the types list.
					value.types[list.data('params')['type']] = type;
				}, this)
			);
			// Return types list.
			return value;
		}

		/**
		* 
		*/
		this.reset = function() {
			// Should reset all fields to Server Synched value.
			// For now its developed as standard iunterface.
			// so that AssignOnly mode switcher don't fail
			// or to need to filter what properties to process.
		};

		/**
		* 
		*/
		this.setValue = function(value) {
			// Initialize.
			var pagesPanel = block.pagesPanel;
			var list, type, typeName;
			if (!value) {
				value = {map : {}, types : {}};
			}
			// Get 'group' type buttons.
			var buttons = pagesPanel.buttons[property.name];
			// For each button reset all 'status data'.
			$.each(buttons, $.proxy(
				function(index, button) {
					// Get list.
					list = button.data('list');
					// Get button items-list type name
					typeName = list.data('params')['type'];
					// Get type object from the new-value.
					type = (value.types[typeName] !== undefined) ? value.types[typeName] : pagesPanel.getTypeObject();
					// Write button stateVars.
					$.each(type.button.stateVars, $.proxy(
						function(name, value) {
							button.data(name, value);
						}, this)
					);
					// Write List stateVars.
					$.each(type.list.stateVars, $.proxy(
						function(name, value) {
							list.data(name, value);
						}, this)
					);
					// Remove all pins under that 'button' list.
					list.empty();
					// Add items to the list.
					list.append(type.list.items);
					// Reset Pagination list.
					button.data('paginationList').reset();
					// Reset info panel.
					var infoPanel = list.data('infoPanel');
					infoPanel.find('.info-panel-total').text(list.data('totalItemsCount'));
					infoPanel.find('.info-panel-loaded-count').text(list.data('loadedCount'));
				}, this)
			);
			// Set the map.
			pagesPanel.setMapGroup(property.name, value.map);
		};
		
	}
	
})(jQuery);