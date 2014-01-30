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
		* 
		*/
		this._onswitchstate = function(state) {
			switch (state) {
				case 'restore':
					this.pagesPanel.loadAssignedOnlyMode = true;
				break;
			}
		}
		
		/// Initialize parent class.
		this.initCJTPluginBase(node, {});
		
		// Plug the assigment panel, get the jQuery ELement for it
		var assigmentPanelElement = this.block.box.find('#tabs-' + this.block.get('id'));
		this.pagesPanel = assigmentPanelElement.CJTBlockAssignmentPanel({block : this}).get(0).CJTBlockAssignmentPanel;

	} // End class.
	
	// Extend CJTBLockPluginBase.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);