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
 	* @var mixed
 	*/
 	protected $paramsView = array('enqueue' => 
 		array('scripts' => array(), 'styles' => array())
 	);

 	/**
 	* put your comment there...
 	* 
 	* @param mixed $parameters
 	* @return CJT_Framework_View_Block_Parameter_Grouper_Tab
 	*/
 	public function __construct($params) {
 		// Cache params list reference.
		$this->params = $params;
		// Cache styles and scripts to be enqueued.
		foreach ($this->params as $groupKey => $group) {
			foreach ($group['params'] as $param) {
				$this->paramsView['enqueue']['scripts'] = array_merge($this->paramsView['enqueue']['scripts'], $param->enqueueScripts());
				$this->paramsView['enqueue']['styles'] = array_merge($this->paramsView['enqueue']['styles'], $param->enqueueStyles());
			}
		}
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
 		return array_merge(array(
 			'jquery',
 			'jquery-ui-tabs'
 			), 
 		$this->paramsView['enqueue']['scripts']);
 	}
 	
 	/**
 	* put your comment there...
 	* 
 	*/
 	public function enqueueStyles() {
 		return array_merge(array(
 			'framework:view:block:parameter:grouper:tab:public:css:tab',
 			'framework:css:jquery-ui-1.8.21.custom',
 			), 
 		$this->paramsView['enqueue']['styles']);
 	}
 	
 	/**
 	* put your comment there...
 	* 
 	*/
 	public function getParams() {
		return $this->params;
 	}

} // End class.