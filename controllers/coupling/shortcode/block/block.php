<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Handle block Shortcode.
*/
class CJT_Controllers_Coupling_Shortcode_Block extends CJTHookableClass {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $attributes;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $options = array('force' => 'false');
	
	/**
	* put your comment there...
	* 
	* @param mixed $attributes
	* @return CJT_Controllers_Coupling_Shortcode_Block
	*/
	public function __construct($attributes) {
		// Hookable initiaization.
		parent::__construct();
		// Initialize.
		$this->attributes = $attributes;
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Initialize.
		$replacement = '';
		$model = CJTModel::getInstance('coupling');
		// Get shortcode options.
		$this->options = array_merge($this->options, array_intersect_key($this->attributes, $this->options));
		// Get Block fields to be used to query the block.
		$blockQueryFields = array_diff_key($this->attributes, $this->options);
		$coupling =& CJTBlocksCouplingController::theInstance();
		// Import dependecies.
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php', 'framework:php:evaluator:evaluator.inc.php');
		// Query block.
		$block = CJTxTable::getInstance('block')
																	->setData($blockQueryFields) // Set Creteria fields!
																	->load(array_keys($blockQueryFields)); // Load using Creteria fields.
		// Get block code if exists and is active block.
		if ($block->get('id')) {
			if ($block->get('state') == 'active') {
				// Get stdCLass copy.
				$block = $block->getData();
				// Output block if 'force="true" or only if it wasn't already in the header/footer!
				if (($this->options['force'] == 'true') || !in_array($block->id, $coupling->getOnActionIds())) {
					// Id is being used!
					if ($this->options['force'] != 'true') {
						$coupling->addOnActionIds((int) $block->id);
					}
					// Import Executable (PHP and HTML) templates.
					$block->code = $block->code . $model->getExecTemplatesCode($block->id);
					// Get block code, execute it as PHP!
					$replacement = CJTPHPCodeEvaluator::getInstance($block)->exec()->getOutput();
					// Get linked templates.
					$linkedStylesheets = '';
					$templates = $model->getLinkedTemplates($block->id);
					$reverseTypes = array_flip(CJTCouplingModel::$templateTypes);
					// Enqueue all scripts & Direct Output for all Style Sheets!
					foreach ($templates as $template) {
						// Enqueue Javascripts.
						if ($template->type == 'javascript') {
							// Get Template type name.
							$typeName = $reverseTypes[$template->type];
							/**
							* @var WP_Dependencies
							*/
							$queue = $model->getQueueObject($typeName);
							if (!in_array($template->queueName, $queue->done)) {
								if (!isset($queue->registered[$template->queueName])) {
									$queue->add($template->queueName, "/{$template->file}", null, $template->version, 1);
								}
								// Enqueue template!
								$queue->enqueue($template->queueName);
							}
						}
						// Concat all linked style sheet to be returned along with the replacment.
						else {
							// Get Template object important in order to read the code from revision file.
							if (!isset($templateModel)) {
								$templateModel = CJTModel::getInstance('template');	
							}
							$templateModel->inputs['id'] = $template->id;
							$template = $templateModel->getItem();
							// Concat!
							$linkedStylesheets .= $template->code;
						}
					}
					// Prepend linked Stylesheets to the replacement.
					if (isset($linkedStylesheets)) {
						$replacement = "<style type='text/css'>{$linkedStylesheets}</style>{$replacement}";
					}
				}
			}
		}
		// Return shortcode replacement string.
		return $replacement;
	}

} // End class.

// Hookable!
CJT_Controllers_Coupling_Shortcode_Block::define('CJT_Controllers_Coupling_Shortcode_Block');