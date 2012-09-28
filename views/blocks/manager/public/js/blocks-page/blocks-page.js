/**
*
*/
var CJTToolBox = {
	forms : {}
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
		wpPageName : 'cjtoolbox',
		
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
		*
		*
		*
		*
		*/
		_ondeleteall : function() {
			/** @TODO confirm delete. */
			CJTBlocksPage.deleteBlocks(CJTBlocksPage.blocks.getBlocks());
		},
		
		/**
		*
		*
		*
		*
		*/
		_ondeleteempty : function() {
		  /// @TODO confirm delete.
		  var blocks = CJTBlocksPage.blocks.getBlocks();
		  var emptyBlocks = [];
		  // For every block check if there is code content.
		  blocks.each(
		  	function(index, block) {
		  		var code = block.CJTBlock.block.get('code');
		  		if (code == '') {
		  			emptyBlocks.push(this);
		  		}
		  	}
		  );
		  // If all the blocks are deleted call _ondeleteall handler.
		  CJTBlocksPage.deleteBlocks(emptyBlocks);
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
				'position' : 'absolute', 
				'left' : '0px',
				'top' : '4px', 
				'margin-left' : '0px', 
				'margin-top' : '0px',
				'width' : '99%',
				'height' : '95%'
			});
			// Set Iframe style.
			thickboxForm.find('iframe').css({
				width : '100%',
				height : '100%',
			});
			// Put Thickbox form inside wpbody element.
			$('#wpbody').prepend(thickboxForm);
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
				calculatePinPoint : true
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
					postboxes.save_state(CJTBlocksPage.wpPageName);
				}
			)
		},
		
		/**
		*
		*
		*
		*
		*/
		_onswitchflag : function(event, params) {
			var multiOperationServer = CJTBlocksPage.server.multiOperation;
			var eventName = 'switch' + params.flag.ucFirst();
			// First queue blocks flag.
			multiOperationServer.trigger(eventName, params)
			// Second send all queued stack to sgerver.
			.send('post');
		},
		
		/**
		*
		*
		*
		*
		*/
		_ontoggle : function(event, params) {
			// Stop postbox from updating postbox states.
			var saveStateMethod = postboxes.save_state;
			postboxes.save_state = function() {};
			// Show or Hide blocks.
			var blocks = CJTBlocksPage.blocks.getBlocks();
			switch (params.state) {
				case false:
					blocks.addClass('closed');
				break;
				case true:
					blocks.removeClass('closed');
				break;
			}
			// Reset save state method.
			// Note: Its just a reset but not saving, save should happend with save all changes button.
			postboxes.save_state = saveStateMethod;
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
					return null;
				}
			}
			return "Confirm Not Save";
		},
		
		/**
		*
		*
		*
		*/
		addBlock : function(position, content) {
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
			CJTBlocksPage.blocksContainer[positionMethod](content);
			// Note: Only new block will be returned because its the only one with .postbox class.
			var newAddedBlock = CJTBlocksPage.blocks.getBlocks().eq(0);
			// Add block element.
			newAddedBlock.CJTBlock({});
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
			// Refresh sort order/save new block order.
			CJTBlocksPage.saveBlocksOrder();
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
					var blockId = block.CJTBlock.block.get('id');
					CJTBlocksPage.deletedBlocks.push(blockId);
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
			CJTBlocksPage.blocksForm = $('form#cjtoolbox-blocks-page-form');
			CJTBlocksPage.blocksContainer = $('div#normal-sortables');
			CJTBlocksPage.intro = CJTBlocksPage.blocksForm.find('#cjt-noblocks-intro');
			CJTBlocksPage.server = CJTServer;
			CJTBlocksPage.server.multiOperation = new CJTBlocksAjaxMultiOperations('blocksPage', 'save_blocks');
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
					'delete-all' : {callback : CJTBlocksPage._ondeleteall},
					'delete-empty' : {callback : CJTBlocksPage._ondeleteempty},
					'reset-order' : {callback : CJTBlocksPage._onresetorder},
					'manage-backups' : {callback : CJTBlocksPage._onmanagebackups},
					'footer-all' : {callback : CJTBlocksPage._onswitchflag, params : {flag : 'location', newValue : 'footer'}},
					'header-all' : {callback : CJTBlocksPage._onswitchflag, params : {flag : 'location', newValue : 'header'}},
					'activate-all' : {callback : CJTBlocksPage._onswitchflag, params : {flag : 'state', newValue : 'active'}},
					'deactivate-all' : {callback : CJTBlocksPage._onswitchflag, params : {flag : 'state', newValue : 'inactive'}},
					'revert-state' : {callback : CJTBlocksPage._onswitchflag, params : {flag : 'state'}},
					'templates-manager' : {callback : CJTBlocksPage._onmanagetemplates},
					'global-settings' : {callback : CJTBlocksPage._onmanagesettings},
					'close-all' : {callback : CJTBlocksPage._ontoggle, params : {state: false}},
					'open-all' : {callback : CJTBlocksPage._ontoggle, params : {state : true}}
				} 
			});
			// Activate blocks.
			CJTBlocksPage.blocks.getBlocks().CJTBlock({state : CJTBlocksPage.getStateName()});
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
		*
		*
		*
		*
		*/
		saveBlocksOrder : function() {
			// impersonateWPAR allow CJTBlocksAjaxController to be loaded with the request.
			// We need it loaded to update order in one common place.
			// This method may be removed in later versions!!
			CJTServer.impersonateWPAR('blocksPage');
			postboxes.manual_save_order(CJTBlocksPage.wpPageName);
			CJTServer.resetWordpressAjaxURL();
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