/**
* @version $ Id; cjtserver.js 21-03-2012 03:22:10 Ahmed Said $
* 
* CJT Ajax core class.
*/

/*
* Put CJTServer class at global scope.
*/
var CJTModuleServer;

/*
* JQuery wrapper for the CJTServer object.
*/ 
(function($){
	
	/**
	* Module server prototype
	* 
	* @param moduleName
	*/
	CJTModuleServer = function(moduleName) {
		
		/**
		* put your comment there...
		* 
		* @param moduleName
		*/
		var $this = this;
	
		/**
		* put your comment there...
		* 
		* @type Object
		*/
		var CJTServer = window.top.CJTServer;
		
		/**
		* 
		*/
		this.moduleNameParamName = 'cjtajaxmodule';
		
		/**
		* put your comment there...
		* 
		* @param data
		*/
		var injectModuleName = function(data) {
			// Copy data object
			var newData = $.extend({}, data);
			/// Inject Module
			newData[$this.moduleNameParamName] = moduleName;
			// Return new data
			return newData;
		};
		
		/**
		* 
		*/
		this.getRequestURL = function(controller, action, data) {
			return CJTServer.getRequestURL(controller, action, injectModuleName(data));
		};

		/**
		* 
		*/
		this.send = function(controller, action, data, requestMethod, returnType, inSettings) {
			// Add module name to the request parameters
			
			// Requesting CJT Service
			return CJTServer.send(controller, 
																 action, 
																 injectModuleName(data),
																 requestMethod, 
																 returnType, 
																 inSettings);
		};
		
	};
	
})(jQuery);