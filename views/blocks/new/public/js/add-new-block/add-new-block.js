/**
* @version $ Id; add-new-block.php 21-03-2012 03:22:10 Ahmed Said $
*
* This file assumed it linked through IFrame element.
*/

/**
* jQuery Wrapper for Add New Block module.
* 
*
*/
(function($) {

	/**
	* CJTBlocksPage object Reference.
	*
	* @var CJTBlocksPage
	*/
	CJTBlocksPage = window.parent.CJTBlocksPage;
	
	/**
	* Server New Block view actions/events.
	* 
	* The rule of the module is to validate and submitting
	* new block data to server, check the response and notify the user.
	*
	* @author Ahmed Said
	* @version 6
	*/
	CJTNewBlock = {
	
		/**
		* New Block form element.
		*
		* @var jqObject
		*/		
		form : null,
		
		/**
		* Event handler for closing the form.
		* 
		* return void
		*/
		_oncancel : function() {
			window.parent.tb_remove();
		},
		
		/**
		* Event handler for saving/adding the new block
		* 
		* Method doesn't validate form data, form validation
		* should be done before this event is triggered.
		*
		* Method submit the data to the server and notify user based
		* on the response.  
		*
		* return void
		*/
		_onsave : function(event) {
			event.preventDefault();
			// Append form data to it.
			var formParams = CJTBlocksPage.server.serializeObject(CJTNewBlock.form);
			// Request parameters.
			var requestParams = $.extend({ids : CJTBlocksPage.blocks.getExistsIds(), viewName : 'cjt-block'}, formParams);
			// Create block at the server.
			CJTBlocksPage.server.send('blocksPage', 'create_block', requestParams, 'get').success(
				function(response) {
					// Add new block to blocks page.
					newAddedBlock = CJTBlocksPage.addBlock(formParams.position, response.view)
					// Close window.
					window.parent.tb_remove();
				}
			);
		},
	
		/**
		* Initialize New Block object when the document is ready.
		* 
		* return void
		*/
		init : function()	{
		  CJTNewBlock.form = $('form#cjtoolbox_new_block_form');
			// Actions handled by this object.
			var events = {
				'.save' : CJTNewBlock._onsave,
				'.cancel' : CJTNewBlock._oncancel,
			};
			$.each(events, function(selector, handler) {
				CJTNewBlock.form.find(selector).click(handler);
			});
		},
		
	} // End class.
	
	// Bind when documet ready.
	$(CJTNewBlock.init);
	
})(jQuery);