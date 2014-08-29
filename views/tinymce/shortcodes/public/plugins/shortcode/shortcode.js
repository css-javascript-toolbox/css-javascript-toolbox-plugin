/**
* 
*/

if (CJT === undefined) {
	var CJT = {};
}

/**
* TinyMCE Plugin for inserting CJT Block Shortcode
* into Wordpress TinyMCE Editor!
*
*/
(function($) {
	
	// Create Plugin.
	tinyMCE.PluginManager.add('CJTShortcodes', function(editor, url) {
	
	// Load CSS!
	tinymce.DOM.loadCSS(url + '/css/shortcode.css');
	
	// Cache Editor
	CJT.codeEditor = editor;

	/**
	* put your comment there...
	* 
	*/
	var _onselectblock = function() {
		// Read selected block!
		var block = this;
		var requestStruct = {blockId : block._id};
		// Request Shortcode from server!
		CJTServer.send('tinymceBlocks', 'getShortcode', requestStruct)
		.success($.proxy(
			function(response) {
					switch (response.state) {
						case 'shortcode-notation':
							// Insert shortcode at current cursor position.
							editor.selection.setContent(response.content);
							editor.focus();
						break;
						case 'show-form':
							// Show in IFRAME window!
							requestStruct.width = 700;
							requestStruct.height = 600;
							requestStruct.TB_iframe = true;
							// @TODO: Localize Form Title!
							tb_show(block.formTitle, CJTServer.getRequestURL('tinymceBlocks', 'getBlockParametersForm', requestStruct))
						break;
					}
			}, this)
		);
	};
	
	// Create Shortcodes list button
	editor.addButton('CJTBlockShortcode', {
		type : 'menubutton',
		title : CJTBlockShortcode.title,
		icon : 'cjt-blocks-list-tinymce-button',
		menu : {style : 'overflow:true;max-height:356px;width:280px'},
		onCreateMenu : function() {
			// Get Menu Object created by tinyMCE behind the sense.
			var menu = editor.controlManager.buttons.CJTBlockShortcode.menu;
			// Start progress/loading image!
			var tinyMCEButton = $('.mce-i-cjt-blocks-list-tinymce-button')
			.addClass('cjt-loading');
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
							block.onclick = _onselectblock;
							// Get a copy of  block original name.
							block.name = block.title;
							// Prepend ID to block title!
							block.text = '#' + id + ': ' + block.title;
							// Add item!
							menu.add(block);
						}, this)
					)
				}, this)
			).complete($.proxy(
				function() {
					// Refresh / Render new added items.
					menu.renderNew();
					// Stop progress/loading image!
					tinyMCEButton.removeClass('cjt-loading');
				}, this)
			);
		}
	});

}); // End TinyMCE Plugin!
	
})(jQuery); // End TinyMCE Plugin namespace!