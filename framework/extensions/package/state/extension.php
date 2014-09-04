<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Extensions_Package_Extension {

	/**
	* 
	*/
	const INSTALLED = 1;
	
	/**
	* 
	*/
	const NOT_INSTALLED = 0;
	
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
		$this->name = (string) $extDeDoc>attributes()->class;
		# Getting DB Option name
		$this->dbOptionName = "{$name}.state";
		# Read from database
		$this->data = get_option($this->dbOptionName);
		# Cache state
		$this->getState();
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
		$newVersion = (string) $deDoc->packages->attributes()->version;
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
	
} # End class