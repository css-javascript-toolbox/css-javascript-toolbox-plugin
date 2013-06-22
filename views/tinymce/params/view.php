<?php
/**
* 
*/

/**
* 
*/
class CJTTinymceParamsView extends CJTView {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $grouper = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $viewInfo
	* @return CJTTinymceParamsView
	*/
	public function __construct($viewInfo, $params)  {
		// Parent procedure!
		parent::__construct($viewInfo, $params);
		// Instantiate grouper.
		// Only tab grouper is supported for now!
		$grouperFactory = new CJT_Framework_View_Block_Parameter_Grouper_Factory();
		$this->grouper = $grouperFactory->create(
			$params['form']->groupType, 
			$params['params']
		);
		// Scripts and Styles.
		self::enqueueScripts();
		self::enqueueStyles();
	}

	/**
	* Output Javascript files requirred to Add-New-Block view to run.
	* 
	* @return void
	*/
	public function enqueueScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__, 
			'jquery',
			'jquery-serialize-object',
			'views:tinymce:params:public:js:{CJT_TINYMCE_PARAMS-}form',
			'framework:js:misc:{CJT-}simple-error-dialog'
		);
	}
	
	/**
	* Output CSS files required to Add-New-Block view.
	* 
	* @return void
	*/
	public function enqueueStyles() {
		// Use related styles.
		self::useStyles(__CLASS__,
			'framework:css:{CJT-}forms',
			'framework:css:{CJT-}error-dialog',
			'views:tinymce:params:public:css:{CJT_TINY_MCE_PARAMS_FORM-}style'
		);
	}

} // End class.
