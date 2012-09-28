/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	var modeBase = function() {
		
		/**
		* put your comment there...
		* 
		*/
		this.baseURI = null;
		
		/**
		* 
		*/
		this.listCache = {
			time : null,
			items : []
		};
		
		/**
		* put your comment there...
		* 
		* @type String
		*/
		this.name = null;
		
		/**
		* put your comment there...
		* 
		*/
		this.path = null;
		
	}
	
	/**
	* put your comment there...
	* 
	* @returns {Object}
	*/
	modeBase.prototype.getList = function(refreshCache) {
		var deferred = $.Deferred();
		// List not cached yet. Cache the list.
		if (refreshCache || !this.listCache.time) {
			var name = this.getName();
			var fileName = name + '.xml';
			// Load list.
			var listFileURI = this.baseURI + '/' + name + '/' + fileName;
			// Load xml list.
			$.get(listFileURI, $.proxy(
				function(doc) {
					// Load keywords into Array.
					var keywords = doc.getElementsByTagName('keyword');
					// Clear current list.
					this.listCache.items = [];
					// Use only keyword "name".
					$.each(keywords, $.proxy(
					  function(index, keyword) {
				  		this.listCache.items.push(keyword.getAttribute('name'));
					  }
						, this)
					);
					// Update cache time.
					this.listCache.time = new Date();
					// Callback.
					deferred.resolveWith(this, [this.listCache.items]);
				}
				, this)
			, 'xml');			
		}
		else { // Return cached list.
			deferred.resolveWith(this, [this.listCache.items]);
		}
		return deferred;
	}
	
	/**
	* Abstract!
	* 
	* @returns {Object}
	*/
	modeBase.prototype.getBaseURL = function() {
		return this.baseURI;
	}
	
	/**
	* 
	*/
	modeBase.prototype.getName = function() {
		return this.name;
	}
	
	/**
	* 
	*/
	modeBase.prototype.getPath = function() {
		return this.path;
	}
	
	// Globalize modeBase prototype.
	ace.pluggable.plugins.cac.prototypes.mode = modeBase;
})(jQuery);