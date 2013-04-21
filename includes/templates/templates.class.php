<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJTTemplates {
	
	/**
	* Get templates types mapped by extensions of the type file.
	* 
	* @return array
	*/
	public static function getExtensionTypeMap() {
		// Initialize!
		static $map = null;
		if (!$map) {
			// Us extension as key and type-name as value.
			foreach (cssJSToolbox::$config->templates->types as $type => $data) {
				$map[$data->extension] = $type;
			}
		}
		return $map;
	}

	/**
	* Get template type extension name.
	* 
	* @param mixed $typeName
	*/
	public static function getExtensionType($extension) {
		// Get extensions->types map.
		$map = self::getExtensionTypeMap();
		// Return Type if Extension exists or FALSE otherwise.
		return isset($map[$extension]) ? $map[$extension] : FALSE;
	}

} // End class. 