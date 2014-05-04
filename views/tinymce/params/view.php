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
	* @var mixed
	*/
	protected $groups = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $packageInfo = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $viewInfo
	* @return CJTTinymceParamsView
	*/
	public function __construct($viewInfo, $params)  {
		// Parent procedure!
		parent::__construct($viewInfo, $params);
		// Prepare groups array.
		foreach ($params['groups'] as $group) {
			// Initialize group info array.
			$group['params'] = array();
			// Group Key.
			$group['key'] = strtolower(str_replace(array(' '), '-', $group['name']));
			// Get group data cxopy.
			$this->groups[$group['id']] = $group;
		}
		// Put the Group woth the loest ID first, 
		// Display in the same order they're added!
		ksort($this->groups);
		// Prepare groups from the passed parameters.
		foreach ($params['params'] as $param) {
			// Add parameter under its group!
			$this->groups[$param->getDefinition()->getGroupId()]['params'][] = $param;
		}
		// Instantiate grouper.
		// Only tab grouper is supported for now!
		$grouperFactory = new CJT_Framework_View_Block_Parameter_Grouper_Factory();
		$this->grouper = $grouperFactory->create(
			$params['form']->groupType, 
			$this->groups
		);
	}

	/**
	* Output Javascript files requirred to Add-New-Block view to run.
	* 
	* @return void
	*/
	public function enqueueScripts() {
		// Scripts required by the form to run.
		$scripts = array(
			'jquery',
			'jquery-serialize-object',
			'views:tinymce:params:public:js:{CJT_TINYMCE_PARAMS-}form',
			'framework:js:misc:{CJT-}simple-error-dialog'		
		);
		// Scripts required by the grouper.
		$scripts = array_merge($scripts, $this->getGrouper()->enqueueScripts());
		// Use related scripts.
		self::useScripts(__CLASS__, $scripts);
	}
	
	/**
	* Output CSS files required to Add-New-Block view.
	* 
	* @return void
	*/
	public function enqueueStyles() {
		// Styles required by the params form.
		$styles = array(
			'framework:css:{CJT-}forms',
			'framework:css:{CJT-}error-dialog',
			'views:tinymce:params:public:css:{CJT_TINY_MCE_PARAMS_FORM-}style'
		);
		// Groupe styles.
		$styles = array_merge($styles, $this->getGrouper()->enqueueStyles());
		// Use related styles.
		self::useStyles(__CLASS__, $styles);
	}

	/**
	* put your comment there...
	* 
	*/
	public function getGrouper() {
		return $this->grouper;
	}

} // End class.
