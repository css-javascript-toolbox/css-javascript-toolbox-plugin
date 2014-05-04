/**
* 
* 
* 
*/

(function($) {
	
	/**
	* 
	* 
	* 
	*/
	CJTBlockMenu = function(block) {
		
		/**
		* 
		*/
    	this.block = block;

		/**
		* put your comment there...
		* 
		*/
		var _onclicked = function() {
			// Initialize.
			var blockBox = this.block.block.box;
			// Switch only if not closed.
			if (!blockBox.hasClass('closed')) {
				CJTBlockMenuView.switchTo(blockBox.get(0));
			}
		};
		
		this.ondeleteblock = function() {
			// Don't delete MENU if it being appended under current block.
			if (CJTBlockMenuView.block === this.block) {
				CJTBlockMenuView.deattach();
			}
		};
		
		/**
		* File Menu
		*/
		this.file = new (function(menu) {
			
			/**
			*
			*
			*/
			this.delete = function() {
				// Delete Block.
				block._ondelete();
			};

			/**
			* 
			*/
			this.load = function(method) {
				// Initialize.
				var model = menu.block.block;
				var aceEditor = model.aceEditor;
				var editSession = aceEditor.getSession();
				// Process related file actions.
				switch (method) {
					case 'file': // Load file into block.
						// Find popup element and clean it up.
						var popup = $('#cjt-inline-popup').empty();
						var file = $('<input type="file" id="block-file" />').appendTo(popup).change($.proxy(
							function() {
								if (window.FileReader) {
									var reader = new window.FileReader();
									var loaded = $.proxy(function(event) {
										// Set value.
										aceEditor.setValuePossibleUndo(event.target.result);
										// Close popup.
										tb_remove();
									}, this);
									reader.addEventListener('loadend', loaded);
									reader.addEventListener('load', loaded);
									// Read file x
									reader.readAsText(file.get(0).files[0]);
								}
							}, this)
						);
						tb_show('Load Block From File', '?TB_inline&width=300&height=40&inlineId=cjt-inline-popup');
					break;
					case 'url':
						var inUrl = prompt('Please enter Url to load code from');
						if (inUrl) {
							CJTBlocksPage.server.send('block', 'loadUrl', {url : inUrl}).done($.proxy(
								function(url) {
									aceEditor.setValuePossibleUndo(url.content);
								}, this)
							);
						}
					break;
					default: // Load from server
						var requestData = {
							blockId : model.get('id'),
							fileId : menu.block.codeFile.file.activeFileId,
							returnAs : 'json'
						};
						CJTBlocksPage.server.send('block', 'downloadCodeFile', requestData)
						.success($.proxy(
							function(code) {
								// Set value.
								aceEditor.setValuePossibleUndo(code);
							}, this)
						);
					break;
				}
			}

			/**
			* 
			*/
			this.save = function(method) {
				// initialize.
				var block = menu.block;
				// Process different save actions.
				switch (method) {
					case 'file': // Download Code File from server.
						var model = block.block;
						var requestData = {
							blockId : model.get('id'), 
							fileId : block.codeFile.file.activeFileId,
							returnAs : 'file'
						};
						// Get Download URL.
						var codeFileURL = CJTBlocksPage.server.getRequestURL('block', 'downloadCodeFile', requestData);
						// Download via new Window.
						window.open(codeFileURL);
					break;
					default: // Save block
						block._onsavechanges();
					break;
				}
			};

		})(this);
	
		/**
		* 
		*/
		this.edit = new (function(menu) {
			
			/**
			* Directly execute ACE Command
			*/
			this.ace = function(command) {
				// Ace Editor.
				var model = menu.block.block;
				var aceEditor = model.aceEditor;
				// Exec command.
				aceEditor.execCommand(command);
				// Save ACE Settings
				switch (command) {
					case 'showSettingsMenu':
						$('#ace_settingsmenu').parent().parent().click($.proxy(
							function(event) {
								if (event.shiftKey) { // Save with Shift!
									model.set('aceEditorMenuSettings', aceEditor.getOptions());
								}
							}, this)
						);
					break;
				}
			}
				
		})(this);

		/**
		* 
		*/
		this.view = new (function(menu) {
			
			/**
			* 
			*/
			this.statusVisible = false;
			
			/**
			* Directly execute ACE Command
			*/
			this.statusBar = function() {
				if (!this.statusVisible) {
					ace.config.loadModule(['ext', 'statusbar'], $.proxy(
						function() {
							var StatusBar = ace.require('ace/ext/statusbar').StatusBar;
							var statusBar = new StatusBar(menu.block.block.aceEditor, menu.block.block.box.find('.inside').get(0));
						}, this)
					);
					this.statusVisible = true;
				}
			}
				
		})(this);
		
		// Switch Menu item when block is clicked.
		this.block.block.box.click($.proxy(_onclicked, this));
	};
	
})(jQuery)