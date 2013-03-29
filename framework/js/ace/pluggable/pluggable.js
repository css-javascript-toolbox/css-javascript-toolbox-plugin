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
	var ACEPluggable = {
		
		/**
		* put your comment there...
		* 
		* @type Object
		*/
		plugins : {},
		
		/**
		* Create ACE Plugins system.
		* 
		* @returns void
		*/
		init : function() {
			// Couldnt find ace object!
			if (ace == undefined) {
				throw {code : 0x0001, msg: "Error while initializing ACEPluggabel class. ACE is not defined!!\nPlease check your Javascript loading order"};
			}
			else { // ace found!
				// For now just assign ACEPluggable object reference.
				ace.pluggable = this;
			}
		}
		
	}; // End pluggable prototype.
	
	// Intialize!
	ACEPluggable.init();	
})(jQuery);