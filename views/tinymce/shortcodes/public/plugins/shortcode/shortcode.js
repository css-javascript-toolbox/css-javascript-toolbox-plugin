/**
* 
*/

/**
* TinyMCE Plugin for inserting CJT Block Shortcode
* into Wordpress TinyMCE Editor!
*
*/
(function($) {
	 // === Define statics ===
	 
	/**
	* put your comment there...
	* 
	*/
	var editor = null;

	/**
	* put your comment there...
	* 
	*/
	var pluginURL = null;
	
	// Register Plugin.
	tinymce.create('tinymce.plugins.CJTShortcodes', {
	
	/**
	* put your comment there...
	* 
	*/
	_onselectblock : function() {
		// Read selected block!
		var block = this;
		// Build shortcode!
		var shortcode = '[cjtoolbox name="' + block.name + '"][/cjtoolbox]';
		// Insert shortcode at current cursor position.
		editor.selection.setContent(shortcode);
		editor.focus();
	},
	
	/**
	* put your comment there...
	* 
	* @param n
	* @param cm
	*/
	createControl : function(n, cm) {
		var control = null;
		switch (n) {
			case 'CJTBlockShortcode':
				// Create blocks list!
				var bList = cm.createMenuButton('blocksList', {
					title : CJTBlockShortcode.title,
					image : pluginURL + '/images/blocks.png',
					'class' : 'cjt-blocks-list-tinymce-button'
				});
				// Fill with blocks!
				bList.onRenderMenu.add($.proxy(
					function(c, menu) {
						// Start progress/loading image!
						var tinyMCEButtonImage = $('#content_blocksList img');
						var CJTPluginURI = ajaxurl.replace('wp-admin/admin-ajax.php', 'wp-content/plugins/css-javascript-toolbox');
						var realImage = tinyMCEButtonImage.prop('src');
						tinyMCEButtonImage.prop('src', (CJTPluginURI + '/framework/css/images/loading.gif'))
						.css({width : '16px', height : '16px', 'margin-left' : '10px'});
						// Read blocks from server!
						CJTServer.send('tinymceBlocks', 'getBlocksList')
						.success($.proxy(
							function(blocks) {
								// If not blocks do nothing!
								if (!blocks.count) {
									blocks.list = [];
								}
								// Add blocks as menu item!
								$.each(blocks.list, $.proxy(
									function(id, block) {
										// Bind to select event!
										block.onclick = this._onselectblock;
										// Get a copy of  block original name.
										block.name = block.title;
										// Prepend ID to block title!
										block.title = '#' + id + ': ' + block.title;
										// Add item!
										menu.add(block);
									}, this)
								)
							}, this)
						).complete($.proxy(
							function() {
								// Stop progress/loading image!
								tinyMCEButtonImage.prop('src', realImage)
								.css({width : '', height : '', 'margin-left' : ''});
							}, this)
						);
					}, this)
				);
				// Return list!
				control = bList;
			break;
		}
		return control;
	},
	
	/**
	* put your comment there...
	* 
	* @returns {Object}
	*/
	getInfo : function() {
		return {
			longname : 'CJT Blocks Shortcode',
			author : 'CJT',
			authorurl : 'http://css-javascript-toolbox.com',
			infourl : 'http://css-javascript-toolbox.com/css-javascript-toolbox-v6/',
			version : '1.0'
		};
	},
	
	/**
	* Initializing CJT TinyMCE Plugins.
	* 
	* @param ed
	* @param url
	*/
	init : function(ed, url) {
		// Initialize!
		pluginURL = url;
		editor = ed;
		// Load CSS!
		tinymce.DOM.loadCSS(pluginURL + '/css/shortcode.css');
	}
		
	}); // End TinyMCE Plugin!
	
	// Add Plugin
	tinymce.PluginManager.add('CJTShortcodes', tinymce.plugins.CJTShortcodes);
	
})(jQuery); // End TinyMCE Plugin namespace!