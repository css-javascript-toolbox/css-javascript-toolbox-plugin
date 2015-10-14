<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesWPService {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controller;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerFactory;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $config;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $serviceConfig;
	
	/**
	* put your comment there...
	* 
	* @param mixed $controllerFactory
	* @param mixed $config
	* @param mixed $serviceConfig
	*/
	public function & attach(& $controllerFactory, $config, $serviceConfig) {

		$this->controllerFactory =& $controllerFactory;
		$this->config = $config;
		$this->serviceConfig = $serviceConfig;
		
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getController() {
		return $this->controller;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getControllerFactory() {
		return $this->controllerFactory;
	}
		
}
