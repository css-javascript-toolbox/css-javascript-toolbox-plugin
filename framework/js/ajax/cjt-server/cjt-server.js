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
		
		/*
		* Wordpress admin Ajax URL.
		*
		* @internal
		* @var string
		*/
		ajaxURL : window.top.ajaxurl,
		
		/*
		* Mapping to all CJT available ajax controllers.
		*
		* @internal
		* @var object
		*/
		controllers : {
			block : 'block-ajax',
			codeFiles : 'code-files',
			blocksPage : 'blocks-ajax',
			blocksBackups : 'blocks-backups',
			templatesLookup : 'templates-lookup',
			templatesManager : 'templates-manager',
			templateRevisions : 'template-revisions',
			template : 'template',
			settings : 'settings',
			metabox : 'metabox',
			installer : 'installer',
			setup : 'setup',
			tinymceBlocks : 'tinymce-blocks',
			packages : 'packages',
			packageFile : 'package-file',
			'package' : 'package',
			tools : 'tools'
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

				/**
		* put your comment there...
		* 
		* @type String
		*/
		pageId : 'cjtoolbox',
		
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
		* 		- @Page string cjtoolbox to identify the request to be handler by CJTPlugin.
		*
		* @param string Controller map name.
		* @param string Action name.
		* @param object User data to send over to the server.
		* @return object Request Data with data param merged to it.
		*/
		getRequestObject : function(controller, action, data) {
			var requestObject = {};
			var requestTime = new Date();
			// Stop controller name mapping!!!
			controller = (CJTServer.controllers[controller] !== undefined) ? CJTServer.controllers[controller] : controller;
			// CJT Wordpress Ajax Access Point!
			var accessPoint = this.pageId + '_api';
			// Action & Controller parameter always in the URL -- not posted.
			var queryString = 'action=' + accessPoint + '&controller=' + controller + '&CJTAjaxAction=' + action;
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
		
		/*
		* initialize CJTServer object. 
		*
		* @internal
		* @return void
		*/
		init : function() {
			// Caching Security nonce value.
			var securityToken = $('input:hidden#cjt-securityToken').val();
			if (securityToken) {
				CJTServer.securityToken = securityToken;
			}
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
		send : function(controller, action, data, requestMethod, returnType, inSettings) {
			var request = null;
			var promising = null;
			// Set default request method.
			requestMethod = (requestMethod == undefined) ? 'get' : requestMethod;
			// Set default return type to JSON.
			returnType = (returnType == undefined) ? 'json' : returnType;
			// Build Request URL and Data.
			request = CJTServer.getRequestObject(controller, action, data);
			// Ajax Request Settings.
			var settings = $.extend({type : requestMethod, data : request.data, dataType : returnType},
															inSettings ? inSettings : {});
			// Merge settings with passed settings.
			// Request!
			promising = $.ajax(request.url, settings)
			/* @TODO: This is a temporary solution for version 6.0 to be releases! Later we'll have a full error handling system! */
			// --- Start temporary Error handling Block ---
			.error($.proxy(
				function(jqXHR, textStatus) {
					switch (textStatus) {
						case 'parsererror':
							// For now just create a temporary element to be over any other elements!
							if (confirm(CJTCjtServerI18N.confirmSubmitErrorForm)) {
								this.unhandledErrorSubmissionForm(jqXHR.responseText);
							}
						break;
					}
				}, this)
			);
			// --- End temporary Error handling Block ---
			return promising;
		},
		
		/**
		* 
		*/
		switchAction : function(newAction, uri) {
			var actionParameter = 'action=' + (this.pageId + '_' + newAction);
			var repExp = new RegExp('action\=[^\&]+');
			if (uri == undefined) {
				uri = document.location.href;
			}
			return uri.replace(repExp, actionParameter);
		},

		/**
		* @internal
		*/
		unhandledErrorSubmissionForm : function(text) {
			//Create form dialog if not exists!
			var jQuery = window.top.jQuery;
			var errorForm = jQuery.find('#cjt-unhandled-error-form');
			if (!errorForm.length) {
				// Creating form element!
				var errorForm = jQuery('<div id="#cjt-unhandled-error-form"><div class="content"></div><input type="button" /></div>').appendTo(jQuery('body'));
			}
			// Close Button!
			errorForm.find('input:button')
			// Localizing!
			.prop('value', 'Close')
			// Styling
			.css({float : 'right', width : '100px'})
			// Close form!
			.click($.proxy(
				function() {
					if (confirm(CJTCjtServerI18N.confirmCloseErrorForm)) {
						errorForm.remove();
					}
				}, this)
			);
			// Error!
			errorForm.find('.content').html(text)
			// Styling!
			.css({overflow : 'auto', height : '92%', 'text-align' : 'center'});
			// Form!
			errorForm.css({
				/* Positioning center*/
				position : 'fixed',
				backgroundColor : 'white',
				left : '2.5%',
				top : '2.5%',
				width : '95%',
				height : '91%',
				/* Styling */
				padding : '10px 10px',
				border : '4px solid gray',
				/* Over everything! */
				'z-index' : 10000000,
				display : 'block'
			});
		}
		
	} // End class.

	// Initialize CJTServer object when document is ready.
	$(CJTServer.init);
	
})(jQuery);