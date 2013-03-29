<?php
/**
* 
*/

/**
* 
*/
abstract class CJTHTMLField {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $classesList;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $form;
	
	 /**
	 * put your comment there...
	 *                                                      
	 * @var mixed
	 */
	protected $id;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $value;
	
	/**
	* put your comment there...
	* 
	* @param mixed $form
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	* @return CJTHTMLField
	*/
	public function __construct($form, $name, $value, $id = null, $classesList = '') {
		// Initialize object.
		$this->form = $form;
		$this->name = $name;
		$this->value = $value;
		$this->id = $id ? $id : $this->name;
		$this->classesList = $classesList;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getForm() {
		return $this->form;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getId() {
		return $this->id;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getValue() {
		return $this->value;
	}
	
} // End class.
