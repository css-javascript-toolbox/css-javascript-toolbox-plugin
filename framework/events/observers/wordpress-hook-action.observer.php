<?php
/**
* 
*/

//Import dependencies.
require_once 'wordpress-hook.observer.php';

/**
* 
*/
class CJTWordpressActionHookObserver extends CJTWordpressHookObserver {
	
	/**
	* put your comment there...
	* 
	*/
	protected function redirectReturn() {
		return false;	
	}
	
} // End class