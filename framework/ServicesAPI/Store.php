<?php
/**
* 
*/

/**
* 
*/
class CJTStore extends CJTServicesClientModule {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $baseName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $itemName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $license;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $pluginFile;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $slug;
	
	/**
	* put your comment there...
	* 
	* @param mixed $itemName
	* @param mixed $license
	* @param mixed $pluginFile
	* @return CJTStore
	*/
	public function __construct($itemName, $license, $pluginFile) {
		# Initialize module base class
		parent::__construct();
		# Initialize 
		$this->pluginFile = $pluginFile;
		$this->baseName = plugin_basename( $pluginFile );
		$this->slug = basename( $this->baseName, '.php' );
		$this->license = $license;
		$this->itemName = $itemName;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $ownerName
	* @return mixed
	*/
	public function activateLicense($ownerName) {
		return (int) $this->makeCall( __FUNCTION__, compact( 'ownerName' ) )->getResponseData();
	}

	/**
	* put your comment there...
	* 
	* @param mixed $ownerName
	* @return mixed
	*/
	public function checkLicense($ownerName) {
		return (int) $this->makeCall( __FUNCTION__, compact( 'ownerName' ) )->getResponseData();
	}

	/**
	* put your comment there...
	* 
	* @param mixed $ownerName
	* @return mixed
	*/
	public function deactivateLicense($ownerName) {
		return (int) $this->makeCall( __FUNCTION__, compact( 'ownerName' ) )->getResponseData();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getBaseName() {
		return $this->baseName;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getItemName() {
		return $this->itemName;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getPluginInformation() {
		# Check if there is update available for current extension
		$info = $this->jsonDecode( $this->makeCall( __FUNCTION__ )->getResponseData() );
		# Return result
		return $info;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getPluginUpdate() {
		# Check if there is update available for current extension                            
		$pluginUpdate = $this->jsonDecode( $this->makeCall( __FUNCTION__ )->getResponseData() );
		# Return result
		return $pluginUpdate;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getLicense() {
		return $this->license;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getPluginFile() {
		return $this->pluginFile;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getSlug() {
		return $this->slug;
	}

	/**
	* put your comment there...
	* 
	*/
	public function hasUpdate() {
		# Get Version
		$pluginUpdate = $this->getPluginUpdate();
		$version = $pluginUpdate[ 'currentVersion' ];
		# Get Plugin version
		$pluginData = get_plugin_data( $this->getPluginFile() );
		# Return version details if there is new version/ otherwise return false
		$result = ( version_compare( $version, $pluginData[ 'Version' ] ) == 1 ) ? $pluginUpdate : false;
		return $result;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $method
	* @param mixed $params
	* @param mixed $postData
	* @return CJTServicesClientModule
	*/
	public function makeCall($method, $params = null, $postData = null) {
		# Always include the following parameters
		$params = array_merge( array( 
			'itemName' => $this->getItemName(), 
			'license' => $this->getLicense() ), 
			$params ? $params : array()
			);
		# Call method
		return parent::makeCall( $method, $params, $postData );
	}

}