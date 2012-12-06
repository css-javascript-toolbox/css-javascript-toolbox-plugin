<?php
/**
* 
*/

/**
* 
*/
class CJTAttributes {

	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $flags;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $resetDefault;
	
	/**
	* put your comment there...
	* 
	* @param mixed $init
	*/
	public function __construct($initFlags = 0, $resetDefault = 0) {
			$this->flags = $initFlags;	
			$this->resetDefault = $resetDefault;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $flag
	*/
	public function isOff($flag) {
		return !$this->isOn($flag);
	}
		
	/**
	* put your comment there...
	* 
	* @param mixed $flag
	*/
	public function isOn($flag) {
		return $this->flags & $flag;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $flag
	*/
	public function off($flag) {
		$this->flags &= (~$flag); // All other flags are ON except our!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $flag
	*/
	public function on($flag) {
		$this->flags &= $flag; // All other flag are OFF except our!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function reset() {
		$this->flags = $this->resetDefault;
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $flag
	*/
	public function revert($flag) {
		$this->flags ^= $flag; // XOR
		return $this;
	}
	
} // End class.
