<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');


/**
* 
*/
class CJTPackageFile {
	
	/**
	* 
	*/
	const DEFINITION_FILE_NAME = 'definition.xml';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $definition = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $packageDirectory;
	
	/**
	* put your comment there...
	* 
	* @param mixed $packageDirectory
	* @return CJTPackageFile
	*/
	public function __construct($packageDirectory) {
		// Initialize.
		$this->packageDirectory = $packageDirectory;
		// Load XML definition.
		$this->loadDefinitionFile();
	}

	/**
	* put your comment there...
	* 
	*/
	public function document() {
		return $this->definition;
	}

	/**
	* Fetch specific object property that might required
	* specific wrapper (read file, read from external resources,
	* decode embedded content) for reading them.
	* 
	* This method now is only supporting read content from
	* local file laying under the package directory.
	* 
	* @param SimpleXMLElement  Object to fetch the property from.
	* @param String Property name.
	* @return mixed Return old value before located or FALSE if not located.
	*/
	public function fetchProperty($object, $propertyName) {
		// Initialize.
		$oldValue = FALSE;
		$attributes = $object->$propertyName->attributes();
		// Tarnsporter.
		$transporter = (string) $attributes->locate;
		// TRansporters.
		switch ($transporter) {
			case 'file':
				// Read from file.
				$oldValue = (string) $object->$propertyName;
				$content = file_get_contents("{$this->packageDirectory}/{$oldValue}");
			break;
			default:
				throw new Exception('Invalid transported! Transporter is not supported');
			break;
		}
		// Replace placeholder with real content so it can be read
		// by the caller!
		$object->$propertyName = $content;
		// Return old value replaced by the resolvedlocated one!
		return $oldValue;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getDirectory() {
		return $this->packageDirectory;
	}

	/**
	* Get only child elements with Text nodes within it.
	* 
	* Ignore all child containers (with childs too!) elements.
	* 
	* @param mixed $object
	*/
	public function getInfo($object = null) {
		// Initialize.
		$info = array();
		// Defaut object is the package object.
		if (!$object) {
			$object = $this->document();
		}
		// Package info is all the child elements except 'objects' element.
		$childs = $object->children();
		foreach ($childs as $field => $childObject) {
			// Remove child elements that has childs!
			if (!$childObject->count()) {
				$info[$field] = (string) $childObject;
			}
		}
		// This is it!
		return $info;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getPackageName() {
		return basename($this->packageDirectory);
	}

	/**
	* put your comment there...
	* 
	*/
	protected function loadDefinitionFile() {
		// Get full path to package definition file.
		$defnitionFile = "{$this->packageDirectory}/" . self::DEFINITION_FILE_NAME;
		// Load Definition file into SimpleXMLElement object.
		$this->definition = new SimpleXMLElement(file_get_contents($defnitionFile));		
	}

} // End class.
