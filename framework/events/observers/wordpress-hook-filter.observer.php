<?php
/**
* 
*/

//Import dependencies.
require_once 'wordpress-hook.observer.php';

/**
* 
*/
class CJTWordpressFilterHookObserver extends CJTWordpressHookObserver {
	
	/**
	* put your comment there...
	* 
	*/
	protected function redirectReturn() {
		return $this->params[1];
	}
	
} // End class