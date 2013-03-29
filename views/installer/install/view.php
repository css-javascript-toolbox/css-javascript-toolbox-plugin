<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstallerInstallView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var Exception
	*/
	protected $error;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installedDbVersion;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installedDbVersionId;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $operations;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $securityToken;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $upgrade;
	
	/**
	* put your comment there...
	* 
	* @param mixed $info
	* @return CJTInstallerInstallView
	*/
	public function __construct($info) {
		parent::__construct($info);
		// Link scripts & styles.
		self::enququeScripts();
		self::enququeStyles();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		$model = $this->getModel('installer');
		// Initialize templates vars!
		try {
			$this->installedDbVersion = $model->getInstalledDbVersion();
			$this->installedDbVersionId = $model->getInternalVersionName();
			$this->securityToken = cssJSToolbox::getSecurityToken();
			$this->operations = $model->getOperations();
			$this->upgrade = $model->isUpgrade();
		}
		catch (Exception $exception) {
			$this->error = $exception;
		}
		// Display view!
		echo $this->getTemplate($tpl);
	}

	/**
	*
	* 
	* @return void
	*/
	public function enququeScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'thickbox',
			'views:installer:install:public:js:{CJTInstaller-}default',
			'framework:js:ajax:{CJT-}cjt-server',
			'framework:js:{CJTFrameworkInstaller-}installer'
		);
	}
	
	/**
	*
	* 
	* @return void
	*/
	public function enququeStyles() {
		// Use related styles.
		self::useStyles(__CLASS__, 
			'thickbox',
			'views:installer:install:public:css:{CJTInstaller-}default'
		);
	}
	
} // End class.