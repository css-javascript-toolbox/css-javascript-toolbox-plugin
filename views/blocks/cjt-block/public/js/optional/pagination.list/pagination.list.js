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
		var _onhide = function() {
			// Hide list.
			paginationList.hide();
		};
		
		/**
		* put your comment there...
		* 
		*/
		var _onselect = function() {
			
		};
	
		/**
		* put your comment there...
		* 
		*/
		var _onshow = function() {
			// Disable all items until the loaded page.
			var realLoadedCount = list.data('loadedCount');
			var loadedDiff = realLoadedCount - loadedCount;
			// Disable all options that is already loaded and
			// not captured by the list as a reult o external
			// loading through the list scrollbar.
			for (var pagNo = (loadedCount + 1); pageNo <= loadedDiff; pageNo++) {
				paginationList.children().eq(pageNo - 1).prop('disabled', true);
			}
			// Set position.
			paginationList.css().show();
		};
		
		// Show/Hide list when mouse is overed.
		link.mouseenter($.proxy(_onshow, this))
		.mouseleave($.proxy(_onhide, this));
		// Select item.
		paginationList.change($.proxy(_onselect, this));
		// Create pages list.
		var pagesCount = list.data('totalItemsCount') / assignPanel.getIPerPage();
		for (var pageNo = 1; pageNo <= pagesCount; pageNo++) {
			// Create page option.
			var option = $('<option></option>').prop('value', pageNo).text(pageNo)
			// Add it
			.appendTo(paginationList);
		}
	};
	
})(jQuery);