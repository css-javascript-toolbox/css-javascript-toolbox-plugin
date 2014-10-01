<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block
extends CJT_Models_Package_Xml_Definition_Abstract {

	/**
	* 
	*/
	const VIRTUAL_PATH = 'block';

	/**
	* put your comment there...
	* 
	*/
	public function transit() {		
		// Initialize.
		$model = CJTModel::getInstance('blocks');
		$register = $this->register();
		// Prepare object + getting item to be saved into database.
		$block = $register['packageParser']->getItem($this->getNode());
		// Set other block internal data.
		$block['created'] = $block['lastModified'] = current_time('mysql');
		$block['owner'] = get_current_user_id();
		// Create Block.
		/// Expose BlockId for other nodes.
		$register['blockId'] = $model->add($block);
		$model->save();
		// Chaining.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function virtualPath() {
		// Block object is unique accross all the file
		// use unique global path that can be  accessed from everywhere
		// without knowing the real path as it might change based on the 
		// child model!
		return self::VIRTUAL_PATH;
	}

} // End class