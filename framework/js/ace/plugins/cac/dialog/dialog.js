/* ***** BEGIN LICENSE BLOCK *****
 * Version: MPL 1.1/GPL 2.0/LGPL 2.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 *
 * Alternatively, the contents of this file may be used under the terms of
 * either the GNU General Public License Version 2 or later (the "GPL"), or
 * the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
 * in which case the provisions of the GPL or the LGPL are applicable instead
 * of those above. If you wish to allow use of your version of this file only
 * under the terms of either the GPL or the LGPL, and not to allow others to
 * use your version of this file under the terms of the MPL, indicate your
 * decision by deleting the provisions above and replace them with the notice
 * and other provisions required by the GPL or the LGPL. If you do not delete
 * the provisions above, a recipient may use your version of this file under
 * the terms of any one of the MPL, the GPL or the LGPL.
 *
 * ***** END LICENSE BLOCK ***** */

/**
* jQuery wrapper for using $.
*/
(function($) {
	
	/**
	* Dialogs default configuration.
	* 
	* @type Object
	*/
	var defaultConfiguration = {
		codeListClassName : '.code-list',
		itemsPerPage : 10
	};
	
	/**
	* Code Auto Complete Dialog class.
	* 
	* Control CAC-Dialog functionality.
	* 
	* @author Ahmed Said
	*/
	ace.pluggable.plugins.cac.prototypes.dialog = function(editor, parser, configuration) {
		
		// Set default configuration.
		configuration = $.extend(true, {}, defaultConfiguration, configuration);
		
		/**
		* Dialog HTML DOM element.
		* 
		* @type DOMElement
		*/
		this.dialog = $(configuration.element);
		
		/**
		* Reference to Select HTML Element
		* that holds the auto completion list.
		* 
		* @type HTLMDOMSelect
		*/
		this.list = this.dialog.find(configuration.codeListClassName).get(0);
		
		/**
		* 
		*/
		this.itemsCache = null;
		
		/**
		* Display auto complete list.
		* 
		* Method job is to filter the cached list
		* based on current filter text.
		* 
		* @returns Boolean TRUE if the list has items or FALSE otherwise.
		*/
		this.autoCompleteList = function() {
			var listElement = $(this.list);
			// Get filter text.
			var text = this.internal.getFilterText();
			// Clear current list.
			listElement.children().remove();
			// Regular expression used for filtering list.
			var expr = new RegExp('^' + text);
			// filter list.
			$.each(this.itemsCache, $.proxy(
				function(index, item) {
					if (expr.test(item)) {
						// Create list option item to add to the list.
						var itemOption = '<option value="' + item + '">' + item + '</option>';
						// Add item to list.
						listElement.append(itemOption);
					}
				}
			, this));
			// Always select the first item.
			this.list.selectedIndex = 0;
			// If there is items return TRUE otherwise return FALSE.
			return listElement.children().length ? true : false;
		}
		
		/**
		* Cache auto complete list code.
		* 
		* TODO: This method should cache the auto complete code list based on the cursor position context.
		* 
		* @returns void
		*/
		this.cacheCodeList = function()	{
			// Load current module based on the editor language/mode.
			var mode = parser.getMode();
	    // Get in array.
	    return mode.getList(false).done($.proxy(
	    	function(list) {
	    		// Get list reference.
	    		this.itemsCache = list;
			    // Sort list.
			    this.itemsCache.sort();
	    	}, this)
	    )
		}
		
		/**
		* Construct new Dialog object.
		* 
		* @returns void
		*/
		this.constructor = function() {
			// Set layout style sheet properties.
			this.dialog.css({'position' : 'absolute'});
			// Init internals! Bind Global events (e.g Command Key for showing dialog).
			this.internal.init();
		}
		
		/**
		* Close the dialog by hidding it.
		* 
		* The dialog list is not cleared yet but it'll be cleared
		* automatically once the dialog is opened using this.show()
		* method again.
		* 
		* @returns void
		*/
		this.close = function() {
			// Unbind Event/Command listeneres.
			this.internal.bind('off');
			// TODO: clear code list.
			
			// Hide dialog.
			this.dialog.hide();
		}
		
		/**
		* 
		*/
		this.getParser = function() {
			return parser;	
		}
		
		/**
		* Select next item.
		* 
		* @returns void
		*/
		this.moveDownList = function() {
			if (this.list.selectedIndex < (this.list.options.length - 1)) {
				this.list.selectedIndex++;
			}
		}
		
		/**
		* Select previous item.
		* 
		* @returns void
		*/
		this.moveUpList = function() {                                                                                                                                                 
			if (this.list.selectedIndex > 0) {
				this.list.selectedIndex--;
			}
		}
		
		/**
		* Page Down/Select the item after configuration.itemsPerPage
		* 
		* @returns void
		*/
		this.pageDownList = function() {
			var newSelectedIndex = (this.list.selectedIndex + configuration.itemsPerPage);
			if (newSelectedIndex >= this.list.options.length) {
				newSelectedIndex = this.list.options.length - 1;
			}
			this.list.selectedIndex = newSelectedIndex;
		}
		
		/**
		* Page Up/Select the item before configuration.itemsPerPage
		* 
		* @returns void
		*/
		this.pageUpList = function() {
			var newSelectedIndex = (this.list.selectedIndex - configuration.itemsPerPage);
			if (newSelectedIndex < 0) {
				newSelectedIndex = 0;
			}
			this.list.selectedIndex = newSelectedIndex;
		}
		
		/**
		* Set Dialog position.
		* 
		* This method reallocating Dialog position by calculating its left and top based
		* on the scroller offsets and another factors. Its also detecting if Dialog is out 
		* of display range (e.g Cursor is hidden because of scrolling, etc...).
		* 
		* @returns jQuery.Deferred Deferred object rejected if dialog position out of range or resolved when dialog displayed successful.
		*/
		this.reallocate = function() {
			var callback = $.Deferred();
			var renderer = editor.renderer;
			// Get editor cursor position -- relative to the editor.
			var cursor = renderer.$cursorLayer;
			// Move CAC dialog to the left by the width of the gutter.
			var gutterWidth = renderer.$gutterLayer.gutterWidth;
			var cursorHeight = editor.renderer.lineHeight;
			// Is the cursor out of boundary -- not visible because of scolling.
			var isHiddenOnTop = (cursor.pixelPos.top < 0);
			var isHiddenOnLeft = (cursor.pixelPos.left < renderer.scrollLeft);
			var isHiddenOnRight = ((cursor.pixelPos.left + gutterWidth) - renderer.scrollLeft) > renderer.$size.width;
			var isHiddenOnBottom = (cursor.pixelPos.top + cursor.config.offset) > renderer.$size.height;
			if (isHiddenOnTop || isHiddenOnBottom || isHiddenOnLeft || isHiddenOnRight) {
				// If the dialog was already visible and the cursor NOW is
				// out of boundry, hide the dialog.
				this.close();
				// Notify failure.
				callback.reject();
			}
			else {
				/* 
				Set position.
				Top = cursor top + cursor height.
				Left = cursor left + gutterWidth.
				*/
				var top = ((cursor.pixelPos.top + cursorHeight) - cursor.config.offset) + 'px';
				var left = ((cursor.pixelPos.left + gutterWidth) - renderer.scrollLeft) + 'px';
				this.dialog.css({top: top, left : left});
				// Dialog successfully displayed!
				callback.resolve();
			}
			return callback;
		}
		
		/**
		* Use current selected item.
		* 
		* Place current selected item text into editor based
		* on the calculated range.
		* 
		* @returns void
		*/
		this.select = function() {
			// Get selected item.
			var selected = this.list.value;
			// Close dialog.
			this.close();
			// Focus on editor.
			editor.focus();
			// Put the selected item into editor.
			editor.getSession().replace(this.internal.range, selected);
		}
		
		/**
		* Display the Dialog at the current cursor.
		* 
		* Displaying the dialog at the correct position
		* by reallocating. Also its detecting the text used
		* for filtering the list and then refresh the list.
		* 
		* @returns {Boolean}
		*/
		this.show = function() {
			// Set dialog position.
			this.reallocate().done($.proxy(
				function() {
					// Initialize range object used to fetch filter text.
					this.internal.initializeRange();
					// Cache code list once the dialog is opened.
					this.cacheCodeList().done($.proxy(
					  function() {
							// Display only if there is items available.
							if (this.autoCompleteList()) {
								// Bind events.
								this.internal.bind('on');
								// Show dialog
								this.dialog.hide().show('fast');
							}
					  }
						, this)
					);
				}, this)
			);
			// Chaining.
			return this;
		}
		
		/**
		* Internal functionality such events handlers
		* and other low level stuff resident here.
		* 
		* @type ace.pluggable.plugins.cac.prototypes.dialog.internal
		*/
		this.internal = new (function(dialog) {
			
			/**
			* Bind or Unbind all events needed for dialog to functional properly.
			* 
			* @param string "on" to bind events or "off" to unbind them.
			*
			* @returns void
			*/
			this.bind = function(state) {
				var methods = {};
				// Bind or Unbind events/Commands based on @state value.
				if (state == 'on') {
					// 
					methods.commands = 'addCommands';
					methods.events = 'addEventListener';
				}
				else {
					methods.commands = 'removeCommands';
					methods.events = 'removeEventListener';
				}
				// Bind events.
				$.each(this.data.events, $.proxy(
					function(index, event) {
						$.each(event.listeners, $.proxy(
							function(index, listener) {
								// Remove or add Event based on @state value.
								event.target[methods.events](listener.name, listener.handler, false);
							}, this)
						);						
					}, this)
				);
				// When adding Commands already used by ACE
				// we simple override the binding keys array.
				// We need to backup when adding commands and then
				// backup them again!
				if (state == 'on') {
					this.data.__CommandkeyBindingBackup__ = $.extend(true, {}, editor.commands.commmandKeyBinding);
				}
				// Associate/Unassociate commands.
				editor.commands[methods.commands](this.data.commands);
				// Restore original key binding array.
				if (state == 'off') {
					editor.commands.commmandKeyBinding = $.extend(true, {}, this.data.__CommandkeyBindingBackup__);
				}
			}
			
			/**
			* Get text used for filter the list.
			* 
			* Use this method to get text user typing while Dialog
			* is opened or text was already typed when user open the Dialog.
			* 
			* @returns string Filter text.
			*/
			this.getFilterText = function() {
				var cursor = editor.getCursorPosition();
				// Create range object for text before the cursor.
				var rangeBeforeCursor = $.extend(true, {}, this.range);
				// Always get text start from range and finished at the cursor column!
				rangeBeforeCursor.setEnd(rangeBeforeCursor.end.row, cursor.column);
				// Return filer text.
				return editor.getSession().getTextRange(rangeBeforeCursor);
			}
			
			/**
			* Initialize the internal object.
			* 
			* @returns void
			*/
			this.init = function() {
				// Command Key event to display the Dialog.
				editor.commands.addCommand({
					name : 'CodeAutoComplete',
					bindKey : {
						win : 'Ctrl-Space',
						mac : 'Command-G'
					},
					exec : $.proxy(this.onCommandKey, this)
				});
			}
			
			/**
			* Initialize range object used for jailing/knowing
			* dialog text range. 
			* 
			* Range object used to detect what range user
			* is allowed to navigate inside and to close the dialog
			* if user type of range.
			* 
			* @returns void
			*/
			this.initializeRange = function() {
				var selection = editor.getSession().selection;
				var cursor = editor.getCursorPosition();
				var currentLine = editor.getSession().getDocument().getLine(cursor.row);
				var searchMatches = [];
				// Get new range object.
				this.range = selection.getRange();
				// If no selection detect Alphanumberic characters before and after cursor.
				if (selection.isEmpty()) {
					// Search for Alpha-numeric or Dashes characters before cursor.
					var textBeforeCursor = currentLine.substring(0, cursor.column);
					if (searchMatches = textBeforeCursor.match(/[\w\-]+$/)) {
						// Get text start from the first Alpha-Numberic character
						// start before the cursor.
						var startColumn = (this.range.start.column - searchMatches[0].length);
						// Set range End! We need to change only column.
						this.range.setStart(this.range.start.row, startColumn);
					}
					// Set range end point.
					this.setRangeEnd();
				}
			}
		
			/**
			* Fired when clicking by mouse on a list item.
			*
			* 
			* @returns void
			*/
			this.onClickList = function(event) {
				dialog.select.call(dialog);
			}
			
			/**
			* Fired when Command key pressed.
			* 
			* @returns void
			*/
			this.onCommandKey = function() {
				dialog.show();
			}
			
			/**
			* Fired with ACE Editor onChange event and when a command key pressed.
			* 
			* Recevies ACE Editor change event and ACE Editor commands
			* callbacks too.
			* 
			* Update end Range when the content is changed and refresh the list.
			* Navigate Dialog list when Commands key is pressed.
			* 
			* @returns any
			*/
			this.onKeyPressed = function(event) {
				var eventReturn = undefined;
				if (event.type == 'change') { // ACE Editor 'change' event.
					// When deleting text ACE Editor automatically select the character next
					// to the delected characters. Clear selection so we can get the correct filter text.
					editor.selection.clearSelection();
					// Update range end.
					this.setRangeEnd();
					/** TODO: Remove isChangeEventFired -- Not used anymore! */
					// Stop Bubbling event listeners so that this.onMayBeCursorChanged won't get fired!
					this.isChangeEventFired = false;
				}
				else if (event.type == undefined) { // undefined Command key pressed.
					// Map command name to method name.
					var commandName = event.name;
					// Remove "cac_" prefix.
					var methodName = commandName.match(/^cac_(\w+)$/)[1];
					// Invoke method.
					eventReturn = dialog[methodName]();
					// Stop Bubbling event listeners so that this.onMayBeCursorChanged won't get fired!
					this.isCommandButtonEventFired = true;
				}
				return eventReturn;
			}
			
			/**
			* Fired when keyup and mousedown events bubbling up 
			* to the editor container.
			* 
			* If the cursor detecting our of range close Dialog.
			* 
			* @param DOMEvent keyup or mousedown event. 
			* @returns void
			*/
			this.onMayBeCursorChanged = function(event) {
				// If the event not a mousedown event and the ACE Editor 
				// onChange event is not fired so its suppose to be a navigation key pressed!
				if (event.type == 'keyup') {
					// Navgiation only on change event fired
					if (!this.isCommandButtonEventFired && !this.isChangeEventFired) {
						// Recalculate End Range AND Filter the list.
						dialog.autoCompleteList();
					}
					// Always reset event flags.
					this.isChangeEventFired = this.isCommandButtonEventFired = false;
				}
				// if cursor is out of the range close dialog.
				var cursor = editor.getCursorPosition();
				if (!this.range.contains(cursor.row, cursor.column)) {
					dialog.close();
				}
				// If Cursor changed using mouse and
				// it still inside the range refresh the list.
				else if (event.type == 'mousedown') {
					dialog.autoCompleteList();
				}
			}
			
			/**
			* Fired when scoll-top/left change positrion.
			* 
			* Reallocate the dialog.
			* 
			* @returns void
			*/
			this.onScroll = function() {
				// TODO: Fix; $cursorLayer.pixelPos is not yet got the new value inside the onScroll event. Only when scroll Array Pressed!
				dialog.reallocate();
			}
			
			/**
			* Set Dialog range end point to extend or shrink the 
			* range based on the detected text.
			* 
			* This method automatcially detect text after the cursor and
			* adjust the end range based on the detected texts.
			* 
			* @returns void
			*/
			this.setRangeEnd = function() {
				var cursor = editor.getCursorPosition();
				var currentLine = editor.getSession().getDocument().getLine(cursor.row);
				var searchMatches = [];
				// Search for Alpha-numeric or Dashes characters after cursor.
				var textAfterCursor = currentLine.substring(cursor.column);
				if (searchMatches = textAfterCursor.match(/^[\w\-]+/)) {
					// Increase text range End by the length of the matched text!
					var endColumn = (cursor.column + searchMatches[0].length);
					// Set range End! We need to change only column.
					this.range.setEnd(this.range.end.row, endColumn);								
				}
			}
			
			/**
			* Meta data storage.
			* 
			* @type Object
			*/
			this.data = {
				
				/**
				* Event for controlling CAC Dialog functions.
				* 
				* @type Array
				*/
				events : [
					{
						target : editor,
						listeners : [
							{name : 'change', handler : $.proxy(this.onKeyPressed, this)}
						]
					},
					{
						target : editor.getSession(),
						listeners : [
							{name : 'changeScrollTop', handler : $.proxy(this.onScroll, this)},
							{name : 'changeScrollLeft', handler : $.proxy(this.onScroll, this)}
						]
					},
					{
						target : editor.container,
						listeners : [
							{name : 'mousedown', handler : $.proxy(this.onMayBeCursorChanged, this)},
							{name: 'keyup', handler : $.proxy(this.onMayBeCursorChanged, this)},
							{name: 'keyup', handler : $.proxy(this.onNavigation, this)}
						]
					},
					{
						target : $(editor.container).find('textarea').get(0),
						listeners : [
							{name : 'paste', handler : $.proxy(this.onMayBeCursorChanged, this)}
						]
					},
					{
						target : dialog.list,
						listeners : [
							{name : 'click', handler : $.proxy(this.onClickList, this)}
						]
					}
				],
				
				/**
				* Commands used to navigate the dialog.
				* 
				* @type Array
				*/
				commands : [
					{
						name : 'cac_pageUpList',
						bindKey : {
							win : 'Pageup',
							mac : 'Pageup'
						},
						exec : function() {
							// Pass Command object as @event parameter.
							dialog.internal.onKeyPressed(this);
						}
					},
					{
						name : 'cac_pageDownList',
						bindKey : {
							win : 'Pagedown',
							mac : 'Pagedown'
						},
						exec : function() {
							// Pass Command object as @event parameter.
							dialog.internal.onKeyPressed(this);
						}
					},
					{
						name : 'cac_moveUpList',
						bindKey : {
							win : 'Up',
							mac : 'Up'
						},
						exec : function() {
							// Pass Command object as @event parameter.
							dialog.internal.onKeyPressed(this);
						}
					},
					{
						name : 'cac_moveDownList',
						bindKey : {
							win : 'Down',
							mac : 'Down'
						},
						exec : function() {
							// Pass Command object as @event parameter.
							dialog.internal.onKeyPressed(this);
						}
					},
					{
						name : 'cac_select',
						bindKey : {
							win : 'Return',
							mac : 'Return'
						},
						exec : function() {
							dialog.select.call(dialog);
						}
					},
					{
						name : 'cac_close',
						bindKey : {
							win : 'Esc',
							mac : 'Esc'
						},
						exec : function() {
							dialog.close.call(dialog);
						}
					}
				],
				
				/**
				* When showing the dialog, adding commands
				* we need to backup the ace-editor keyBinding Array
				* bewcause we simply overriding them. We need to restore then
				* back after the dialog is closed so that up, down, pageup, pagedown
				* and return buttons can behave correctly.
				* 
				* @type Array
				*/
				__CommandkeyBindingBackup__ : []		
			};

			/**
			* State that onChange event was clicked so that
			* onMayBeCursorChange handler can avoid processing the event.
			* 
			* How we could stop ACEEditor from bubbling Keyup event 
			* after ACE Editor fire onChange event!!!!
			*/
			this.isChangeEventFired = false;
						
			/**
			* State that command button was clicked so that
			* onMayBeCursorChange handler can avoid processing the event.
			* 
			* How we could stop ACEEditor from bubbling Keyup event 
			* after ACE Editor fire onChange event!!!!
			*/
			this.isCommandButtonEventFired = false;
					
			/**
			* Text range used for jailing the cursor while the dialog
			* is opened. Also its used to know which range to replace when item
			* selected from the list.
			* 
			* @type ACERange
			*/
			this.range = null;
			
		})(this); // End internal container.
		
		// Construct class.
		this.constructor();
		
	} // End CACDialog class.
})(jQuery);