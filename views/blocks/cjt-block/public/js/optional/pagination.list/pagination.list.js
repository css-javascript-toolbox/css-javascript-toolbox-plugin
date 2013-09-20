/**
* 
*/
var CJTBlockAssignPanelPaginationList;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @param assignPanel
	* @param list
	*/
	CJTBlockAssignPanelPaginationList = function(assignPanel, list) {
		
		/**
		* put your comment there...
		* 
		*/
		var link = list.data('infoPanel').find('a.pagination-list');
		
		/**
		* put your comment there...
		* 
		* @type Number
		*/
		var loadedCount = 0;
		
		/**
		* put your comment there...
		* 
		*/
		var paginationList = $('<select size="2" class="pagination-options-list"></select>').insertAfter(link);
		
		/**
		* put your comment there...
		* 
		*/
		var _onhide = function(event) {
			// Initialize.
			var element = event.relatedTarget;
			if ((element !== paginationList.get(0)) && (element !== link.get(0))) {
				// Hide list.
				paginationList.hide();
			}
		};
		
		/**
		* put your comment there...
		* 
		*/
		var _onselect = function() {
			// Don't do anything when reseting the selectedIndex
			// through the _onshow method.
			var selectedIndex = parseInt(paginationList.prop('selectedIndex'));
			if (selectedIndex != -1) {
				// Load the number of pages laying between
				// the last loaded page and the new selected one.
				var pagesCount = parseInt(paginationList.val()) - loadedCount;
				// Load pages.
				assignPanel.list_GetAPOP.apply(list, [false, pagesCount]);
				// Close.
				paginationList.hide();
			}
		};
	
		/**
		* put your comment there...
		* 
		*/
		var _onshow = function() {
			// Disable all items until the loaded page.
			var realLoadedCount = list.data('loadedPages');
			// Disable all options that is already loaded and
			// not captured by the list as a reult o external
			// loading through the list scrollbar.
			for (var pageNo = (loadedCount + 1); pageNo <= realLoadedCount; pageNo++) {
				paginationList.children().eq(pageNo - 1).prop('disabled', true);
			}
			// Update loaded count
			loadedCount = realLoadedCount;
			// Set position.
			paginationList.prop('selectedIndex', -1)
			.show();
		};

		/**
		* 
		*/
		this.reset = function() {
			// Create pages list.
			var pagesCount = Math.ceil(list.data('totalItemsCount') / assignPanel.getIPerPage());
			// Clear list.
			paginationList.empty();
			// Add page numbers.
			for (var pageNo = 1; pageNo <= pagesCount; pageNo++) {
				// Create page option.
				var option = $('<option></option>').prop('value', pageNo).text(pageNo)
				// Add it
				.appendTo(paginationList);
			}
			// Reset vars.
			loadedCount	= 0;
		}

		// Show/Hide list when mouse is overed.
		link.mouseenter($.proxy(_onshow, this))
		.mouseleave($.proxy(_onhide, this));
		paginationList.mouseleave($.proxy(_onhide, this))

		// Select item.
		paginationList.change($.proxy(_onselect, this));
	};
	
})(jQuery);