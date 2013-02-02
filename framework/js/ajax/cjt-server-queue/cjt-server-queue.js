/**
* @version $ Id; cjtserverqueue.js 21-03-2012 03:22:10 Ahmed Said $
* 
* CJTServerQueue class.
*/

/*
* Put CJTServerQueue class at global scope.
* 
* @var CJTServerQueue
*/
var CJTServerQueue;

/*
* JQuery wrapper for the CJTServerQueue object.
*/ 
(function($) {

	/*
	*	Abstract base class for Ajax Queue classes.
	* 
	* This is a prototype and cannot be used without a derivided class.
	* There is two abstract method must be implemented in the child class.
	*
	* Abstracts:
	* 	- getData() : This method get called when this.send method is called right before
	* 								sending the request to the server. The purpose of the method is to Encapsulate the 
	*									queues data and prepare it for sending.
	*		- getResponseParameters(response, data): This method called when server response. The method will be called
	*									for every added queue. The purpose of the method is to de-encapsulate response object
	*									to pass for every queue.
	* 
	* @author Ahmed Said
	* @version 6
	*/
	CJTServerQueue = function() {
	
		/*
		* Operation Action.
		*
		* @var string
		*/
		this.action = null;

		/*
		* Controller map name.
		*
		* @var string
		*/
		this.controller = null;

		/*
		* Queue object unique identifier. 
		* 
		* @internal 
		* @var string
		*/
		this.key = '';
		
		/*
		* Lock or Unlock queue object allow and disallow sending
		* the request to the server when .send() method is called.
		*
		* @var boolean
		*/
		this.locked = false;
		
		/*
		* Operations queue.
		* 
		* All queued operations are stored here waiting
		* for sending.
		*
		* @var object
		*/
   	this.queue = [];
   	
		/*
		* Derived classed constructor.
		* 
		* Call this from child classes for initialize objects.
		* 
		* @param string Controller map name. 
		* @param string Action name.
		* @param string Queue key.
		* @return void
		*/
   	this._init = function(controller, action, key) {
			this.controller = controller;
			this.action = action;
			this.key = key;
			// Reset prototype copy vars.
			this.queue = [];
			this.locked = false;
   	}
   	
		/*
		* Add request to the queue.
		* 
		* The method push the new data to the queue list.
		*
		* The returned object is jQuery Ajax-Like object that has .success and .error
		* methods implemented. You can add callbacks to the returned object as like as you
		* need. When the queue is sent to the server and the response received,
		* all these methods will be called with the context parameter as "this" pointer.
		*
		* Possible values for queue type is 'queue' and endpoint.
		*
		* endpoint is extension to send method when the object is locked.
		* This allow dispatch method to call deferred methods added through send method
		* when the object was locked.
		* 
		* @param object Data to add to queue.
		* @param mixed context to be used for deferred callbacks (e.g success, error).
		* @param string Queue type.
		* @return CJTServer.getDeferredObject.promise()
		*/
		this.add = function(data, context, type) {
			var queue = {
				deferred : CJTServer.getDeferredObject(),
				data : data,
				context : context,
				type : ((type == undefined) ? 'queue' : type)
			};
			var promise = queue.deferred.promise();
			// Add queue object to the queue.
			this.queue.push(queue);
			return promise;
		}
		
		/*
		* Clear queues list.
		* 
		* The method quietly clear queue list.
		*
		* No callbacks called when queue is cleared.
		* 
		* @return void
		*/
		this.clear = function() {
			this.queue = [];
		}
		
		/*
		* Dispatch callbacks associated for the all the available queues.
		* 
		* @internal
		* 
		* state parameter possible values are:
		* 	- resolve
		*		- reject 
		*
		* @param string jQuery.Deferred states.
		* @param object Response Object to pass to the callbacks.
		* @return void
		*/
		this.dispatch = function(state, response) {
			var method = state + 'With';
			var serverQueue = this; // To use inside .each().
			var queueParams = null;
			$(this.queue).each(function(index, queue) {
        // If rejected don't call getResponseParameters() to avoid error
        // This is a temporary solution for version 6.0 to be releases!
        // Get queue parameters based on queue type.
        if ((state == 'reject') || (queue.type == 'endpoint')) {
            // endpoint type queue is queue to handle the typical/native
            // ajax response without setting up response parameters.
            queueParams = [response];
        }
				else if (queue.type == 'queue') {
					// Customize response data based on derivded class.
					queueParams = serverQueue.getResponseParameters(response, queue.data)
				}
				queue.deferred[method](queue.context, queueParams);
				// Always call completed callbacks.
				queue.deferred.completeDeferred.resolveWith(queue.context, queueParams);
			});
			// Clear queue.
			this.clear();
		}

		/*
		* Don't send the queue when send method is called.
		* 
		* This method is great when an operation need to control the behavior of 
		* another operation. An operation may prevent the queue from sending the request
		* and do that in alternative ways.
		* 
		* @return void
		*/
		this.lock = function() {
			this.locked = true;
		}

		/*
		* Merge queue object to current queue.
		*
		* @param CJTServerQueue Queue object to merge to this queue.
		* @return void
		*/		
		this.merge = function(serverQueue) {
			this.queue = $.merge(this.queue, serverQueue.queue);
		}
		
		/*
		* Send queue data to server.
		* 
		* If the object is locked nothing will happen at all.
		* If the object is unlocked a call to CJTServer.send method will be
		* processed with the data returned from the abstract method .getData().
		*
		* @param string Http Request Method @see CJTServer.send for more details.
		* @param object Data to pass along with the queue data.
		* @return CJTServer.getDeferredObject.promise()
		*/
		this.send = function(method, data) {
			var ajaxPromise = null;
			// Process only of not locked.
			if (!this.locked) {
				var queue = this; // To be used inside .each().
				// Merge data param with derived class data for the final request.
				// But first mask usre data param is passed.
				data = (data != undefined) ? data : {};
				data = $.extend(data, this.getData());
				// Send request to CJTServer object.
				ajaxPromise = CJTServer.send(this.controller, this.action, data, method)
				.success(
					function(response) {
						queue.dispatch('resolve', response);
					}
				)
				.error(
					function(response) {
						queue.dispatch('reject', response);
					}
				);
			}
			else {
				// Use Dummy Deferred object in case the object is locked.
				ajaxPromise = this.add(data, undefined, 'endpoint');
			}
			return ajaxPromise;
		}
		
		/*
		* Unlock queue object.
		* 
		* @return void
		*/		
		this.unlock = function() {
			this.locked = false;
		}
		
	} // End class.
	
})(jQuery);