/**
*
*/
var CJTBlocksAjaxMultiOperations;

/**
*
*/
(function($) {

	/**
	*
	*
	*
	*
	*/
	var operations = {
		save : {
			element : '.cjttbl-save',
			eventName : '_onsavechanges',
			queueName : 'saveDIFields'
		},
		switchLocation : {
			element : '.cjttbl-location-switch',
			eventName : '_onswitchflag',
			params : {flag : 'location'},
			queueName : 'location'
		},
		switchState : {
			element : '.cjttbl-state-switch',
			eventName : '_onswitchflag',
			params : {flag : 'state'},
			queueName : 'state'
		}
	};
			
	/**
	*
	*
	*
	*
	*/
	CJTBlocksAjaxMultiOperations = function(controller, action) {

		/**
		*
		*
		*
		*
		*/	
		this.action = null;
		
		/**
		*
		*
		*
		*
		*/
		this.controller = null;
		
		/**
		*
		*
		*
		*
		*/
		this.queue = null;
		
		/**
		*
		*
		*
		*
		*/
		this._init = function(controller, action) {
			this.controller = controller;
			this.action = action;
		}
		
		/**
		*
		*
		*
		*
		*/		
		this.trigger = function(operationName, params) {
			// Having queue for every multioperation allow executing multi-multioperations.
			// in the same time, e.g user can deactivate/activate all and then header/footer all
			// in the same time without interfering with each others.
			var multiOperationQueue = new CJTBlocksServerQueue(this.controller, this.action);
			var operation = operations[operationName];
			// Make sure operation.params is object.
			operation.params = (operation.params != undefined) ? operation.params : {};
			// Prepare event paramaters.
			var dummyEventObject = {};
			var eventParams = $.extend(operation.params, params);
			// For every block trigger switch state event.
			var blocks = CJTBlocksPage.blocks.getBlocks();
			blocks.each(
				function() {
					var block = $(this);
					// CJTBlock Plugin set a reference in oringal DOMNode.
					var cjtBlock = this.CJTBlock;
					// Inisde jquery Plugin we can find block model object.
					var queue = cjtBlock.block.getOperationQueue(operation.queueName);
					// Set it just if the original element is clicked.
					dummyEventObject.target = block.find(operation.element);
					// Lock the queue to prevent send the Ajax request for every single block.
					// We'll send it all at once.
					queue.lock();
					// Triger the event.
					cjtBlock[operation.eventName](dummyEventObject, eventParams);
					// Get single block queue into the internal queue.
					multiOperationQueue.merge(queue);
					// Delete single block queue.
					CJTServer.destroyQueue(queue);
				}
			);
			return multiOperationQueue; 
		}
		
		// Initialize.
		this._init(controller, action);
		
	} // End class.
	
})(jQuery);