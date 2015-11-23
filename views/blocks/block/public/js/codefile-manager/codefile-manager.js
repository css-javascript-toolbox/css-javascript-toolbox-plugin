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
	CJTBlockCodeFileView = new function() {
		
		/**
		* 
		*/
		this.block;
		
		/**
		* 
		*/
   	this.filesManager;
		
		/**
		* 
		*/		
		this.quickToolbar;

		/**
		* 
		*/				
    	var _changequicktoolbarstate = function(event) {
			switch (event.type) {
				case 'mouseenter':
					// Initialize.
					var liElement = $(event.currentTarget);
					var toolbarNode = this.quickToolbar.get(0);
					// Associate with Li.
					toolbarNode.codeFileLi = liElement;
					var toolbarNode = this.quickToolbar.get(0);
					// Don' allow active/master selection.
					var link = liElement.find('a');
					toolbarNode.selectCheckbox.prop('disabled', ((link.data('codeFile').id == 1) || link.hasClass('active')));
					// Get Select-Checkbox check state.
					toolbarNode.selectCheckbox.prop('checked', liElement.data('checkbox-selected') ? true : false);
					// Add directly after the current li.
					liElement.after(this.quickToolbar);
					// Show over the current li element.
					this.quickToolbar.show();
				break;
				case 'mouseleave':
					// Hide when mouse leaved.
					this.quickToolbar.hide();
				break;
			}
		};
		
		/**
		*
		*
		*/
		var _onswitchfile = function(event) {
			// Initialize.
			var link = $(event.target);			
			var codeFile = link.data('codeFile');
			// Disable Form.
			var overlay = this.filesManager.find('.overlay').show();
			// Switch.
			this.block.codeFile.switchFile(codeFile).done($.proxy(
				function(blockId, codeFileId) {
					// Process only if it still the current active block
					// otehrwise discard.
					if (blockId == this.block.block.get('id')) {
						// Switch editor language.
						this.block._onswitcheditorlang(undefined, {
							lang :	codeFile.type ? 
									codeFile.type : 
									this.block.block.get('editorLang', 'css')
						});
						// Close.
						this.deattach();
						// Activate new switched code file.
						//===this.filesManager.find('li.codeFile a').removeClass('active');
						//===link.addClass('active');
						// Enable Form
						overlay.hide();
					}
				}, this)
			);
		};

		/**
		*
		*
		*/
		var dialogToolButtons = function() {
			// Initialize Tool Buttons.
			this.dialogToolButtons = this.filesManager.find('.dialog-tool-buttons');	
			// Close Dialog
			this.dialogToolButtons.find('.close').click($.proxy(
				function() {
					// Deattach from current block, free resources.
					this.deattach();
					// Inactive
					return false;
				}, this )
			);
			// Delete Seletced Files.
			this.dialogToolButtons.find('.delete').click($.proxy(
				function() {
					// Get all selected codeFiles ids.
					var ids = [];
					var checkboxes = this.filesManager.find('.code-file-item input[type="checkbox"]');
					// My select at least one code file.
					if (!checkboxes.length) {
						alert(CJTCodefileManagerI18N.noSelection);
					}
					else {
						// Confirm Delete
						if (confirm(CJTCodefileManagerI18N.confirmDelete.replace('{count}', checkboxes.length))) {
							checkboxes.each($.proxy(
								function(index, checkbox) {
									ids.push(checkbox.value);
								}, this)
							);
							// Disable Form.
							var overlay = this.filesManager.find('.overlay').show();
							// Once collected process deletion.
							this.block.codeFile.deleteCodeFile(ids).done($.proxy(
								function(response) {
									// Delete those codeFiles from Files Manager.
									checkboxes.parent().remove();
									// Enable Form.
									overlay.hide();
								}, this)
							);		
						}
					}
					// Inactive
					return false;
				}, this )
			);

		};
		
		/**
		*
		*
		*/		
		var editCodeFile = function() {
			// Edit Code File Dial object/
			this.editCodeFileDialog = new (function(codeFilesView) {
			
				/**
				*
				*
				*/
				var jDialog = codeFilesView.filesManager.find('.edit-code-file');
				
				/**
				*
				*
				*/
				var jOverlay = codeFilesView.filesManager.find('.overlay');
				
				/**
				*
				*
				*/
				var promise;
				
				/**
				* 
				*
				*/
				var _oncancel = function() {
					// Destroy dialog.
					this.close();
				};
				
				/**
				*
				*
				*/
				var _onsave = function() {
					// Initialize vars.
					var fields = ['name', 'description', 'tag', 'type', 'id'];
					var data = {};
					// Collect data.
					$.each(fields, $.proxy(
						function(index, fieldName) {
							data[fieldName] = jDialog.find('#code-file-' + fieldName).val();
						}, this)
					);
					// Validate.
					// Name must be specified.
					if (!data.name) {
						alert(CJTCodefileManagerI18N.nameIsNull);
					}
					else {
						// Initialize as available.
						var isNameExists = false;
						// Name must be unique!
						codeFilesView.filesManager.find('.codeFile a').each($.proxy(
							function(index, link) {
								// Get CodeFile structure associated with the link.
								var codeFile = $(link).data('codeFile');
								// Check the name exists. DONT CHECK WITH SELF!
								if ((codeFile.id != data.id) && (codeFile.name == data.name)) {
									// Mark as not available.
									isNameExists = true;
									// Exists loop.
									return false;
								}
							}, this));
						if (isNameExists) {
							alert(CJTCodefileManagerI18N.nameAlreadyExists);	
						}
						else { // Success
							codeFilesView.block.codeFile.save(data).done($.proxy(
								function(response) {
									// Response to caller (create/edit)
									promise.resolve(response);
								}, this)
							);
						}
					}
				};
				
				/**
				* Display
				*
				*/
				this.close = function() {
					// Hide the form.
					jDialog.hide();
					// Hide Overlay.
					jOverlay.hide();
				};
				
				/**
				* Display
				*
				*/
				this.display = function(li, formData) {
					// Fill the form.
					$.each(formData, $.proxy(
						function(name, value) {
							// Find element corresponding to the field.
							jDialog.find('#code-file-' + name).val(value);
						}, this)
					);
					// Add after current LI.
					li.after(jDialog)
					// Add the overlay immediately before the form.
					.after(jOverlay);
					// Display dialog and overlay.
					jOverlay.show();
					jDialog.show();
					// Return a promise object to be used when form
					//is being saved.
					promise = $.Deferred();
					return promise;
				};
	
				// Prepare dialog.
				jDialog.find('#code-file-save-button').click($.proxy(_onsave, this));
				jDialog.find('#code-file-cancel-button').click($.proxy(_oncancel, this));
				var typesList = jDialog.find('#code-file-type').change($.proxy(
					function() {
						// Tag text.
						var tagInput = jDialog.find('#code-file-tag');
						var tag = '';
						// Based on the selected type specifiy TAG.
						switch (typesList.val()) {
							case 'php':
								tag = '<?php%s?>';
							break;
							case 'javascript':
								tag = '<script type="text/javascript">%s</script>';
							break;
							case 'css':
								tag = '<style type="text/css">%s</style>';
							break;
						}
						// Set.
						tagInput.val(tag);
					}, this)
				);
			})(this);
		};
		
		/**
		*
		*
		*/
		var filesManagerDialog = function() {
			// File Manager.
			this.filesManager = $('#code-files-manager')
			// Prevent Closing Metabox when interacting with the Code Files Manager.
			.click(function(event) {event.stopPropagation()});
		};

		/**
		*
		*
		*/		
		this.applyTheme = function(themeBlock) {
			// Switch only if displayed for the current block that changing the theme.
			if (this.block === themeBlock) {
				this.filesManager.css({
					'background-color': this.block.theme.backgroundColor,
					'color': this.block.theme.color
				});	
			}
		};

		this.listCodeFile = function(codeFile) {
			// Create new LI element for the code file and all the related comopnents.
			var liElement = $('<li>').addClass('codeFile').addClass('code-file-item').appendTo(this.filesManager)
			// Show quick toolbox when Li ELement is hovered.
			.mouseenter($.proxy(_changequicktoolbarstate, this));
			$('<a>').text(codeFile.name)
					.prop('title', codeFile.description)
					.appendTo(liElement)
					// Store Code File Data.
					.data('codeFile', codeFile)
					// Switch when clicked.
					.click($.proxy(_onswitchfile, this));
			return liElement;
		};
		
		/**
		*
		*
		*/
		var quickToolbar = function() {
			// Initialize Quick Toolbar
			this.quickToolbar = this.filesManager.find('.quick-toolbar').detach();
			// Hide Quicktoolbar when mouse leave.
			this.filesManager.mouseleave($.proxy(_changequicktoolbarstate, this));
			// Select File Checkbox.
			this.quickToolbar.get(0).selectCheckbox = this.quickToolbar.find('.select-code-file').change($.proxy(
				function() {
					var isChecked = this.quickToolbar.get(0).selectCheckbox.prop('checked');
					var codeFileLi = this.quickToolbar.prev()
					// Mark current QuickToolbar Code File as Selected.
					.data('checkbox-selected', isChecked);
					// Get CodeFile.
					var codeFile = codeFileLi.find('a').data('codeFile');
					// Add Checkbox for current Code File is chcked.
					// Delete it otherwise.
					switch(isChecked) {
						case true:
							// Add checkbox and mark it checked.
							codeFileLi.data('mySelectCheckbox', $('<input class="show-selection" type="checkbox" checked="checked" />').val(codeFile.id)
							.prop('disabled', 'disabled')
							.prependTo(codeFileLi));
						break;
						case false:
							// Delete chekbox
							var mySelectCheckbox;
							if (mySelectCheckbox = codeFileLi.data('mySelectCheckbox')) {
								mySelectCheckbox.remove();	
							}
						break;
					}
				}, this)
			); 
			// Edit Code File.
			this.quickToolbar.find('a.edit').click($.proxy(
				function(event) {
					// Get Code File data from the Code FileLi the Quicktoolbar is currenty activated for.
					var codeFileLi = this.quickToolbar.get(0).codeFileLi;
					var codeFileLink = codeFileLi.find('a');
					// Display form.
					this.editCodeFileDialog.display($(event.target).parent(), codeFileLink.data('codeFile')).done($.proxy(
						function(rCodeFile) {
							// Update codeFile record cache.
							codeFileLink.data('codeFile', rCodeFile);
							// Edit View Code File.
							codeFileLink.text(rCodeFile.name);
							// If is the active file.
							if (codeFileLink.hasClass('active')) {
								// Change Block Active File Name.
								this.block.block.box.find('.hndle a.file').text(rCodeFile.name);
							}
							// Close the form.
							this.editCodeFileDialog.close();
						}, this)
					);
					return false;
				}, this )
			);	
		};
		
		/**
		*
		*
		*/
		this.deattach = function() {
			// Hide Manager.
			this.filesManager.css({'display' : 'none'}).detach();
			// Clear any previously added list
			this.filesManager.find('li.codeFile').remove();
			// Make sure edit/create form to be destructed.
			this.editCodeFileDialog.close();
			// Hide Quicktoolbar.
			this.quickToolbar.hide();
			// Reset Block
			var oldBlock = this.block;
			this.block = null;
			// Returns Old block
			return oldBlock;
		};

		/**
		* put your comment there...
		* 
		*/
		this.initialize = function() {
			// Files Manager dialog.
			filesManagerDialog.apply(this);
			// Edit Code File Dialog.
			editCodeFile.apply(this);
			// Prepare Quick Toolbar
			quickToolbar.apply(this);
			// Dialog tool buttons
			dialogToolButtons.apply(this);
		};
		
		/**
		* 
		*/
		this.switchTo = function(block) {
			// If not in revision mode submit to server.
			if (block.revisionControl && (block.revisionControl.state == 'revision')) {
				return;
			}
			// Enter Deattached state.
			var oldBlock = this.deattach();
			// Display if not displayed.
			if (oldBlock !== block) {
				// Switch to block.
				this.block = block;
				// Fetch List
				block.codeFile.getList().done($.proxy(
					function(codeFiles) {
						// - Don't process is entered deattach state.
						// - Process only last switched block.
						if (this.block.block.get('id') == codeFiles.blockId) {
							// Append list.
							$.each(codeFiles.list, $.proxy(
								function(index, codeFile) {
									// List codeFile.
									var codeFileLink = this.listCodeFile(codeFile).find('a');
									// Check if current codeFile.
									if (this.block.codeFile.file.activeFileId == codeFile.id) {
										codeFileLink.addClass('active');
									}
								}, this)
							);
							// Set Location.
							this.block.block.box.find('.file').after(this.filesManager);
							// Aply theme
							this.applyTheme(this.block);
							// Display
							this.filesManager.css({'display' : 'block'});
						}
					}, this)
				);
			}
		};
	};
	
})(jQuery)