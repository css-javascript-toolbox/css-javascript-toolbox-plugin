<?php
/**
* 
*/


// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstallerBlocks02 extends CJTInstallerBlock {
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		// Block vars!
		$key = $this->key();
		$id = $this->id();
		$block =& $this[$key];
		// Give a name to the block!
		$block['name'] = "Block #{$id}";
		$block['state'] = 'active'; // Defautt to active!
		$block['location'] = 'header'; // Output in header!
		// Fix links as it saved with /n/r as line end and it got splitted using only /n!
		// This is  a Bug in version 0.2! Only the last link is correct but the others carry /r at the end!
		$block['links']	= str_replace("\r\n", "\n", $block['links']);
		// Block referdnce has not effect with ArrayIterator update it internally!
		$this[$key] = $block;
		// Upgrade block (save into db, etc...)
		return parent::upgrade();
	}
	
} // End class.