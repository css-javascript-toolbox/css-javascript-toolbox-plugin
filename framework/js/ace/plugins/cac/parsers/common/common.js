/**
* 
*/

/**
* 
*/
(function($) {
	
	/**
	* Constructor.
	*/
	var parser = function(configuration, editor) {
		
		/** */
		var MODES_PATH = 'ace/plugins/cac/modes/';
		
		/**
		* put your comment there...
		* 
		*/
		var $mode = null;
		
		/**
		* put your comment there...
		* 
		*/
		var $modes = {};
		
		/**
		* put your comment there...
		* 
		*/
		this.getMode = function() {
			// Set last retreived mode as the active mode.
			$mode = this.getModeName();
			// Load mode if not loaded!
			if ($modes[$mode] == undefined) {
				var modePath = MODES_PATH + $mode;
				$modes[$mode] = require(modePath);
				// Set mode parameters.
				with ($modes[$mode]) {
					name = $mode;
					path = modePath;
					baseURI = configuration.modesBaseURI;
				}
			}
			return $modes[$mode];
		}
		
		/**
		* put your comment there...
		* 
		*/
		this.getModeName = function() {
			var name = '';
			var editSession = editor.getSession();
			// Search all modes to find the active mode object.
			$.each(editSession.$modes, $.proxy(
				function(path, mode)  {
					// The "referenced" mode is the currently active mode.
					if (mode === editSession.getMode()) {
						// Use mode file name/base name as the mode name.
						name = path.split('/').pop();
						return false; // Break $.each loop!
					}
				}, this)
			);
			return name;
		}
	
		/**
		* TODO: Check if the dialog should be opened automatically when specific characters are types. This should be based on the current loaded module.
		*/
		var $onKeyPress = function() {}

		// Create active mode object.
		
		// Get user input when user type new character.
		editor.addEventListener('change', $.proxy($onKeyPress, this));
	};
	
	// Assign to prototypes scope.
	ace.pluggable.plugins.cac.prototypes.parsers.common = parser;
	
})(jQuery);