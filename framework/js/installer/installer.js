/**
* 
*/

/**
* put your comment there...
* 
* @param operations
*/
var CJTInstaller;

/**
* 
*/
(function ($) {
	
	/**
	* put your comment there...
	* 
	* @param operations
	*/
	CJTInstaller = function(operations) {
		
		/**
		* 
		*/
		this.action = 'install';
		
		/**
		* 
		*/
		this.ajax = CJTServer;
		
		/**
		* 
		*/
		this.cancelled;
		
		/**
		* 
		*/
		this.controller = 'installer';
		
		/**
		* put your comment there...
		* 
		*/
		this.operationId;
		
		/**
		* 
		*/
		this.promise;
		
		/**
		* 
		*/
		this.cancel = function() {
			this.cancelled = true;
			return this;
		}
		
		/**
		* 
		* 
		* @param callback
		* @returns 
		*/
		var each = function(callback) {
			var promise = this.ajax.getDeferredObject();
			// Call installation callback!
			if (isValid.call(this)) {
				var operation = getCurrentOperation.call(this);
				callback(promise.promise(), this.operationId, operation);
				if (!this.cancelled) {
					// Install operation!
					this.ajax.send(this.controller, this.action, {operation : operation})
					.success($.proxy( // Single operation installation success.
						function(irs /*Install Response Structure! */) {
							// Notify single operation installation successed!
							promise.resolveWith(irs);
							// continue loop!
							this.operationId++; // Increase operation pointer by one!
							each.call(this, callback);
						}, this)
					).error($.proxy( // Single operation installation error.
						function(irs /*Install Response Structure! */) {
							// Notify single operation installation failure!
							promise.rejectWith(irs);
							// Notify all failures!
							this.promise.rejectWith(irs, this.operationId, operation, callback);
						}, this)
					);
				} // End if
			} // End if
			else { // All operations are done!
				if (1) { // @TODO Success condition
					this.promise.resolve();
				} 
				else { // @TODO Failure condition
					this.promise.reject();
				}
			}
		}
		
		/**
		* 
		*/
		var getCurrentOperation = function() {
			if (operations[this.operationId] === undefined) {
				$.error('Invalid operation pointer.');
			}
			return operations[this.operationId];
		}
		
		/**
		* 
		* 
		* @param callback
		* @returns
		*/
		this.install = function(callback) {
			// Reset operations iterator.
			reset.call(this);
			// Start client-server-iteration (csi).
			each.call(this, callback);
			// Overall promising!
			return this.promise.promise();
		}
		
		/**
		* 
		*/
		var isValid = function() {
			var operationsCount = operations.length;
			return ((operationsCount > 0) && (this.operationId != operations.length));
		}
		
		/**
		* 
		*/
		var reset = function() {
			// Reset our loop!
			this.promise = this.ajax.getDeferredObject();
			this.operationId = 0;
			this.cancelled = false;
		}
		
	} // End CJTInstaller class.
	
}(jQuery));