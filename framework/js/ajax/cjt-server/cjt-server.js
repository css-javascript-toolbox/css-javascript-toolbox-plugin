/**
* @version $ Id; cjtserver.js 21-03-2012 03:22:10 Ahmed Said $
* 
* CJT Ajax core class.
*/

/*
* Put CJTServer class at global scope.
*/
var CJTServer;

/*
* JQuery wrapper for the CJTServer object.
*/ 
(function($){

	/*
	*	Hold CJT Core Ajax methods.
	* 
	* All Ajax (post, get) operations should go through this object.
	*
	* @author Ahmed Said
	* @version 6
	*/
	CJTServer = {
		
		/**
		* put your comment there...
		* 
		* @type String
		*/
		actionPrefix : 'cjtoolbox',
		
		/*
		* Wordpress admin Ajax URL.
		*
		* @internal
		* @var string
		*/
		ajaxURL : ajaxurl,
		
		/*
		* Mapping to all CJT available ajax controllers.
		*
		* @internal
		* @var object
		*/
		controllers : {
			block : 'block-ajax',
			blocksPage : 'blocks-page-ajax',
			blocksBackups : 'blocks-backups',
			templatesLookup : 'templates-lookup',
			templatesManager : 'templates-manager',
			templateRevisions : 'template-revisions',
			template : 'template',
			settings : 'settings',
			metabox : 'metabox'
		},
		
		/*
		* Wordpress nonce for ajax operations.
		*
		* @internal
		* @var string
		*/
		securityToken : '',

		/*
		* Queued Ajax operations stored here.
		*
		* Each operation can be expressed as a queued object.
		* Each queued object is stored here for send to the server later.
		* 
		* @var object
		*/
		queue : {},

		/*
		* Destroy queue object.
		*
		* The method delete the queue object from the queue list.
		*
		* @param queue Queue object to destory.
		* @return void
		*/
		destroyQueue : function(queue) {
			delete this.queue[queue.key];
		},
		
		/*
		* Get jQuery-Ajax-Like object.
		* 
		* This method return jQuery.Deferred() object
		* with .success and .error methods added as aliased for
		* .done and .fail respectively.
		*
		* Return Object is jQueryDeferred() with the following methods added.
		*		- success = function(callbacks) { return this.done(callbacks); }
		* 	- error = function(callbacks) { return this.fail(callbacks); }
		* 
		* @return jQuery.Deferred();
		*/		
		getDeferredObject : function() {
			var deferred = $.Deferred();
			deferred.completeDeferred = $.Deferred();
			// Add success and error methods to the promise object.
			deferred.promise().success = function(callbacks) { return this.done(callbacks); };
			deferred.promise().error = function(callbacks) { return this.fail(callbacks); };
			deferred.promise().complete = function(callbacks) { return deferred.completeDeferred.done(callbacks); };
			return deferred;
		},
		
		/*
		* Get CJT Server request object.
		*
		* Every request to the CJT server required header (not HTTP header) data like
		* security nonce and some more fields. The point is to centralize the method that
		* build the request object. Always merge your Ajax data with requestObject for requesting
		* the server.
		*
		* Return object
		*		- url: Ajax URL.
		* 	- data
		* 		- @security string Wordpress nonce.
		* 		- @requestTime string Request time.
		* 		- @requestId string Request unique number.
		*
		* @param string Controller map name.
		* @param string Action name.
		* @param object User data to send over to the server.
		* @return object Request Data with data param merged to it.
		*/
		getRequestObject : function(controller, action, data) {
			var requestObject = {};
			var requestTime = new Date();
			// CJT Wordpress Plugin Ajax hooks prefix.
			action = this.actionPrefix + '_' + action;
			// Action & Controller parameter always in the URL -- not posted.
			var queryString = 'action=' + action + '&controller=' + CJTServer.controllers[controller];
			var url = CJTServer.ajaxURL + '?' + queryString;
			// Prepare request object.
			var requestToken = {
				security : CJTServer.securityToken,
				requestTime : requestTime,
				requestId : requestTime.getTime()
			};
			// Combine user data with request parameters data.
			data = $.extend(requestToken, data);
			// Set return object.
			requestObject.url = url;
			requestObject.data = data;
			return requestObject;
		},

		/*
		* Get Ajax URL for a specific resource specified by controller and action.
		*
		* Don't ever use ajaxURL var directly, use this method instead.
		* The purpose of this method is to serve the Popup forms.
		* Instead of building URL every time a Popup for i srequested,
		* this method will do that for you.
		*
		* This method should used only for GET requests.
		* 
		* @param string Controller map name.
		* @param string Action name.
		* @param object User data to send over to the server as query string parameters.
		* @return string Request URL. 
		*/
		getRequestURL : function(controller, action, data) {
			var requestObject = CJTServer.getRequestObject(controller, action, data);
			var url = requestObject.url + '&' + $.param(requestObject.data);
			return url;
		},
		
		/*
		* Get Ajax queue object.
		* 
		* Ajax queue objects is used to queue Ajax operations locally
		* and then send them as a batch.
		*
		* When the queue is requested for the first time it'll be
		* created and cached, any further request will get a reference to
		* the same instance. The queue is identified by classKey, name, controller
		* and action parameters.
		*
		* ClassKey Parameter: ClassKey as the word between CJT and ServerQueue phrases.
		* Any queue server must use this schema CJT[CLASS-KEY]ServerQueue.
		*
		* @param string classKey Class key.
		* @param string Unique name for the queue.
		* @param string Controller map name.
		* @param string Action name.
		* @return CJTServerQueue pointer.
		*/
		getQueue : function(classKey, name, controller, action) {
			var queueKey = hex_md5(classKey + name + controller + action);
			var queue = null;
			var queueClass = 'CJT' + classKey + 'ServerQueue';
			if (CJTServer.queue[queueKey] == undefined) {
				// Create new queue object.
				queue = new window[queueClass](controller, action, queueKey);
				// Add to queue list.
				CJTServer.queue[queueKey] = queue;
			}
			else {
				queue = CJTServer.queue[queueKey];
			}
			return queue;
		},
		
		/**
		* Impersonate Wordpress Ajax request to dispatch specific CJT controller.
		*
		* Add Security Token and controller parameters to ajaxurl variable
		* to send along with Wordpress Ajax request.
		*
		* Don't forget to call resetWordpressAjaxURL
		*
		* @param string Controller name.
		* @return void
		*/
		impersonateWPAR : function(controller) { // Wordpress Ajax Request.
			// Add security token.
			ajaxurl += '?security=' + CJTServer.securityToken;
			// Add Controller.
			ajaxurl += '&controller=' + CJTServer.controllers[controller];
		},
		
		/*
		* initialize CJTServer object. 
		*
		* @internal
		* @return void
		*/
		init : function() {
			// Caching Security nonce value.
			CJTServer.securityToken = $('input:hidden#cjt-securityToken').val();
		},
		
		/*
		* Reset/Deimpersonate Wordpress ajaxurl variable.
		*
		*
		* @return void
		*/
		resetWordpressAjaxURL : function() {
			// Reset ajaxurl to its original value.
			ajaxurl = CJTServer.ajaxURL;
		},
		
		/*
		* Send Ajax request to server.
		*
		* requestType parameter:
		*		- get: Send get Request
		*		- set: Post request.
		* 
		* @param string Controller map name.
		* @param string Action name.
		* @param object data to send.
		* @param string Any valid http request methods.
		* @return jqxhr
		*/
		send : function(controller, action, data, requestMethod, returnType) {
			var request = null;
			var promising = null;
			// Set default request method.
			requestMethod = (requestMethod == undefined) ? 'get' : requestMethod;
			// Set default return type to JSON.
			returnType = (returnType == undefined) ? 'json' : returnType;
			// Send the request.
			request = CJTServer.getRequestObject(controller, action, data);
			promising = $[requestMethod](request.url, request.data, null, returnType);
			return promising;
		},
	
		/**
		* Serialize form into object.
		*
		* Sometime when forms needed to be serialized into something
		* accessible like object/array its likely to use jQuery.serializeArray.
		* jQuery.serializeArray has one problem: It returns each property as following.
		* [0 : {name : NAME, value : VALUE}]
		* 
		* @TODO Remove this method and use Wordpress jquery-serialize-object Plugin instead.
		* The purpose of this method is to remove the index wrapper and return
		* { name : NAME, value : VALUE }
		*
		* @param HTMLFormElement/Jquery object.
		* return object
		*/		
		serializeObject : function(form) {
			var jquerySerializedArray = form.serializeArray();
			var serializedObject = {};
			$(jquerySerializedArray).each(
				function(index, item) {
					serializedObject[item.name] = item.value;
				}
			);
			return serializedObject;
		},
		
		/**
		* 
		*/
		switchAction : function(newAction, uri) {
			var actionParameter = 'action=' + (this.actionPrefix + '_' + newAction);
			var repExp = new RegExp('action\=[^\&]+');
			if (uri == undefined) {
				uri = document.location.href;
			}
			return uri.replace(repExp, actionParameter);
		}
		
	} // End class.

	// Initialize CJTServer object when document is ready.
	$(CJTServer.init);
	
})(jQuery);