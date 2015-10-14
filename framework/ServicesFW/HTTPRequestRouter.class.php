<?php
/**
* 
*/

/**
* 
*/
class CJTServicesHTTPRequestRouter {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $map;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $methodData;

	/**
	* put your comment there...
	* 
	* @param mixed $defaults
	* @param mixed $method
	* @return CJTServicesRequestRouter
	*/
	public function __construct($map, $method = 'request')  {
		# Initiaize
		$this->map = $map;
		$this->methodData =& $GLOBALS[ '_' . strtoupper( $method ) ];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $route
	* @return CJTServicesRequestRouter
	*/
	public function getRoute(& $route) {
		# Initialize
		$newRoute = array();
		# Overwrite defauls values if submitted
		foreach ( $this->map as $name => $paramName ) {
			$newRoute[ $name ] = isset( $this->methodData[ $paramName ] ) ?
													 $this->methodData[ $paramName ] :
													 ( isset( $route[ $name ] ) ? $route[ $name ] : null );
		}
		# Set new route
		$route = array_merge( $route, $newRoute );
		# Chain
		return $this;
	}
}
