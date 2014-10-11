/**
* 
*/

/**
* put your comment there...
* 
* @type T_JS_FUNCTION
*/
(function($) {

	/**
	* 
	*/
	CJTBlockObjectPluginDockModule = new function() {
		
		/**
		* 
		*/
		this.plug = function(block) {
			// Define Dock method
			block.dock = function(elements, pixelsToRemove) {
				// Initialize.
				var alwaysRemove = 33;
				pixelsToRemove = (pixelsToRemove != undefined) ? (pixelsToRemove + alwaysRemove) : alwaysRemove;
				// There're always 33 pixels need to be removed from the Code area
				var fixedHeight = this.block.box.height() - pixelsToRemove;
				var heightInPixels = fixedHeight + 'px';
				$(elements).css('height', heightInPixels);	
			}
		};
		
	};
	
})(jQuery);