<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_View_Block_Parameter_Parameters
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Parameters {
	
	/**
	* put your comment there...
	* 
	*/
	protected function getFactory() {
		return new CJT_Framework_View_Block_Parameter_Renderer_Factory();
	}

} // End class
