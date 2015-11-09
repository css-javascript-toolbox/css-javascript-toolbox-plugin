/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* Override CJTBlockPlugin class.
	* 
	* @param node
	* @param args
	*/
	CJTBlockPlugin = function(node, args) {			
		
		/**
		* 
		*/
		this.pagesPanel = null;
		
		/**
		* put your comment there...
		* 
		*/
		var _onload = function() {
			// Plug the assigment panel, get the jQuery ELement for it
			var assigmentPanelElement = this.block.box.find('#tabs-' + this.block.get('id'));
			this.pagesPanel = assigmentPanelElement.CJTBlockAssignmentPanel({block : this}).get(0).CJTBlockAssignmentPanel;
			// More to Dock with Fullscreen mode!
			this.extraDocks = [
				{element : assigmentPanelElement.find('.ui-tabs-panel'), pixels : 78},
				{element : assigmentPanelElement.find('.ui-tabs-panel .pagelist'), pixels : 132},
				
				{element : assigmentPanelElement.find('.custom-posts-container'), pixels : 124},
				{element : assigmentPanelElement.find('.custom-posts-container .custom-post-list'), pixels : 156},
				{element : assigmentPanelElement.find('.custom-posts-container .custom-post-list .pagelist'), pixels : 178},
				
				{element : assigmentPanelElement.find('.advanced-accordion .ui-accordion-content'), pixels : 172},
				{element : assigmentPanelElement.find('.advanced-accordion .ui-accordion-content textarea'), pixels : 182}
			];
			
			this.block.box.trigger( 'cjtassignableblockloaded', [ this ] );
		};
		
		// Load block only when loaded by parent model.
		this.onLoad = _onload;
	
		/// Initialize parent class.
		// Add assigment panel fields to the restoreRevision args.
		args.restoreRevision = {fields : ['code', 'pages', 'posts', 'categories', 'pinPoint', 'links', 'expressions']};
		
		this.initCJTPluginBase(node, args);
		
	} // End class.
	
	// Extend CJTBLockPluginBase.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);