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
		* put your comment there...
		* 
		*/
		var _onload = function() {
			// Add toolbox button.
			var tbIconsGroup = this.block.box.find('.editor-toolbox .icons-group')
			tbIconsGroup.children().first().after('<a href="#" class="cjt-tb-link cjttbl-preview"></a>');
			this.editorToolbox.add('preview', {callback : this._onpreviewchanges})
			// Set Title.
			.jButton.attr('title', CJT_METABOX_BLOCKJqueryBlockI18N.previewLinkTitle);
			// Register COMMAND-KEYS.
			this.registerMetaboxCommands();
		};

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
		
		/**
		* 
		*/
		this.onLoad = _onload;
		
		// Initialize base class.
		this.initCJTPluginBase(node, args);
			
	} // End class.
	
	// Extend CJTBlockPlugin class.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();
})(jQuery);