<?php
/**
* 
*/

// No direct access allowed.
defined('ABSPATH') or die("Access denied");

// HTML Component base class.
require CJTOOLBOX_FRAMEWORK . '/html/component.class.php';

/**
* 
* @author Ahmed Said
*/
class HTMLCheckboxList extends HTMLComponent {
	
	/**
	* 
	*/
	const ITEM_CLASS_NAME = 'checkbox-list-item';

	/**
	* 
	*/
	const LIST_CLASS_NAME = 'checkbox-list';
	
	/**
	* 
	*/
	const ITEM_SELECTED_CLASS_NAME = 'checkbox-list-selected-item';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $className = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $id = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $list = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $itemDefaults = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $title = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	* @param mixed $name
	* @param mixed $className
	* @return HTMLCheckboxList
	*/
	public function __construct($id, $name, $title = '', $className = self::LIST_CLASS_NAME) {
		// Identify component file.
		parent::__construct(__FILE__);
	  // Initialize class properties.
		$this->id = $id;
		$this->className = $className;
		$this->title = $title;
		// Initialize default values.
		$this->itemDefaults = (object) array(
			'name' => $name,
			'className' => self::ITEM_CLASS_NAME,
			'selectedClassName' => self::ITEM_SELECTED_CLASS_NAME,
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		return $this->getTemplate('checkbox-list');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $text
	* @param mixed $value
	* @param mixed $checked
	* @param mixed $name
	* @param mixed $className
	* @param mixed $selectedClassName
	*/
	public function add($text, $value, $checked, $name, $className = null, $selectedClassName = null) {
		// Create new item.
		$item = (object) array();
		// Fill item with data.
		$item->text = $text;
		$item->value = $value;
		$item->checked = $checked;
		$item->name = "{$this->itemDefaults->name}{$name}";
		$item->className = $className ? $className : $this->itemDefaults->className;
		$item->selectedClassName = $selectedClassName ? $selectedClassName : $this->itemDefaults->selectedClassName;
		// Add item to the list.
		$this->list[$value] = $item;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function clear() {
		$this->list = array();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function delete($value) {
		unset($this->list[$value]);
	}
	
	/**
	* 
	*/
	public function getClassName() {
		return $this->className;
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
	* @param mixed $id
	* @param mixed $className
	*/
	public static function getInstance($id, $name, $title = '', $className = self::LIST_CLASS_NAME) {
		return new HTMLCheckboxList($id, $name, $title, $className);
	}

	/**
	* put your comment there...
	* 
	*/
	public function getTitle() {
		return $this->title;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $className
	* @param mixed $selectedClassName
	*/
	public function setItemDefault($name, $className = self::ITEM_CLASS_NAME, $selectedClassName = self::ITEM_SELECTED_CLASS_NAME) {
		$this->itemDefaults->name = $name;
		$this->itemDefaults->className = $className;
		$this->itemDefaults->selectedClassName = $selectedClassName;
	}
	
} // End class.