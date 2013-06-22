<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_View_Block_Parameter_Grouper_Tab_Tab {
 	
 	/**
 	* put your comment there...
 	* 
 	* @var mixed
 	*/
 	protected $params = null;
 	
 	/**
 	* put your comment there...
 	* 
 	* @param mixed $parameters
 	* @return CJT_Framework_View_Block_Parameter_Grouper_Tab
 	*/
 	public function __construct($params) {
		$this->params = $params;
 	}

 	/**
 	* put your comment there...
 	* 
 	*/
 	public function __toString() {
 		ob_start();
		include 'index.phtml';
		return ob_get_clean();
 	}

 	/**
 	* put your comment there...
 	* 
 	*/
 	public function enqueueScripts() {
		
 	}
 	
 	/**
 	* put your comment there...
 	* 
 	*/
 	public function enqueueStyles() {
		
 	}
 	
 	/**
 	* put your comment there...
 	* 
 	*/
 	public function getParams() {
		return $this->params;
 	}

} // End class.