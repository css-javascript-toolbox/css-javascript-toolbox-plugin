<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Extensions_Package_State_Extension {

	/**
	* 
	*/
	const INSTALLED = 3;
	
	/**
	* 
	*/
	const NOT_INSTALLED = 1;
	
	/**
	* 
	*/
	const UPGRADE = 2;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $data;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbOptionName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $extDefDoc;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $newVersion;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $state;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @return CJT_Framework_Extensions_Package_InfoStructure
	*/
	public function __construct(SimpleXMLElement & $extDeDoc) {
		# Initialize
		$this->extDefDoc =& $extDeDoc;
		$this->name = (string) $extDeDoc->attributes()->class;
		$this->newVersion = (string) $extDeDoc->packages->attributes()->version;
		# Getting DB Option name
		$this->dbOptionName = self::getDbOptionName($this->name);
		# Read from database
		$this->data = get_option($this->dbOptionName, array());
		# Cache state
		$this->getState();
	}

	/**
	* put your comment there...
	* 
	* @param mixed $extensionClass
	*/
	public static function create($extensionClass) {
		# Option name
		$dbOptionName = self::getDbOptionName($extensionClass);
		# Reading cached state
		$state = get_option($dbOptionName);
		# Getting extension defFile
		$defFile = ABSPATH . PLUGINDIR . DIRECTORY_SEPARATOR . $state['defFile'];
		$deDoc = new SimpleXMLElement(file_get_contents($defFile));
		# Returns new instance
		return new CJT_Framework_Extensions_Package_State_Extension($deDoc);
	}

	/**
	* put your comment there...
	* 
	*/
	public function clearInstallInfo() {
		return delete_option($this->dbOptionName);
	}

	/**
	* put your comment there...
	* 	
	* @param mixed $name
	*/
	protected function getDBVar($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $extensionClass
	*/
	protected static function getDbOptionName($extensionClass) {
		return "{$extensionClass}.state";
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getExtensionDeDoc() {
		return $this->extDefDoc;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getInstalledVersion() {
		return $this->getDBVar('version');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getState() {
		# Initialize
		$deDoc =& $this->getExtensionDeDoc();
		$installedVersion = $this->getInstalledVersion();
		$newVersion = $this->newVersion;
		# Check extension installation state
		if (!$installedVersion) {
			$this->state = self::NOT_INSTALLED;
		}
		elseif ($installedVersion != $newVersion) {
			$this->state = self::UPGRADE;
		}
		else {
			$this->state = self::INSTALLED;
		}
		# Retrun state
		return $this->state;
	}

	public static function isInstalled($extensionClass) {
		# Getting DB Option name
		$dbOptionName = self::getDbOptionName($extensionClass);
		# Get data
		$state = get_option($dbOptionName, array());
		# Returns
		return !empty($state);
	}
	/**
	* put your comment there...
	* 
	* @param mixed $pluginFile
	* @return bool
	*/
	public function upgrade($defFile) {
		# Set version
		$this->data['version'] = $this->newVersion;
		# Hold path to XML file to be used when uninstalling extension data
		$this->data['defFile'] = $defFile;
		# Save
		return update_option($this->dbOptionName, $this->data);
	}

} # End class