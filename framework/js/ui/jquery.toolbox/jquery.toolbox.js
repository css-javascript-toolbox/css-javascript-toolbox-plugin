/**
* @version $ Id; cjttoolbox.jquery.js 21-03-2012 03:22:10 Ahmed Said $
* 
* CJT Toolbox jQuery Plugin.
*/

/*
* JQuery wrapper for the CJTToolBox Plugin.
*/ 
var CJTToolBoxNS = new (function ($) {
	
	/**
	*
	*
	*
	*
	*/
	this.ButtonBase = function() {
		
		/**
		*
		*
		*
		*
		*/
		this.callback = null;
		
		/**
		*
		*
		*
		*
		*/		
		this.cssClass = null;
		
		/**
		*
		*
		*
		*/
		this.enabled = false;
		
		/**
		*
		*
		*
		*
		*/		
		this.jButton = null;
		
		/**
		*
		*
		*
		*
		*/
		this.name = '';
    
		/**
		*
		*
		*
		*
		*/
		this.params = {};

		/**
		*
		*
		*
		*
		*/
		this.toolbox = null;
				
		/**
		*
		*
		*
		*
		*/
		this.ButtonBase = function(toolbox, name, callback, params) {
			// Initiliaze object properties.
			this.toolbox = toolbox;
			this.name = name;
			this.callback = callback;
			// Set params with defaults.
			this.params = $.extend({enable : true}, params);
			// Get jQuery object of button node.
			this.cssClass = '.cjttbl-' + name;
			this.jButton = this.toolbox.jToolbox.find(this.cssClass);
		}

		/**
		*
		*
		*
		*/
		this.enable = function(enable) {
			if (enable) {
				// Enable button twice duplicate event handler.
				if (!this.enabled) {
					this.jButton.bind('click.CJTButton', null, $.proxy(this._onclick, this));
					this.jButton.unbind('click.cjtbe-disabled');
					this.jButton.removeClass(this.toolbox.params.disabledClass);							
					this.enabled = true;
				}
			}
			else {
				// Don't dispatch handler when disabled.
				this.jButton.unbind('click.CJTButton');
				// For link to act inactive.
				this.jButton.bind('click.cjtbe-disabled', (function() {return false;}));
				this.jButton.addClass(this.toolbox.params.disabledClass);							
				this.enabled = false;
			}
			// Chaining.
			return this;
		}
		
		/**
		* 
		*/
		this.fireCallback = function(params) {
			// Proxy to button callback function.
			var proxyCallback = $.proxy(
				function(params) {
					return this.callback.apply(this.toolbox.params.context, params)
				}
			, this);
			// Fire callback function cna change context to user specified.
			return proxyCallback(params);
		}
		
		/**
		*
		*
		*
		*
		*/		
		this.isEnabled = function() {
			return this.enabled;
		}
		
	}; // End class.

	/**
	*
	*
	*
	*
	*/	
	this.Button = function(toolbox, name, callback, params) {

		/**
		* Event handler for this.params.linkClass.click() event.
		* 
		* The click event is used to dispatch the call to the link handler.
		*/
		this._onclick = function(event) {
			// All event handlers required toolbox reference to be in params var.
			var params = $.extend({toolbox : this.toolbox}, this.params);
			// Diaptch button event handler.
			this.fireCallback([event, params]);
			// For links to behave inactive (don't put # AND auto scroll to the button).
			return false;
		}
		
		/**
		*
		*
		*
		*
		*/	
		this.Button = function(toolbox, name, callback, params) {
			// Parent constructor.
			this.ButtonBase(toolbox, name, callback, params);
			// Enable/Disable Button events.
			this.enable(this.params.enable);
		}
		
		/**
		*
		*
		*
		*/
		this.loading = function(load, enable) {
			// If enable is undefined then if load = true then enable = false and vise versa.
			enable = ((enable == undefined) ? (!load) : enable);
			this.enable(enable);
			// If enabled add link text if disabled remove it and take tmp copy.
			if (load) {
				// Get text copy.
				this.jButton.get(0).cjttb_temp_text = this.jButton.text();
				// Remove text.
				this.jButton.text('');
			}
			else {
			  this.jButton.text(this.jButton.get(0).cjttb_temp_text);
			}
			// Show Loading.
			var method = load ? 'addClass' : 'removeClass';
			this.jButton[method](this.toolbox.params.loadingClass);
		}
		
	} // End class.
	// Extend ButtonBase Class.
	this.Button.prototype = new this.ButtonBase();
	
	/**
	*
	*
	*
	*
	*/	
	this.ButtonPopup = function(toolbox, name, callback, params) {

		/**
		* 
		*/
		this.popupTimer = null;
		
		/**
		*
		*
		*
		*
		*/
		this.targetElement = null;
		
		/**
		*
		*
		*
		*
		*/                               
		this._onmouseenter = function() {
			var cbMouseOut = null;
			// Clear time out in case the mouse is out and entered again.
			// By mean don't close dialog if the mouse is out and quickly back again!
			clearTimeout(this.popupTimer);
			// Process only if target element is not visible yet.
			// This condition prevent Shaking!
			if (this.targetElement.css('display') == 'none') {
				// Don't show the popup immediately when the mouse come over the button.
				// As our move the mouse and didnt decide yest which popup to open.
				// Stay for a while to make sure that this popup is desirable.
				this.popupTimer = setTimeout($.proxy(this.showPopup, this), 100);
				cbMouseOut = $.proxy(this._onmouseout, this);
				// Hide Popup if mouse out from button or the popup form!
				this.jButton.bind('mouseout.CJTButtonTouchMouseOut', cbMouseOut);
				this.targetElement.bind('mouseout.CJTButtonTouchMouseOut', cbMouseOut);
			}
		}

		/**
		*
		*
		*
		*
		*/		
		this._onmouseout = function(event) {
			// In all cases just clear the timeout timer.
			// It has no effect if the popup is already opened.
			// But it has effect if the Popup is not opened yet.
			clearTimeout(this.popupTimer);
			// Don't close the dialg once get out but give it a break!
			this.popupTimer = setTimeout($.proxy(function() {
					// Is the mouse still over button?
					var isOverButton = (event.relatedTarget === this.jButton.get(0));
					// Is mouse still over target element of any of its childs/descendants.
					var isOverElement = this.targetElement.find('*').andSelf().is(event.relatedTarget);
					// Is mouse is not over button or target element hide element and unbind events.
					if (!isOverButton && !isOverElement) {
						this.close();
					}
				}, this)
			, 400);
		}
		
		/**
		*
		*
		*
		*
		*/
		this.ButtonPopup = function(toolbox, name, callback, params) {
			// Set type parameters.
			params._type = $.extend({setTargetPosition : true}, params._type);
			// Initialize parent/prototype class.
			this.ButtonBase(toolbox, name, callback, params);
			// Show popup element when mouse entered button element.
			this.jButton
				.mouseenter($.proxy(this._onmouseenter, this))
				.click(function() {return false;}); // Behave inactive.
			// Prepare popup elements.
			this.targetElement = params._type.targetElementObject ? 
													 params._type.targetElement : 
													 this.toolbox.jToolbox.find(params._type.targetElement)
			// Be intelegant and don't close for just if the mouse got out
			// Please give User a break!!
			.mouseenter($.proxy(this._onmouseenter, this));
		}

		/**
		* put your comment there...
		* 		
		*/
		this.close = function() {
			this.jButton.unbind('mouseout.CJTButtonTouchMouseOut');
			this.targetElement.unbind('mouseout.CJTButtonTouchMouseOut').hide();
		}
		
		/**
		* 
		*/
		this.showPopup = function() {
			var cbParams = [this.targetElement, this];
			// Call onPopup event. If false is returned don't display the list.
			if (this.params._type.onPopup !== undefined) {
				var openPopup = this.params._type.onPopup.apply(this.toolbox.params.context, cbParams);
				if (!openPopup) {
					return false;
				}
			}
			// Callback before displaying menu.
			if ($.isFunction(this.callback)) {
				this.fireCallback(cbParams);	
			}
			// Display target element below button link if desired.
			if (this.params._type.setTargetPosition) {
				this.targetElement.css ({left : (this.jButton.position().left + 'px')})
			}
			// Show popup form.
			this.targetElement.show();
		}
		
	} // End class.
	// Extend ButtonBase Class.
	this.ButtonPopup.prototype = new this.ButtonBase();
		
	/**
	*
	*
	*
	*
	*/
	this.ButtonPopupList = function(toolbox, name, callback, params) {

		/**
		*
		*
		*
		*
		*/	
		this.currentValue = '';
		
		/**
		*
		*
		*
		*
		*/
		this.list = null;
		
		/**
		*
		*
		*
		*
		*/
		this._onlistchange = function() {
			var list = this.list.get(0);
			var newValue = list.options[list.selectedIndex].value;
			var newValueClass = this.params._type.cssMap[newValue];
			var currentClass = this.params._type.cssMap[this.currentValue];
			if (currentClass != undefined) {
				// Remove previous value class.
				this.jButton.removeClass(currentClass);			
			}
			// Add new value class.
			this.jButton.addClass(newValueClass);
			this.currentValue = newValue;
			// Call list change handler.
			this.fireCallback([event, this.params, newValue]);
			this.targetElement.hide('fast');
		}
		
		/**
		*
		*
		*
		*
		*/
		this.ButtonPopupList = function(toolbox, name, callback, params) {
			// Parent constructor.
			this.ButtonPopup(toolbox, name, callback, params);
			// Setting Popup list.
			this.list = this.targetElement.find(params._type.listElement);
			// Switch button class when list item is clicked.
			this.list.change($.proxy(this._onlistchange, this));
			// Set button initial class based on initial value.
			this.list.find('option').each(
				// Get option index from option value.
				function(index, option) {
					if (option.value == params._type.initialValue) {
						// 1. Select value option.
						// 2. Trigger the event to do the job just like user interaction.
						option.parentElement.selectedIndex = index;
						$(option.parentElement).change();
						return;
					}
				}
			)
		}
		
	}; // End class.
	// Extend ButtonBase Class.
	this.ButtonPopupList.prototype = new this.ButtonPopup();
	
	/**
	* jQuery Plugin interface.
	* version 6
	* @author Ahmed Said
	*/
	$.fn.CJTToolBox = function(args) {
	
		/**
		* Process objects list.
		*/
		return this.each(
		
			function() {
				
				// If first time to be called for this element
				// create new CJToolBox object for the this element.
				if (this.CJTToolBox == undefined) {

					/**
					* Reference to Toolbox node element to be used inside
					* the jquery object below.
					*
					* @var DOMElement
					*/				
					var tbDOMElement = this;
					
					/**
					* CJToolbox class.
					*
					* The purpose of this class is to manage links/buttons effects
					* and dispatch the call to speicifc handler. This is great for SOC.
					*
					* version 6
					* @author Ahmed Said
					*/
					var CJTToolBox = {

						/**
						*
						*
						*
						*
						*/
						buttons : {},

						/**
						*
						*
						*
						*
						*/
						jToolbox : $(tbDOMElement),
						
						/**
						* Every toolbox may has a position value added as a css class.
						*
						* CSS position class name schema is : cjtb-position-[POSITION].
						* 
						* @var string
						*/						 
						position : 'default',
						
						/**
						* Object options.
						*
						* Not all options are known yet.
						*
						* @var object
						*/
						params : {
							defaultHandler : null,
							disabledClass : 'cjttbs-disabled',
							linkClass : 'cjt-tb-link',
							loadingClass : 'cjttbs-loading'
						},
						
						/**
						* put your comment there...
						* 
						*/
						add : function(name, data) {
							// Get button class from type var.
							var buttonType = (data.type != undefined) ? data.type : '';
							var buttonClassName = 'Button' + buttonType;
							var buttonClass = CJTToolBoxNS[buttonClassName];
							// Create button object.
							var button = CJTToolBox.buttons[name] = new buttonClass();
							// If no params object passed create empty one.
							data.params = ((data.params == undefined) ? {} : data.params);
							// Initialize object must be done through custom constructor.
							// Object can't initialize itself because it'll produce an error
							// when created for inheritance.
							// Custom constuctor is the same name as the class.
							button[buttonClassName](CJTToolBox, name, data.callback, data.params);
							return button;
						},
						
						/**
						* Enable or Disable Toolbox user interactions.
						* 
						* @param enabled
						*/
						enable : function(enabled) {
							$.each(this.buttons, $.proxy(
								function(index, button) {
									button.enable(enabled);
								}, this)
							);
							// Chaining.
							return this;
						},
						
						/**
						* Initialize Toolbox object.
						*
						* @return void
						*/
						init : function()	{
							// Initialize object properties.
							if (position = tbDOMElement.className.match(/cjtb-position-(\w+)/)) {
								// If has a position class take it.
								this.position = position[1];
							}
							// Get buttons data copy.
							var handlers = $.extend({}, args.handlers);
							// Store other parameters.
							CJTToolBox.params = $.extend(CJTToolBox.params, args);
							// Create Toolbox buttons.
							$.each(handlers,
								function(name, data) {
									CJTToolBox.add(name, data);
								}
							)
						},
						
						/**
						* 
						*/
						remove : function(name) {
							// Get button.
							var button = this.buttons[name];
							// Remove button node.
							button.jButton.remove();
							// Delete from buttons list.
							delete this.buttons[name];
							// Chaining.
							return this;
						}
						
					}; // End Toolbox class.
					
					// Construct new ToolBox object.
					CJTToolBox.init();
					// Store DOMNode CJTToolBox Reference.
					this.CJTToolBox = CJTToolBox;
					
				} // end if(this.CJTToolBox == undefined)
				else {
					// Set options or dispatch methods.
				}
			}
		); // End .each
	} // End .fn
})(jQuery);