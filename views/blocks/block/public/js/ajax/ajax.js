/**
*
*/

var CJTBlocksServerQueue;

/**
*
*
*
*/
(function($) {

	/**
	*
	*
	*
	*/
	CJTBlocksServerQueue = function(controller, action, key) {
	
	  // Initialize base class.
		this._init(controller, action, key);
		
		/**
		*
		*
		*
		*/
		this.getData = function() {
			var data = {};
			// Handle actions requsts.
			switch (this.controller) {
				case 'blocksPage':
					switch (this.action) {
						case 'save_blocks':
							var blocks = {};
							// Prepare blocks data.
							$(this.queue).each(
								function(index, queue) {
									// CJTBlocksServerQueue doesn't use any queue types except type 'queue'.
									if (queue.type == 'queue') {
										var partialBlock = queue.data;
										blocks[partialBlock.id] = (blocks[partialBlock.id] != undefined) ? blocks[partialBlock.id] : {};
										blocks[partialBlock.id][partialBlock.property] = partialBlock.value;									
									}
								}
							)
							data = {blocks : blocks};
						break;
					}
				break;
			}
			return data;
		}
		
		/**
		*
		*
		*
		*/
		this.getResponseParameters = function(blocks, data) {
			var params;
			// Handle actions requsts.
			switch (this.controller) {
				case 'blocksPage':
					switch (this.action) {
						case 'save_blocks':
							var block = blocks[data.id];
							var rProperty = block[data.property];
							// Build response parameters.
							params = [rProperty, data, blocks];
						break;
					}
				break;
			}
			return params;
		}
		
	} // End class.
	
	// Extend CJTServerQueue.
	CJTBlocksServerQueue.prototype = new CJTServerQueue();
	
})(jQuery);