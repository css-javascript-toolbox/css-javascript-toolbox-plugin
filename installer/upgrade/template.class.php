<?php
/**
* 
*/

/**
* 
*/
class CJTInstallerTemplate extends ArrayIterator {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $model;
	
	/**
	* put your comment there...
	* 
	* @param mixed $templates
	* @return CJTInstallerTemplate
	*/
	public function __construct($templates) {
		// Initialize!
		$this->model = CJTModel::getInstance('template');
		// Initialize Iterator!
		parent::__construct(is_array($templates) ? $templates : array());
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		// Initiaize!
		$template = $this->current();
		$item = array();
		// Revision data!
		$revision['code'] = $template['code'];
		$revision['changeLog'] = 'Just created...';
		$revision['state'] = 'release';
		// Template data!
		unset($template['code']);
		// js type name changed to javascript!
		if ($template['type'] == 'js') {
			$template['type'] = 'javascript';
		}
		// Build template-model template item sturture!
		$item['template'] = $template;
		$item['revision'] = $revision;
		// Save template  into db!
		$this->model->inputs['item'] = $item;
		$this->model->save();
	}
	
} // End class.
