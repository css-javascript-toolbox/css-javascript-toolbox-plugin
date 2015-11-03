<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesInstaller extends CJTServicesModel {
	
	// INSTALLER STATES
	const STATE_DOWNGRADE = -1;
	const STATE_FRESH_INSTALL = 2;
	const STATE_INSTALLED = 0;
	const STATE_UPGRADE = 1;
	
	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $_currentVersion;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $_upgraders = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installedVersion = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct( )
	{
		
		parent::__construct();
		
		// Current version is the latest version on the upgraders array
		$this->_currentVersion = end( $this->_upgraders );		
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInstalledVersion()
	{
		return $this->installedVersion;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getState()
	{
		
		$installedVersion = $this->getInstalledVersion();
		
		return 	! $installedVersion ? 
							self::STATE_FRESH_INSTALL : // Never installed
							version_compare( $this->_currentVersion, $installedVersion ); // Upgrade, Installed, Downgrade
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $version
	*/
	public function getUpgraderIndex( $version )
	{
		return array_search( $version, $this->_upgraders );
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $version
	*/
	public function getUpgraderName( $version )
	{
		return str_replace( array( '.', '-' ), '', $version );
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function install()
	{
		return $this->processUpgraders( 0 );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $version
	*/
	public function processUpgraders( $upgraderIndex )
	{
		
		# Call all exists upgraders start from $version upgrader
		# to the end of tehe upgraders array
		for ( ; $upgraderIndex < count( $this->_upgraders ); $upgraderIndex ++ )
		{
			
			$upgraderName = $this->getUpgraderName( $this->_upgraders[ $upgraderIndex ] );
			
			$upgraderMethodName = "_upgrade{$upgraderName}";
			
			if ( method_exists( $this, $upgraderMethodName ) )
			{
				if ( ! $this->$upgraderMethodName() )
				{
					return false;
				}
				
			}
			
		}
		
		$this->installedVersion = $this->_currentVersion;
		
		$this->saveState();
		
		return true;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade()
	{
		
		$upgraderIndex = $this->getUpgraderIndex( $this->_currentVersion );
		
		if ( $upgraderIndex === FALSE )
		{
			throw new Exception( "Upgrader does not exists!!!" );
		}
		
		return $this->processUpgraders( $upgraderIndex );
		
	}
	
}