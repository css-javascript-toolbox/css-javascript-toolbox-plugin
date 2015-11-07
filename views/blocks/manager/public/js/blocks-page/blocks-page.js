/**
*
*/
var CJTToolBox = {
	forms : {templatesLookupForm : []}
}; // Application name spaces structure.

/*
*
*
*/
var CJTBlocksPage;

/*
*
*
*/
(function($){

	/*
	*
	*
	*/
	CJTBlocksPage = {

		/*
		*
		*
		*
		*/
		blocksContainer : null,
				
		/*
		*
		*
		*
		*/
		blocksForm : null,
		
		/*
		*
		*
		*
		*/
		blocks : null,

		/**
		*
		*
		*
		*
		*
		*/
		changes : [],
		
		/*
		*
		*
		*
		*/		
		deletedBlocks : [],
		
		/*
		*
		*
		*
		*/
		loadingElement : null,
		
		/*
		*
		*
		*
		*/		
		loadingImage : null,

		/**
		*
		*
		*
		*/
		pageId : 'cjtoolbox',
		
		/**
		*
		*
		*
		*/
		server : null,
		
		/**
		*
		*
		*
		*/
		toolboxes : {
		
			/**
			*
			*
			*
			*/
			toolboxes : null,
			
			/*
			*
			*
			*
			*/
			css : function(role, value) {
				this.toolboxes.each(
					function() {
						$(this).css(role, value);
					}
				);
			},
			
			/**
			* put your comment there...
			* 
			*/
			getIButton : function(buttonName, method, params) {
				var dispatcher = {
					/**
					* put your comment there...
					* 
					*/
					dispatch : function(params) {
						var result = {};
						CJTBlocksPage.toolboxes.toolboxes.each(
							function(index) {
								// Get button object.
								var button = this.CJTToolBox.buttons[buttonName];
								// Dispatch button method.
								result[index] = button[method].apply(button, params);
							}
						);
						return result;
					}
				};
				// When created and params is passed vall dispatch.
				if (params != undefined) {
					dispatcher.dispatch(params);
				}
				// Return dispatched handle object.
				return dispatcher;
			},
			
			/**
			*
			*
			*
			*/
			enable : function(buttonName, enable) {
				this.toolboxes.each(
					function() {
						var button = this.CJTToolBox.buttons[buttonName];
						button.enable(enable);
					}
				);
			},
			
			/**
			*
			*
			*
			*/
			isEnabled : function(buttonName) {
				var isEnabled = true;
				this.toolboxes.each(
					function() {
						var button = this.CJTToolBox.buttons[buttonName];
						isEnabled &= button.isEnabled();
					}
				);
				return isEnabled;
			},
			
			/*
			*
			*
			*
			*/
			switchState : function(state) {
				var buttonsToHide;
				switch (state) {
					case 'restore':
						// Hide Add New Block, Save all changes, location tools and state tools buttons.
						buttonsToHide = ['save-changes', 'add-block', 'state-tools', 'location-tools'];
					break;
					default:
					  // Hide Restore & Cancel Restore buttons.
					  buttonsToHide = ['restore', 'cancel-restore'];
					break;
				}
				// Hide buttons.
				this.toolboxes.each(
					// Get all toolboxes.
					function(tbIndex, toolbox) {
						// Hide all buttons for each toolbox.
						$.each(buttonsToHide,
							function(btnIndex, buttonName) {
								var button = toolbox.CJTToolBox.buttons[buttonName];
								button.jButton.css('display', 'none');
							}
						);
					}
				);
				// Display Toolboxes.
				this.css('visibility', 'visible');
			}
		},
				
		/**
		*
		*
		*
		*/
		_onaddnew : function(event, params) {
			// Server request.
			var requestData = {
				// Server paramerers.
				viewName : 'blocks/new',
				// Thick box parameters.
				width : 450,
				height: 230,
				position : params.toolbox.position,
				TB_iframe : true // Must be last one Thickbox removes this and later params.
			};
			var url = CJTBlocksPage.server.getRequestURL('blocksPage', 'get_view', requestData);
			// Popup form.
			tb_show(CJTBlocksPageI18N.addNewBlockDialogTitle, url);
		},
		
		/**
		* put your comment there...
		* 
		*/
		_oncancelrestore : function() {
			window.location.href = window.location.href.replace(/&backupId=\d+/, '');
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onmanagesettings : function() {
			var params = {width: 700, height: 600,TB_iframe : true};
			var settingsFormURL = CJTBlocksPage.server.getRequestURL('settings', 'manageForm', params);
			tb_show(CJTBlocksPageI18N.settingsFormTitle, settingsFormURL);
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onmanagetemplates : function() {
			var params = {width: '100%', height: '100%', TB_iframe : true};
			var url = CJTBlocksPage.server.getRequestURL('templatesManager', 'display', params);
			tb_show(CJTBlocksPageI18N.manageTemplatesFormTitle, url);
			// Get thickbox form element.
			var thickboxForm = $('#TB_window');
			// Style thickbox.
			thickboxForm.css({
				'position' : 'fixed', 
				'left' : '0px',
				'top' : '4px', 
				'margin-left' : '5px',
				'margin-top': '0px',
				'width' : '99%',
				'height' : ((jQuery(window).height() - 40) + 'px'),
				'z-index' : 1000000
			});
			// Set Iframe style.
			thickboxForm.find('iframe').css({
				width : '100%',
				height : '100%'
			});
		},
		
		/**
		*
		*
		*
		*
		*/
		_onmanagebackups : function() {
			// Server request.
			var requestData = {
				// Thick box parameters.
				width : 480,
				height: 400,
				TB_iframe : true // Must be last one Thickbox removes this and later params.
			};
			var url = CJTBlocksPage.server.getRequestURL('blocksBackups', 'list', requestData);
			tb_show(CJTBlocksPageI18N.manageBackupsDialogTitle, url);
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onrestore : function() {
			// Confirm restore.
			var doRestore = confirm(CJTBlocksPageI18N.confirmRestoreBlocks);
			if (doRestore) {
				// Disable restore button for all toolboxes.
				var ibCancelRestore = CJTBlocksPage.toolboxes.getIButton('cancel-restore', 'enable', [false]);
				// SHow loading for restore buttons.
				var ibRestoreLoader = CJTBlocksPage.toolboxes.getIButton('restore', 'loading', [true, false]);
				// Send request to server.
				var requestData = {backupId : CJTBlocksPage.isRestore()};
				CJTBlocksPage.server.send('blocksBackups', 'restore', requestData)
				.success(
					function() {
						// Refresh the page without backupId parameter.
						CJTBlocksPage._oncancelrestore();
					}
				)
				.error(
					function() {
						// Notify user error.
						alert(CJTBlocksPageI18N.unableToRestoreBackup);
						// Stop loading progress.
						ibCancelRestore.dispatch([true]);
						ibRestoreLoader.dispatch([false, true]);
					}
				);
			}
		},
		
		/**
		*
		*
		*
		*
		*/
		_onsavechanges : function() {
			var multiOperationServer = CJTBlocksPage.server.multiOperation;
			var data = {
				deletedBlocks : CJTBlocksPage.deletedBlocks,
				calculatePinPoint : 1,
				createRevision : 1
			};
			// Save block data.
			multiOperationServer.trigger('save').send('post', data).success(
				function() {
					// Reset deleted blocks array.
					CJTBlocksPage.deletedBlocks = [];
					CJTBlocksPage.changes = [];
					CJTBlocksPage.toolboxes.enable('save-changes', false);
					// Save blocks order.
					CJTBlocksPage.saveBlocksOrder();
					// Save closed postboxes.
					postboxes.save_state(CJTBlocksPage.pageId);
				}
			)
		},
		
		/**
		* put your comment there...
		* 
		*/
		_onupdateorder : function() {
			var container = CJTBlocksPage.blocksContainer;
			var orders = container.data('cjtOrders');
			var newOrders = container.sortable('toArray');
			var isChanged = (orders.join('') != newOrders.join(''));
			// Notify changes!
			CJTBlocksPage.blockContentChanged(0, isChanged);
		},
		
		/**
		*
		*
		*
		*
		*
		*/
		_onunload : function() {
			// If there is any deleted blocks or any block changed
			// notify user that there is a change need to be saved;
			if (!CJTBlocksPage.deletedBlocks.length) {
				// If there is no changes in any block save-changed button shouild be disabled.
				if (!CJTBlocksPage.toolboxes.isEnabled('save-changes')) {
					return;
				}
			}
			return CJTBlocksPageI18N.confirmNotSavedChanges;
		},
		
		/**
		*
		*
		*
		*/
		addBlock : function(position, content) {
			var sortable = CJTBlocksPage.blocksContainer;
			// New Block positions to jQuery methods mapping.
			var positions = {top : 'prepend', bottom : 'append'};
			var positionMethod = positions[position];
			// Apply toggling: The only way to apply postboxes it via postboxes.add_postbox_toggles method.
			// Method finding all .postbox elements and bind to click.
			// Exists .postbox(s) will bind to click event twice and the result is
			// not toggling. We need to remove .postbox of exists and apply only
			// to the newly added one.
			var currentBlocks = CJTBlocksPage.blocks.getBlocks();
			currentBlocks.removeClass('postbox').addClass('applying-postbox-to-new-block');
			// Add block to the selected position.
			sortable[positionMethod](content);
			// Note: Only new block will be returned because its the only one with .postbox class.
			var newAddedBlock = CJTBlocksPage.blocks.getBlocks().eq(0);
			// Add block element.
			var blockId =newAddedBlock.CJTBlock({}).get(0).CJTBlock.block.get('id');
			// SET ORDER: Add the new block as first or last Block without saving the unsave orders! //
			// JUST USE THE CURRENT HASHED (SAVED ON SERVER) + ADDING THE NEW BLOCK!
			var newBlockOrderName = CJTBlocksPage.blocks.getSortableName(blockId);
			var order = $.merge([], sortable.data('cjtOrders'));
			(position == 'top') ? order.unshift(newBlockOrderName) : order.push(newBlockOrderName);
			CJTBlocksPage.saveCustomOrder(order);
			// If this is the first block hide the intro and show normal sortable.
			if (!CJTBlocksPage.blocks.hasBlocks()) {
				// Remove intro text.
				CJTBlocksPage.intro.css({display : 'none'});
				CJTBlocksPage.blocksContainer.css({display : 'block'});
				// Set has block value.
				CJTBlocksPage.blocks.hasBlocks(true);
			}
			// Refresh toggling.
			postboxes.add_postbox_toggles('cjtoolbox');
			currentBlocks.removeClass('applying-postbox-to-new-block').addClass('postbox');
			// Put new block into focus.
			newAddedBlock.get(0).CJTBlock.focus();
			return newAddedBlock;
		},

		/**
		*
		*
		*
		*
		*
		*/
		blockContentChanged : function(id, isChanged) {
			var enable = CJTBlocksPage.blocks.calculateChanges(CJTBlocksPage.changes, id, isChanged);
			// Enable/Disable save button in all Toolboxes.
			CJTBlocksPage.toolboxes.enable('save-changes', enable);
		},
		
		/**
		*
		*
		*
		*/
		deleteBlocks : function(blocks) {
			// Delete block.
			$.each(blocks,
				function(index, block) {
					var blockPlg = block.CJTBlock;
					var blockId = blockPlg.block.get('id');
					CJTBlocksPage.deletedBlocks.push(blockId);
					// Notify Menu and Code Files Manager.
					// Only for Loaded Blocks.
					blockPlg.menu && blockPlg.menu.ondeleteblock();
					blockPlg.codeFile && blockPlg.codeFile.ondeleteblock();	
					$(block).remove();
					// Notify save change.
					CJTBlocksPage.blockContentChanged(blockId, true);
				}
			);
			// If last block call _ondeleteall.
			var existsBlocks = CJTBlocksPage.blocks.getBlocks();
			if (existsBlocks.length == 0) {
				CJTBlocksPage.blocksContainer.css('display', 'none');
				CJTBlocksPage.intro.css('display', 'block');
				// Mark as has no blocks.
				CJTBlocksPage.blocks.hasBlocks(false);
			}
		},
		
		/*
		*
		*
		*
		*/		
		getStateName : function() {
			var stateName = this.isRestore() ? 'restore' : '';
			return stateName;
		},
		
		/*
		*
		*
		*
		*/
		init : function() {
			// Initialize object vars.
			CJTBlocksPage.blocksForm = $('#cjtoolbox-blocks-page-form');
			// Prevent form submission, ALL is done through AJAX.
			// Pressing Enter in text fields might caused the whole page to be refreshed.
			CJTBlocksPage.blocksForm.get(0).onsubmit = function() {return false;}
			CJTBlocksPage.blocksContainer = $('div#normal-sortables');
			CJTBlocksPage.intro = CJTBlocksPage.blocksForm.find('#cjt-noblocks-intro');
			CJTBlocksPage.server = CJTServer;
			CJTBlocksPage.server.multiOperation = new CJTBlocksAjaxMultiOperations('blocksPage', 'save_blocks');
			// Initilize Global-Blocks Conponents.
			CJTBlockCodeFileView.initialize();
			// Make sure CJTBlocks is ready.
			CJTBlocksPage.blocks = new CJTBlocks();
			// Initialize Toolboxes.
			CJTBlocksPage.toolboxes.toolboxes = CJTBlocksPage.blocksForm.find('.cjt-toolbox-blocks').CJTToolBox({
				handlers : {
					'state-tools' : {
						type : 'Popup',
						params : {_type : {targetElement : '.state-tools'}}
					},
					'location-tools' : {
						type : 'Popup',
						params : {_type : {targetElement : '.location-tools'}}
					},
					'admin-tools' : {
						type : 'Popup',
						params : {_type : {targetElement : '.admin-tools'}}
					},
					'save-changes' : {callback : CJTBlocksPage._onsavechanges, params : {enable : false}},
					'add-block' : {callback : CJTBlocksPage._onaddnew},
					'restore' : {callback : CJTBlocksPage._onrestore},
					'cancel-restore' : {callback : CJTBlocksPage._oncancelrestore},
					'reset-order' : {callback : CJTBlocksPage._onresetorder},
					'manage-backups' : {callback : CJTBlocksPage._onmanagebackups},
					'templates-manager' : {callback : CJTBlocksPage._onmanagetemplates},
					'global-settings' : {callback : CJTBlocksPage._onmanagesettings},
				} 
			});
			
			$( document ).trigger( 'cjtmanagertoolboxloaded', [ CJTBlocksPage ] );
			
			var jBlocks = CJTBlocksPage.blocks.getBlocks();
			
			$( document ).trigger( 'cjtmanagerpreloadblocks', [ CJTBlocksPage.blocksForm, jBlocks ] );
			
			// Activate blocks.
			jBlocks.CJTBlock({state : CJTBlocksPage.getStateName()});
			
			// Hide loading image. #cjt-blocks-loader will be used for other loading later.
			CJTBlocksPage.loadingImage = CJTBlocksPage.blocksForm.find('#cjt-blocks-loader');
			CJTBlocksPage.loadingImage.find('.loading-text').remove();
			CJTBlocksPage.loadingImage.css({
				display : 'none',
				position : 'absolute'
			});
			// If has no blocks hide normal sortable (it takes 50px as min-height).
			if (!CJTBlocksPage.blocks.hasBlocks()) {
				CJTBlocksPage.blocksContainer.css({display : 'none'});
			}
			// Show blocks.
			CJTBlocksPage.blocksForm.find('#post-body').css('display', 'block');
			//// Setup postboxes ////
			postboxes.add_postbox_toggles('cjtoolbox');
			// Cache blocks order to detect order change!
			CJTBlocksPage.blocksContainer.data('cjtOrders', CJTBlocksPage.blocksContainer.sortable('toArray'));
			// Detect order change.
			CJTBlocksPage.blocksContainer.sortable('option', {update: CJTBlocksPage._onupdateorder});				
			// Stop auto save order, orders should be saved only with "Save All Changes" button.
			// Move it to another method that allow us to save order later manually.
			postboxes.manual_save_order = postboxes.save_order;
			postboxes.save_order = function() {};
			// Notify block when postbox get opened.
			postboxes.pbshow = function(id) {
				$('#' + id).get(0).CJTBlock._onpostboxopened();
			};
			// When navigating away notify saving changes.
			window.onbeforeunload = CJTBlocksPage._onunload;
			// Switch blocks page state.
			CJTBlocksPage.switchState(CJTBlocksPage.getStateName());
		},

		/*
		*
		*
		*
		*/		
		isRestore : function() {
			// If there is backupId parameter then this is a restore state.
			var regEx = /backupId=(\d+)/;
			var backupId = false;
			if (regEx.test(window.location.href)) {
				backupId = parseInt(window.location.href.match(regEx)[1]);
			}
			return backupId;
		},
		
		/*
		*
		*
		*
		*/
		main : function()	{
			// Add indexOf function for IE7.
			if (Array.indexOf == undefined) {
				Array.prototype.indexOf = function(value) {
					var index = -1;
					var array = this;
					jQuery.each(array, function(sIndex, sValue) {
						if (sValue == value) {
							index = sIndex;
						  return;
						}
					});
					return index;
				}
			}
			if (String.ucFirst == undefined) {
				String.prototype.ucFirst = function() {
					var newString = [];
					var words = this.split(' ');
					$(words).each(
						function(index, word) {
							newString.push(word.substr(0, 1).toUpperCase() + word.substr(1));
						}
					)
					newString = newString.join(' ');
					return newString;
				}
			}
			// Initialize CJTBlocks page vars and ui.
			jQuery(document).ready(CJTBlocksPage.init);
		},
		
		/**
		* 
		*/
		saveCustomOrder : function(order) {
			// Override jquery sortable plugin!!
			// We  need to handle toArray method when called by postboxes.save_order method!
			 var originalSortable = $.fn.sortable;
			 $.fn.sortable = function(method) {
				 if (method != 'toArray') {
					 throw 'Dummy CJT::jquery.sortable Plugin: only toArray method is supported!';
				 }
				 // Return the passed order instead of the real order!
				 return order;
			 }
			 // Save order.
			 CJTBlocksPage.saveBlocksOrder();
			 // Reset original sortable!
			 $.fn.sortable = originalSortable;
		},
		
		/**
		*
		*
		*
		*
		*
		*/
		saveBlocksOrder : function() {
			var ordersArray = CJTBlocksPage.blocksContainer.sortable('toArray');
			var request = {order : ordersArray.join(',')};
			// Save Blocks order!
			CJTBlocksPage.server.send('blocksPage', 'saveOrder', request).success($.proxy(
				function() {
					// Refresh local cache order!
					CJTBlocksPage.blocksContainer.data('cjtOrders', ordersArray);
				}, this)
			);
		},
		
		/*
		*
		*
		*
		*/
		switchState : function(state) {
			// For now only toolboxes need to switch state.
			CJTBlocksPage.toolboxes.switchState(state);
		}
		
	} // End class.
	
	// This is the entry point to all Javascript codes.
	CJTBlocksPage.main();
	
})(jQuery); // Dont wait for document to be loaded.