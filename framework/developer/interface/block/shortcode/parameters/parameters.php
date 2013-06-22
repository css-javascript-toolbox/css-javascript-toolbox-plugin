<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Parameters
extends CJT_Framework_Developer_Interface_Block_Parameters_Parameters {

	/**
	* Block programming interface (BPI).
	* 
	* @param mixed $name
	*/
	public function get($name) {
		// Allow writing it in the original case inside the code
		// however its only retrived by the lowercvase letter.
		return $this->params[strtolower($name)]->getValue();
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getFactory() {
		return new CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Factory();
	}

	/**
	* Shortcode parameters interface (SPI).
	*                                        
	* @param mixed $strings
	*/
	public function loadString($strings) {
		// Load all parameters with shortcde parameters.
		foreach ($this->getParams() as $name => $param) {
			// Dont override default value unless parameter is passed!
			if (isset($strings[$name])) {
				$param->loadString($strings[$name]);
			}
			// Validate type!                                                                                                                                           
			if (!$param->validate()) {
				echo cssJSToolbox::getText("Invalid Shortcode parameter: {$type->getMessage()}");
			}
			// Remove the value from the stringsValue allow validating if 
			// any invalid parameter name is passed!
			// See after the loop.
			unset($strings[$name]);
		}
		// If there is any string values still exists then invalid parameter is passed.
		if (!empty($strings)) {
			echo cssJSToolbox::getText("Invalid Shortcode parameter(s) has been passed! Please check parameters name.");
		}
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $blockName
	*/
	public function shortcode($blockName) {
		// Initialize.
		$shortcode = "[cjtoolbox name='{$blockName}'\n\t\t%s]\n[/cjtoolbox]";
		$parameters = array();
		// Aggregate all parameters strings in one array!
		foreach ($this->getParams() as $param) {
			$parameters[] = $param->shortcode();
		}
		// Join with a TAB character!
		return sprintf($shortcode, join("\t\t\n", $parameters));
	}

} // End class
