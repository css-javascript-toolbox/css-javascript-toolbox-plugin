<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Lib_Template {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $source;
	
	/**
	* put your comment there...
	* 
	* @param mixed $source
	*/
	public function __construct($source) {
		$this->source = $source;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function useTheme($name) {
		// Get template name from theme name.
		// Template name is [BLOCKNAME-THEMENAME-theme].
		$templateName = "{$this->source->name} - {$name} theme";
		// Load template record from database.
		$tblTemplate = CJTxTable::getInstance('template')
									 											->setData(array('name' => $templateName))
									 											->load(array('name'));
		$mdlTemplate = CJTModel::getInstance('template');
		$mdlTemplate->inputs['id'] = $tblTemplate->get('id');
		$template = $mdlTemplate->getItem();
		// Link Style sheet.
		$queueObject = CJTModel::getInstance('coupling')
																						 ->getQueueObject('styles');
		$queueObject->add($template->queueName, "/{$template->file}");
		$queueObject->enqueue($template->queueName);
		// Chaining.
		return $this;
	}

} // End class.