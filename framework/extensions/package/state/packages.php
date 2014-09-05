<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Extensions_Package_State_Packages extends ArrayIterator {
	
	/**
	* 
	*/
	const INSTALLED = 1;
	
	/**
	* 
	*/
	const NOT_INSTALLED = 2;
	
	/**
	* 
	*/
	const UPGRADE = 3;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $extDeDoc;
	
	/**
	* put your comment there...
	* 
	* @var SimpleXMLElement
	*/
	protected $dbOptionName;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $deDocPackages;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installedPackages;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $oldPackagesQueue;
		
	/**
	* put your comment there...
	* 
	* @param SimpleXMLElement $exDeDoc
	* @return {CJT_Framework_Extensions_Package_State_Packages|SimpleXMLElement}
	*/
	public function __construct(SimpleXMLElement & $extDeDoc) {
		# Initialize
		$extensionName = (string) $extDeDoc->attributes()->class;
		$this->dbOptionName = "{$extensionName}.state.packages";
		$this->extDeDoc =& $extDeDoc;
		# Reading Database packages
		$this->installedPackages = get_option($this->dbOptionName, array());
		# Caching DeDoc packages
		foreach ($extDeDoc->packages->package as $package) {
			# Package data
			$pckData = $package->attributes();
			# Cache package data
			$this->deDocPackages[(string) $pckData->name] = array(
				'version' => ((string) $pckData->version),
				'file' => ((string) $pckData->file)
			);
		}
		# Support dedoc iteration side
		parent::__construct($this->deDocPackages);
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
	*/
	public function & getDeletedPackages() {
		return $this->oldPackagesQueue;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getInstalledPackages() {
		return $this->installedPackages;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getState() {
		# INitialize
		$state = null;
		$currentPckKey = $this->key();
		$newPck = $this->current();
		$oldPck = isset($this->oldPackagesQueue[$currentPckKey]) ? $this->oldPackagesQueue[$currentPckKey] : array('version' => null);
		$package = $this->current();
		$oldVersion = $oldPck['version'];
		$newVersion = $package['version'];
		# Compare packages state
		if (!$oldVersion) {
			# Not installed
			$state = self::NOT_INSTALLED;
		}
		else if ($oldVersion != $newVersion) {
			# Upgrading package
			$state = self::UPGRADE;
		}
		else {
			# Already installed, same version, no changes
			$state = self::INSTALLED;
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function packageInstalled($id) {
		# Get current package
		$package =& $this->deDocPackages[$this->key()];
		# Hold package id for installed package
		$package['id'] = $id;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function removeOld() {
		# INitialize
		$currentKey = $this->key();
		# Remove from installed packages working queue
		unset($this->oldPackagesQueue[$currentKey]);
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function rewind() {
		# Reset
		$this->oldPackagesQueue = $this->installedPackages;
		# ArrayIterator
		return parent::rewind();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		# Save new deDoc packages into database, drop previous state
		return update_option($this->dbOptionName, $this->deDocPackages);
	}
	
} # End class