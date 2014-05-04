/**
*
*/

var CJTBlocks;

/*
*
*
*/
(function($){

	/*
	*
	*
	*/
	CJTBlocks = function() {

		/**
		*
		*
		*
		*/
		this.hasBlocksElement = CJTBlocksPage.blocksForm.find('input:hidden#cjt-hasBlocks');
		
		/**
		* 
		*/
		this.nextId = 1;
		
		/**
		* 
		*/
		this.signature = "cjtoolbox";
		
		/**
		*
		*
		*
		*
		*
		*/
		this.calculateChanges = function(changes, id, change) {
			// If field value changed add it to changes list.
			if (change) {
				changes[id] = true;
			}
			else {
				// Remove unchanged element.
				delete changes[id];
			}
			return this.hasChanges(changes);
		}
		
		/**
		* 
		*/
		this.getUFI = function(changes) {
			return this.nextId++;
		}
	
		/*
		*
		*
		*
		*/		
		this.getBlock = function(id) {
			var elementId = this.signature + '-' + id;
			var node = $('div#' + elementId);
			return node;
		}
		
		/**
		* 
		*
		* 
		*/		
		this.getBlocks = function() {
			return $('div[id^="' + this.signature + '"].postbox');
		}
		
		/*
		*
		*
		*
		*/		
		this.getExistsIds = function() {
			var idsElement = CJTBlocksPage.blocksForm.find('input:hidden[name="blocks[]"]');
			var ids = [];
			$(idsElement).each(
				function() {
					var id = parseInt($(this).val());
					ids.push(id);
				}
			)
			return ids;
		}
		
		/**
		* 
		*/
		this.getSortableName = function(id) {
			return 'cjtoolbox-' + id;
		}
		/*
		*
		*
		*
		*/
		this.hasBlocks = function(has) {
			var hasBlocks = this.hasBlocksElement.val();
			// Setter.
			if (has != undefined) {
				this.hasBlocksElement.val(has);
			}
			// Return old value.
			return ((hasBlocks == 'true') ? true : false);
		}
		
		/**
		* 
		*/
		this.hasChanges = function(changes) {
			// Initially to no changes.
			var isChanged = false;
			// Change is detected if there is at least one element found.
			$.each(changes,
				function() {
					if (this == true) {
						isChanged = true;
						return;
					}
				}
			);
			return isChanged;
		};

		/**
		* 
		*/
		this.toArray = function(prop, blocks) {
			// Initialize vars!
			var list = [];
			if (blocks == undefined) {
				blocks = this.getBlocks();
			}
			// Get property value for all blocks!
			$.each(blocks, function() {
					list.push(this.CJTBlock.block.get(prop));
				}
			);
			return list;
		}
		
	} // End class.
	
})(jQuery);