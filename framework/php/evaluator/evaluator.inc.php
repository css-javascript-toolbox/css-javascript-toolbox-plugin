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
	private $block = null;
	
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
	* @param stdClass Block object!
	* @return CJTPHPCodeEvaluator
	*/
	public function __construct(& $block) {
		// Hold Block code!
		$this->block = $block;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $stack
	*/
	public function exec($stack = array()) {
		$block =& $this->block;
		$code =& $block->code;
		// Make all stack variables available to the local scope.
		extract($stack);
		// Evaluate PHP codes!
		ob_start();
		// Evaluate PHP code and save the result!
		$beforeEvalError = error_get_last();
		$unusedResult = eval("?>{$code}");
		$afterEvalError = error_get_last();
		$evalOBuffer = ob_get_clean();
		$showError = ini_get('display_errors') && WP_DEBUG;
		$isError = $afterEvalError && ($beforeEvalError != $afterEvalError);
		// Handling errors!
		if ($isError && $showError) {
			$this->output  = 'CJT PHP Code Error detected for the following block: <br><br>';
			$this->output .= "Name: {$block->name}<br>";
			$this->output .= "ID: #{$block->id}<br><br>";
			if ($evalOBuffer) {
				$this->output .= "PHP Error message:<br>";
				$this->output .= $evalOBuffer;
			}
			$this->output .= "PHP Error tech Details:<br>";
			$this->output .= print_r($afterEvalError, true);
		}
		else { // Get evaludated code result!
			$this->output .= $evalOBuffer;
		}
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getBlock() {
		return $this->block;	
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