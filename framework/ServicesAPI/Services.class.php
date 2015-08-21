<?php
/**
* 
*/

/**
* 
*/
class CJTServicesClient {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $instance;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $sslVerify = false;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $timeOut = 10;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $url;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		# Initialize
		####cssJSToolbox::getCJTWebSiteURL()
		$this->url = 'http://testwp.com/' . "cjtservices-api";
	}

	/**
	* put your comment there...
	* 
	* @return CJTServicesClient
	*/
	public static function & getInstance() {
		# Maintain only ONE instance
		if (!self::$instance) {
			self::$instance = new CJTServicesClient();
		}
		return self::$instance;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getSSLVerify() {
		return $this->sslVerify;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getTimeOut() {
		return $this->timeOut;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getUrl() {
		return $this->url;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $module
	* @param mixed $method
	* @param mixed $params
	*/
	public function makeCall($module, $method, $params = null, $postData = null) {
		# Prepare method name by replacing all UPPER Letters to Lower 
		# precedence by _
		while ( preg_match( '/[A-Z]/', $method, $upperLetter, PREG_OFFSET_CAPTURE ) ) {
			$method = substr_replace( $method, 
																( '_' . strtolower( $upperLetter[ 0 ][ 0 ] ) ), 
																$upperLetter[ 0 ][ 1 ], 1 
																);
		};
		# Lowercase module name
		$module = strtolower( $module );
		# Construct method call uri
		$methodUri = "{$this->url}/{$module}/{$method}";
		# Defaults and E_ALL Complains
		if ( !$params ) {
			$params = array();
		}
		# Encode parameters
		foreach ($params as $name => $value) {
			$params[ $name ] = urlencode( $value );
		}
		# Add query string parameters
		$methodUri = add_query_arg( $params, $methodUri );
		# Request parameters
		$requestParams = array(
			'timeout' => $this->getTimeOut(),
			'sslverify' => $this->getSSLVerify(),
			'body' => json_encode( $postData ),
		);
		# POST Server
		$post = wp_remote_post( $methodUri,  $requestParams );
		if ( !$post || ( $post instanceof WP_Error ) ) {
			throw new CJTServicesAPICallException( 'Unable to call CJT Services API' );
		}
		return $post;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	* @return {CJTServicesClient|mixed}
	*/
	public function & setSSLVerify($value) {
		# Set
		$this->sslVerify = $value;
		# Chaining
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	* @return CJTServicesClient
	*/
	public function & setTimeOut($value) {
		# Set
		$this->timeOut = $value;
		# Chaining
		return $this;
	}

	
}