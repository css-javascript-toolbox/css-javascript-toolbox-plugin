/**
* 
*/

var ScriptsLoader;
/**
* 
*/
(function($) {
	
	/**
	* 
	*/
	ScriptsLoader = function(scripts) {
		
		/**
		* 
		*/
		callback : null,
		
		/**
		* 
		*/
		this.failures = [];
		
		/**
		* 
		*/
		this.loaded = [];
		
		/**
		* 
		*/
		this.queue = [];
		
		/**
		* 
		*/
		this._fail = function(script, method) {
			// Add script into failure list.
			this.failures.push(script);
			// Notify failure.
			this.callback.rejectWith(this, [method, script]);
		}
		
		/**
		* 
		*/
		this._loaded = function(script) {
			// Add script into loaded list.
			this.loaded.push(script);
			// Wait until all scripts is loaded.
			if (this.loaded.length == scripts.length) {
				// Notify success.
				this.callback.resolveWith(this);
			}
		}
		
		/**
		* 
		*/
		this.loadAll = function() {
			// Use deferred object for notifing caller.
			this.callback = $.Deferred();
			// Load all scripts.
			$.each(scripts, 
				$.proxy(function(index, script) {
					// Get load method name to use for loading script file.
					var loadingMethod = 'load';
					if (script.cjt.loadMethod != undefined) {
						loadingMethod += script.cjt.loadMethod;
					}
					// How many times we tried to load the same script.
					script.retries = (script.retries == undefined) ? 0 : (script.retries + 1);
					this[loadingMethod](script);
					// Push script object into the queue.
					this.queue.push(script);
				}, this)
			);
			return this.callback;
		}
		
		/**
		* 
		*/
		this.load = function(script) {
			// Load script using Ajax!
			$.getScript(script.src).done($.proxy(
				function() {
					this._loaded(script);
				}, this)
			)
			.fail($.proxy( // The state is failure if only one file faild to load.
				function() {
					this._fail(script, 'load');
				}, this)
			);			
		}
		
		/**
		* 
		*/
		this.loadTag = function(script) {
			// Create script tag.
			var scriptTag = document.createElement('script');
			// Set script properties.
			scriptTag.type = 'text/javascript';
			scriptTag.src = script.src;
			// using jQuery for appending Script tag still load it using $.getScript!!
			// Use raw method to add HTML script tag.
			document.getElementsByTagName('head')[0].appendChild(scriptTag);
			// Check if script loaded every 50 milli seconds!
			var timeout = 4000; // 4 seconds.
			var interval = 50; // Milisecs
			var secondsElapsed = 0;
			var checker = setInterval($.proxy(
				function() {
					if (window[script.cjt.lookFor] != undefined) {
						// No more check for this script.
						clearInterval(checker);
						// Notify that script is loaded.
						this._loaded(script);
					}
					else if (secondsElapsed == timeout) {
						// No more check for this script.
						clearInterval(checker);
						// Notify that script load is failure.
						this._fail(script, 'loadTag');
					}
					secondsElapsed += interval;
				}, this)
			, interval);
		}
		
	} // End class.
	
})(jQuery);