<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_View_Block_Parameter_Renderer_Imageslist_Imageslist
extends CJT_Framework_View_Block_Parameter_Base_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function enqueueScripts() {
		return array(
			'framework:js:ui:{CJT-}jquery.link-progress',
			'framework:view:block:parameter:renderer:imageslist:public:js:imageslist'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function enqueueStyles() {
		return array('framework:view:block:parameter:renderer:imageslist:public:css:imageslist');
	}

} // End class.