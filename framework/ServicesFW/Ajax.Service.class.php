<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesAjaxService {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $ajaxEndPoints;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $config;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $controller;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $controllerFactory;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $endPointsConfig;

	/**
	* put your comment there...
	* 
	*/
	public function __construct() {}
	
	/**
	* put your comment there...
	* 
	*/
	public function _definePoints() {
		# Allow child classes to deifne points
		$this->defineEndPoints();
		# Hook points
		foreach ( $this->ajaxEndPoints as $point ) {
			# Add Ajax action
			add_action( "wp_ajax_{$point[ 'name' ]}", array( $this, '_pointDelegated' ) );
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function _pointDelegated() {
		# Get ajax action
		$ajaxAction = current_filter();
		# Remove wordpress ajax action prefix added to the hook name
		$name = substr( $ajaxAction, strlen( 'wp_ajax_' ) );
		# Get endpoint config
		$endPointConfig =& $this->endPointsConfig[ $name ];
		# Route 
		$router = new CJTServicesHTTPRequestRouter( $this->config[ 'requestParams' ] );
		$router->getRoute( $endPointConfig[ 'route' ] );
		# Get Controller
		$this->controller =& $this->getControllerFactory()->getController( array_merge( $this->config, $endPointConfig ) );
		# Dispatch 
		$this->controller->dispatch( $endPointConfig[ 'route' ][ 'action' ] );
		# Output
		echo $this->controller->getResponse();
		# Exit
		die();
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function addEndPoint($name) {
		# Add point
		$this->ajaxEndPoints[] = array( 'name' =>  $name );
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $controllerFactory
	* @param mixed $config
	* @param mixed $endPointsConfig
	* @return CJTServicesAjaxService
	*/
	public function attach(& $controllerFactory, $config, $endPointsConfig) {
		# Attach
		$this->controllerFactory =& $controllerFactory;
		$this->config = $config;
		$this->endPointsConfig = $endPointsConfig;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected abstract function defineEndPoints();
	
	/**
	* put your comment there...
	* 
	*/
	public function & getControllerFactory() {
		return $this->controllerFactory;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & start() {
		# Hook points
		add_action( 'admin_init', array( $this, '_definePoints' ) );
		# Chain
		return $this;
	}
	
}