<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Parameters_Form_Value {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $text;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $value;
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	* @return CJT_Models_Block_Parameters_Form_Value
	*/
	public function __construct($data) {
		$this->text = isset($data['text']) ? $data['text'] : null;
		$this->value = isset($data['value']) ? $data['value'] : null;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getText() {
		return $this->text;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getValue() {
		return $this->value;
	}

} // End class
