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
	CJTBlockMenuView = new function() {
		
		/**
		* 
		*/
		this.block;
		
		/**
		* 
		*/
    	this.menu;

		/**
		* 
		*/    
    	this.applyTheme = function(themeBlock) {
			// Switch only if displayed for the current block that changing the theme.
			if(themeBlock == this.block) {
				this.menu.find('li>ul').css({'background-color' : this.block.theme.backgroundColor});	
			}
		};
		
		
		this.deattach = function() {
			// Daettach.
			this.block = null;
			this.menu.css({'display' : 'none'}).detach();
		};
		
		/**
		* put your comment there...
		* 
		*/
		this.initialize = function() {
			// Menu Id.
			var id = 'block-menu';
			// Apply jQueryMenu.
			this.menu = $('#' + id).menu({
				'position' : {'my' : 'left top', 'at' : 'left bottom'},
				'select' : $.proxy(
					function (event, ui) {
						// Get all parents menu.
						var parents = $(ui.item).parentsUntil('div.cjt-block').filter('ul').prev('a').get().reverse();
						var menuObject = this.block.menu;
						// Reach menu object.
						$.each(parents, function(index, parent) {
							menuObject = menuObject[parent.href.split('#')[1]];
						});
						// Fire block event.
						var itemKeyStruct = ui.item.find('a').prop('href').split('#')[1].split('-');
						// Dipatch event handler.
						menuObject[itemKeyStruct[0]](itemKeyStruct[1], event, ui);
						return false;
					}, this)
			});
			// chain.
			return this;
		};
		
		/**
		* 
		*/
		this.switchTo = function(element) {
			// Initialize.
			this.block = element.CJTBlock;
			// Display menu for that block.
			this.block.elements.editBlockName.after(this.menu);
			this.applyTheme(this.block);
			this.menu.css({'display' : 'block'});
		};

	};
	
})(jQuery)