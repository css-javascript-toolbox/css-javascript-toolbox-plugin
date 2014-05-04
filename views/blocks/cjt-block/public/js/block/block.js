/**
* 
*/
var CJTBlock;

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @param element
	*/
	CJTBlock = function(blockPlugin, element) {

		// Constructor.
		this.CJTBlock = function() { this.CJTBlockBase(blockPlugin, element, {}); }
		
		/**
		* 
		*/
		this.load = function() {
			// Initialize.
			var properties = {};
			// Define CJTBlock properties for assignment panel.
			properties.links = {om : new CJTBlockPropertyHTMLNodeOM(), flags: 'rws', selector : 'textarea[name="cjtoolbox[{blockId}][links]"]'};
			properties.expressions = {om : new CJTBlockPropertyHTMLNodeOM(), flags: 'rws', selector : 'textarea[name="cjtoolbox[{blockId}][expressions]"]'};
			properties.pinPoint = {om : new CJTBlockPropertyAPItemsList(), flags: 'rws', selector : 'input:checkbox[name="cjtoolbox[{blockId}][pinPoint][]"]'};
			properties.pages = {om : new CJTBlockPropertyAPItemsList(), flags: 'rws',  selector : 'input:checkbox[name="cjtoolbox[{blockId}][pages][]"]'};
			properties.posts = {om : new CJTBlockPropertyAPItemsList(), flags: 'rws', selector : 'input:checkbox[name="cjtoolbox[{blockId}][posts][]"]'};
			properties.categories = {om : new CJTBlockPropertyAPItemsList(), flags: 'rws', selector : 'input:checkbox[name="cjtoolbox[{blockId}][categories][]"]'};
			properties.pagesPanelToggleState = {flags: 'rwc'};
			properties.assignOnlyModeSwitcher = {flags: 'rwc'};
			// Load Base Model.
			this.loadBase(properties);
		}

		// Construct!
		this.CJTBlock();
	}
	
	// Extend CJTBlockBase class.
	CJTBlock.prototype = new CJTBlockBase();
	
})(jQuery)