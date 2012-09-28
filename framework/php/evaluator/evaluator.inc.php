<?php
/**
* 
*/

/**
* 
*/
class CJTPHPCodeEvaluator {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $code = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $output = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $code
	* @return CJTPHPCodeEvaluator
	*/
	public function __construct($code) {
		// Encode code for using inside php data:// wrapper.
		$this->code = base64_encode($code);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $stack
	*/
	public function exec($stack = array()) {
		// Make all stack variables available to the local scope.
		extract($stack);
		// Get the content in an output buffer.
		ob_start();
		// Execute PHP code!
		require "data://text/plain;base64,{$this->code}";
		$this->output = ob_get_clean();
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getCode() {
		return $this->code;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $code
	*/
	public static function getInstance($code) {
		return new CJTPHPCodeEvaluator($code);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getOutput() {
		return $this->output;
	}
} // End class.
