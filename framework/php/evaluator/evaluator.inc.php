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
		$this->code = $code;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $stack
	*/
	public function exec($stack = array()) {
		// Base64 encoding for code to be included by data:// wrapper!
		$base64Code = base64_encode($this->code);
		// Make all stack variables available to the local scope.
		extract($stack);
		// Get the content in an output buffer.
		ob_start();
		// Execute PHP code!
		@include "data://text/plain;base64,{$base64Code}";
		$this->output = error_get_last() ? $this->code : ob_get_clean();
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
