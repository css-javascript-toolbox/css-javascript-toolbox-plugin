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
	var defaultConfig = {
		loading : true, 
		cssClass : 'link-loading'
	};
	
	/**
	* put your comment there...
	* 
	* @param 
	*/
	$.fn.CJTLoading = function(params) {
		// Implement jQuery Chain.
		return this.each(
			function() {
				// If the Plugin is not yet enabled on the current link, enable it.
				if (this.CJTLoader == undefined) {
					
					/**
					* 
					*/
					this.CJTLoader = {

						/**
						* put your comment there...
						* 
						* @type Object
						*/
						config : {},
						
						/**
						* put your comment there...
						* 
						* @type DOMElement
						*/
						jNode : $(this),
						
						/**
						* Hold parameters about the current in loading
						* progress object (e.g ubinded event handlers, etc...).
						* 
						* @type Object
						*/
						stack : {},
						
						/**
						* put your comment there...
						* 
						*/
						changeState : function() {
							// Get reference to HTML node for the link element.
							var node = this.jNode.get(0);
							var stack = this.stack;
							// Change configs.
							this.setConfig();
							// Enable loading.
							if (this.config.loading) {
								// Store link text & Remove link text.
								stack.originalWidth = this.jNode.css('width');
								stack.text = this.jNode.text();
								// Clear link text.
								this.jNode.text('');
								// Reset to the original width.
								this.jNode.css({width : stack.originalWidth})
								// Add loading class.
								this.jNode.addClass(this.config.cssClass);
								// Make link inactive by deattaching original handler (defined by the caller),
								// plus prevent default behavior!
								this.jNode.unbind('click', this.config.ceHandler);
								this.jNode.bind('click.CJTLoading', function(event) {
										event.preventDefault();
									}
								);
							}
							else { // Disable loading and destroy object.
								// Remove loading class.
								this.JNode.removeClass(this.config.cssClass);
								// Set text back.
								this.jNode.text(stack.text);
								// Remove custom width.
								this.jNode.css({width : ''});
								// Bind original handler back to the click event!
								this.jNode.bind('click', this.config.ceHandler)
								// Unbind prevent default handler defined by this class
								.unbind('click.CJTLoading');
								// Destroy jQuery Plugin for that object.
								node.CJTLoader = undefined;
							}
						},
						
						/**
						* 
						*/
						setConfig : function() {
							this.config = $.extend(defaultConfig, params);
						}
						
					};
				}
				// Initialize Plugin for first time.
				this.CJTLoader.changeState();
			}
		)
	} // End JPlugin.
	
})(jQuery);