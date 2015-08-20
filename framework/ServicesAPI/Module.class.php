<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesClientModule {
	
	/**
	* put your comment there...
	* 
	* @var WP_Error
	*/
	private $response;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $responseData;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $servicesAPI;
	
	/**
	* put your comment there...
	* 
	* @param mixed $extensionData
	* @return CJTProUpdateCheck
	*/
	public function __construct() {
		# Get Services API instance
		$this->servicesAPI =& CJTServicesClient::getInstance();
		# Obtain module name from $this class name with CJT prefix removed
		$this->moduleName = strtolower( substr( get_class( $this ), 3 ) );
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	protected function get($name) {
		return isset( $this->responseData[ $name ] ) ? $this->responseData[ $name ] : null;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getModuleName() {
		return $this->moduleName;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getResponseData() {
		return $this->responseData;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getServicesAPI() {
		return $this->servicesAPI;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $json
	*/
	protected function jsonDecode( $json ) {
		return json_decode( $json, true );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $method
	* @param mixed $params
	* @param mixed $postData
	*/
	public function makeCall($method, $params = null, $postData = null) {
		# Reset response object
		$this->response = null;
		$this->responseData = null;
		# Dispatch and store response
		$this->response = $this->getServicesAPI()->makeCall( $this->getModuleName(), $method, $params, $postData );
		# Read response data
		$this->responseData = wp_remote_retrieve_body( $this->response );
		# Chain
		return $this;
	}

}
