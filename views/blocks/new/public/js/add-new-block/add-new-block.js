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
		* put your comment there...
		* 
		*/
		errors : null,
		
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
			this.isValid().done($.proxy(
			  function() {
					// Append form data to it.
					var formData = this.form.serializeObject();
					// Request parameters.
					var requestParams = $.extend({ids : CJTBlocksPage.blocks.getExistsIds(), viewName : 'cjt-block'}, formData);
					// Disable new form.
					this.form.find('input, select').prop('disabled', true);
					// Create block at the server.
					CJTBlocksPage.server.send('blocksPage', 'create_block', requestParams, 'get')
					.success($.proxy(
						function(response) {
							// Add new block to blocks page.
							newAddedBlock = CJTBlocksPage.addBlock(formData.position, response.view)
							// Close window.
							window.parent.tb_remove();
						}, this)
					// Request COMPLETE!
					).complete($.proxy(
					  function() {
							// Enable new form.
							this.form.find('input, select').prop('disabled', false);
					  }, this)
					);	
			  }, this)
			).fail($.proxy(
				function() {
					this.errors.show('width=380&height=170');
				}, this)
			);
		},
	
		/**
		* Initialize New Block object when the document is ready.
		* 
		* return void
		*/
		init : function()	{
		  this.form = $('form#cjtoolbox_new_block_form');
		  this.errors = new CJTSimpleErrorDialog(this.form)
		  .add('name', /^[A-Za-z0-9\!\#\@\$\&\*\(\)\[\]\x20\-\_\+\?\:\;\.]{1,50}$/, CJTAddNewBlockI18N.invalidName);
			// Actions handled by this object.
			var events = {'.save' : this._onsave, '.cancel' : this._oncancel};
			$.each(events, $.proxy(
				function(selector, handler) {
					this.form.find(selector).click($.proxy(handler, this));
				}, this)
			);
		},
		
		/**
		* Is the form data is valid for submission!
		* 
		* @returns boolean
		*/
		isValid : function() {
			var promising = $.Deferred();
			// Client side validation
			if (!this.errors.validate().hasError()) {
				// Make sure that the Block name is not taked by antoher Block!
				var request = {
					returns : ['id'],
					filter : {field : 'name', value : this.form.prop('name').value}
				};
				CJTBlocksPage.server.send('block', 'getBlockBy', request)
				.success($.proxy(
					function(response) {
						// FAIL -- Name is being used by antoher Block!!!
						if (response.id) {
							var error = {
									name : this.errors.fetchFieldInfo('name').text,
									message:  CJTAddNewBlockI18N.AlreadyInUse
							};
							this.errors.errors.push(error);
							promising.reject();
						}
						else {
							// Successed -- Name is not taken yet!
							promising.resolve();
						}
					}, this)
				);
			}
			else {
				// Client side validatiom faild!
				promising.reject();
			}
			return promising;
		}
		
	} // End class.
	
	// Bind when documet ready.
	$($.proxy(CJTNewBlock.init, CJTNewBlock));
	
})(jQuery);