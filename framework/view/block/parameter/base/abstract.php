<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Framework_View_Block_Parameter_Base_Abstract
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Abstract 
implements CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Interface_Type,
CJT_Framework_View_Block_Parameter_Interface_Type {
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Get class file name.
		$content = $this->getTypeObject()->getFactory()->getClassFile(get_class($this), 'index.phtml');
		// Get content on output buffer.
		ob_start();
		include $content;
		return ob_get_clean();
	}

	/**
	* put your comment there...
	* 
	*/
	public function enqueueScripts() {return array();}
	
	/**
	* put your comment there...
	* 
	*/
	public function enqueueStyles() {return array();}

	/**
	* put your comment there...
	* 
	* @param mixed $parameter
	*/
	public function getBaseTypeFactory($parameter) {
		// Hold local instance!
		static $baseTypeFactory;
		if (!$baseTypeFactory) {
			$baseTypeFactory = new CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Factory();
		}
		// Type factory
		return array(
			'factory' => $baseTypeFactory,
			'typeName' => $parameter->getOriginalType(),
		);
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getControlName() {
		return $this->getDefinition()->getName(true);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getElementId() {
		// Get element name.
		$name = $this->getName();
		// Replace all [ and ] to dashes!
		$id = str_replace(array('[', ']'), '-', $name);
		return $id;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		// Initialize.
		$name = '';
		$names = array();
		// Add current parameter name as first.
		$names[] = $this->getControlName();
		// Unshift all the parent names at the begning of the names list!
		$parent = $this;
		while ($parent = $parent->getParent()) {
			array_unshift($names, $parent->getControlName());
		}
		// Use first item as the name and everything else as array.
		$name = 'form-data';
		// Get everything else as array.
		if (!empty($names)) {
			$name .= '[' . implode('][', $names) . ']';	
		}
		// Return string name
		return $name;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	protected function getTemplate($name) {
		// Get RENDERER class name.
		$path = explode('_', get_class($this));
		$rendererName = array_pop($path);
		// For now it only support getting standard templates
		// from the abstract class.
		// In the future it might used to get template overrided in the
		// $this object class.
 		ob_start();
		include "abstract/template/{$name}.phtml";
		return ob_get_clean();
	}

	/**
	* put your comment there...
	* 
	*/
	public function shortcode() {
		return $this->getTypeObject()->shortcode();
	}
	
} // End class.
