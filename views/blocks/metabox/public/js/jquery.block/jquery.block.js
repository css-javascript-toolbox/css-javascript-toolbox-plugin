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
		this._onpreviewchanges = function() {
			// Save changes and preview changes when successed!
			this._onsavechanges().success(
				function() {
					$('#post-preview').click();	
				}
			);
		}
		
		/**
		* 
		*/
		this.registerMetaboxCommands = function() {
			// Preview Changes!
			this.block.aceEditor.commands.addCommand({
				name: 'Preview Changes',
				bindKey: {
					win : 'Ctrl-I',
					mac : 'Command-I'
				},
				exec: $.proxy(this._onpreviewchanges, this)
			});
		}
		
		// Initialize base class.
		this.initCJTPluginBase(node, args);
		// Add toolbox button.
		var tbIconsGroup = this.block.box.find('.editor-toolbox .icons-group')
		tbIconsGroup.children().first().after('<a href="#" class="cjt-tb-link cjttbl-preview"></a>');
		this.editorToolbox.add('preview', {callback : this._onpreviewchanges});
		// Register COMMAND-KEYS.
		this.registerMetaboxCommands();
			
	} // End class.
	
	// Extend CJTBlockPlugin class.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);