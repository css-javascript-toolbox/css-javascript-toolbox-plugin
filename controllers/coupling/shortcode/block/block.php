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
	protected $content = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $options = array('force' => 'true', 'tag' => 'span');
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parameters = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $attributes
	* @param mixed $content
	* @return CJT_Controllers_Coupling_Shortcode_Block
	*/
	public function __construct($attributes, $content) {
		// Hookable initiaization.
		parent::__construct();
		// Initialize.
		$this->attributes = $attributes;
		$this->content = $content;
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
		// Get shortcode parameters.
		$this->parameters = array_diff_key($this->attributes, array_flip(array('force', 'tag', 'name', 'id')));
		// Get Block fields to be used to query the block.
		$blockQueryFields = array_intersect_key($this->attributes, array_flip(array('id', 'name')));
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
					$coupling->addOnActionIds((int) $block->id);
					// CJT Block Standard Parameters object.
					cssJSToolbox::import('framework:developer:interface:block:shortcode:shortcode.php');
					$spi = new CJT_Framework_Developer_Interface_Block_Shortcode($block, $this->parameters, $this->content);
					// Get block code, execute it as PHP!
					$blockCode = CJTPHPCodeEvaluator::getInstance($block)->exec(array('cb' => $spi))->getOutput();
					// CJT Shortcode markup interface (CSMI)!
					// CSMI is HTML markup to identify the CJT block Shortcode replacement.
					$replacement = "<{$this->options['tag']} id='{$spi->containerElementId()}' class='csmi csmi-bid-{$block->id} csmi-{$block->name}'>{$this->content}{$blockCode}</{$this->options['tag']}>";
				}
			}
		}
		else { // Invalid Shortcode block query!
			$replacement = cssJSToolbox::getText('Could not find block specified! Please check out the Shortcode parameters.');
		}
		// Return shortcode replacement string.
		return $replacement;
	}

} // End class.

// Hookable!
CJT_Controllers_Coupling_Shortcode_Block::define('CJT_Controllers_Coupling_Shortcode_Block');