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
		 // Clone scripts array.
		 scripts = $.merge([], scripts);
		 
		/**
		* 
		*/
		this.callback = $.Deferred();
		
		/**
		* 
		*/
		this.loaded = [];
		
		/**
		* 
		*/
		this._loaded = function(script) {
			// Add script into loaded list.
			this.loaded.push(script);
			// Wait until all scripts is loaded.
			if (this.loaded.length == scripts.length) {
				console.log('All is done');
				// Notify success.
				this.callback.resolveWith(this);
			}
		}
		
		/**
		* 
		*/
		this.loadAll = function() {
			this.loadNext();
			return this.callback;
		}
		
		/**
		* 
		*/
		this.load = function(script) {
			// Load script using Ajax!
			return $.getScript(script.src);
		}
		
		/**
		* 
		*/
		this.loadNext = function() {
			// If there is more script to load.
			if (scripts.length) {
				// Current Script!
				var script = scripts.shift();
				// Get load method name to use for loading script file.
				var loadingMethod = 'load';
				if (script.cjt.loadMethod != undefined) {
					loadingMethod += script.cjt.loadMethod;
				}
				this[loadingMethod](script).done($.proxy(
					function() {
						// Push script object into the queue.
						this.loaded.push(script);
						// Load next script!
						this.loadNext();
					}, this)
				)
				.fail($.proxy(
					function() {
						// Report failure!
						this.callback.rejectWith(this, [loadingMethod, script]);
					}, this)
				)
			}
			else {
				// All scripts has been loaded!!
				this.callback.resolveWith(this);
			}
		}
		
		/**
		* 
		*/
		this.loadTag = function(script) {
			var deferred = $.Deferred();
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
						// Report success!
						deferred.resolve();
					}
					else if (secondsElapsed == timeout) {
						// No more check for this script.
						clearInterval(checker);
						// Notify that script load is failure.
						deferred.reject();
					}
					secondsElapsed += interval;
				}, this)
			, interval);
			// Promising!
			return deferred;
		}
		
	} // End class.
	
})(jQuery);