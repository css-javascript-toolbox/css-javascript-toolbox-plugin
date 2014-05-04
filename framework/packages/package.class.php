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
	* @param Array TRansporter info array
	* @return CJTPackageFile Returning $this.
	*/
	public function fetchProperty($object, $propertyName, $transportersInfo = array()) {
		// Initialize.
		$attributes = $object->$propertyName->attributes();
		// Tarnsporter.
		$transporter = (string) $attributes->locate;
		// Do transport only if one specified!
		if ($transporter) {
			// Get value to be transported.
			$oldValue = (string) $object->$propertyName;
			// TRansporters.
			switch ($transporter) {
				// Read from file.
				case 'file':
					// If no file name (old value) is given use transporters Info parameter
					// for getting the file name.
					$fileName = $oldValue ? $oldValue : $transportersInfo[$propertyName];
					$content = file_get_contents("{$this->packageDirectory}/{$fileName}");
					// Save locatted file path in 'locatted' attribute.
					$object->$propertyName->addAttribute('locatted', $fileName);
				break;
				default:
					throw new Exception('Transported is not being supported!');
				break;
			}
			// Replace placeholder with real content so it can be read
			// by the caller!
			$object->$propertyName = $content;
		}
		// Chaining!
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getDirectory() {
		return $this->packageDirectory;
	}

	/**
	* put your comment there...
	* 
	* @param SimpleXMLElement $object
	* @param mixed $transportersInfo
	*/
	public function getItem($object = null, $transportersInfo = array()) {
		// Initialize.
		$item = array();
		// Defaut object is the package object.
		if (!$object) {
			$object = $this->document();
		}
		// Package info is all the child elements except 'objects' element.
		$childs = $object->children();
		foreach ($childs as $field => $childObject) {
			// Discard child elements that has childs!
			if (!count($childObject->children())) {
				// Fetch item properties that need to be transported from
				// other resource (e.g file).
				// Items that need to have transported would have
				// 'locate' attributes in its XML element.
				if (((string) $childObject->attributes()->locate)) {
					$this->fetchProperty($object, $field, $transportersInfo);
				}
				// Add item name to list.
				$item[$field] = (string) $childObject;
			}
		}
		// This is it!
		return $item;
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
