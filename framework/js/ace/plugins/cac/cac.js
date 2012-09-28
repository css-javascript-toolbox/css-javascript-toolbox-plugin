/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	var defaultConfiguration = {dialog : {}, parser : {name : 'common'}};
	
	/**
	* put your comment there...
	* 
	* @type Object
	*/
	ace.pluggable.plugins.cac = {
		
		/**
		* put your comment there...
		* 
		*/
		prototypes : {parsers : {}},

		/**
		* put your comment there...
		* 
		*/
		references : {},
		
		/**
		* put your comment there...
		* 
		* @param editor
		* @param configuration
		*/
		apply : function(editor, configuration) {
			// Prepare configuration.
			configuration = $.extend(true, {}, defaultConfiguration, configuration);
			// Get ACE Editor instance if DOMNode or selector passed @editor param.
			var aceEditor = this.getEditor(editor);
			// Create new parser object based on the given configuration
			var parser = this.createParser(configuration.parser, editor);
			// Create CAC Dialog object, assign parse and editor object.
			var dialog = new ace.pluggable.plugins.cac.prototypes.dialog(aceEditor, parser, configuration.dialog);
			// Make sure editor has plugins namespace created!
			if (aceEditor.plugins == undefined) {
				aceEditor.plugins = {};
			}
			// Set a reference for the new created CAC dialog inside the ACEEditor instance.
			aceEditor.plugins.cac = dialog;
		},
		
		/**
		* put your comment there...
		* 
		* @param parser
		*/
		createParser : function(configuration, dialog, editor) {
			// Load parser through jsRequired.
			var parserPrototype = ace.pluggable.plugins.cac.prototypes.parsers[configuration.name];
			if (!parserPrototype) {
				throw 'Could not load parser "' + configuration.name;
			}
			// Create parser object.
			var parser = new parserPrototype(configuration, dialog, editor);
			return parser;
		},
		
		/**
		* put your comment there...
		* 
		* @param editor
		*/
		getEditor : function(editor) {
			return editor;
		}
		
	} // End cac Plugin prototype.
})(jQuery);