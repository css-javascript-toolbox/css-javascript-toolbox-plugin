<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Block_Parameters_Base_Parameters
extends ArrayIterator {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $blockId = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @return CJT_Models_Block_Parameters
	*/
	public function __construct($blockId) {
		$this->blockId = $blockId;
		// Query all block parameters, pass it to the ArrayIterator object.
		parent::__construct($this->queryParameters());
	}

	/**
	* put your comment there...
	* 
	* @param mixed $row
	*/
	public abstract function createModelObject($row);
	
	/**
	* put your comment there...
	* 
	*/
	public function getBlockId() {
		return $this->blockId;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getParams() {
		return $this->params;
	}

	/**
	* put your comment there...
	* 
	*/
	protected abstract function getQuery();
	
	/**
	* put your comment there...
	* 
	*/
	protected function queryParameters() {
		// Initialize.
		$parameters = array();
		$query = $this->getQuery();
		$recset = cssJSToolbox::getInstance()->getDBDriver()->select($query, ARRAY_A);
		// Prototype to parameter model.
		foreach (new ArrayIterator($recset) as $row) {
			// Instantiate parameter model object.
			$param = $this->createModelObject($row);
			// Add as child if it has a parent or add in the root if not.
			if (!$param->getParent()) {
				// Add to list.
				$parameters[$param->getId()] = $param;
			}
			else {
				// Add as child parameter!
				$parameters[$param->getParent()]->addChild($param);
			}
			// Flat parameters list.
			$this->params[] = $param;
		}
		// Return only root parameters.
		return $parameters;
	}

} // End class.
