<?php
/**
* 
*/

// No direct access allowed.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class HTMLComponent {
	
	/**
	* 
	*/
	const DEFAULT_TEMPLATE_DIR = 'tmpl';
	
	/**
	* 
	*/
	const DEFAULT_TEMPLATE_EXTENSION = 'html.tmpl';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $componentFile = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $templatesDir = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $templateFileExtension = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	* @return HTMLComponent
	*/
	protected function __construct($file, $templatesDir = self::DEFAULT_TEMPLATE_DIR, $templateFileExtension = self::DEFAULT_TEMPLATE_EXTENSION) {
		// Intialize object vars.
		$this->componentFile = $file;
		$this->templatesDir = $templatesDir;
		$this->templateFileExtension = $templateFileExtension;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public abstract function __toString();
	
	/**
	* put your comment there...
	* 
	* @param mixed $basePath
	* @param mixed $baseURI
	* @param mixed $path
	*/
	public function getURI($basePath, $baseURI, $path = '') {
		// Build path to the component file.
		$pathToComponent = str_replace($basePath, '', dirname($this->componentFile));
		// Build component resource URI.
		$resourceURI = "{$baseURI}{$pathToComponent}/public/{$path}";
		return $resourceURI;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getTemplate($name) {
		$templatePath = dirname($this->componentFile);
		// Get content into alternate output buffer.
		ob_start();
		// Import template file.
		require "{$templatePath}/{$this->templatesDir}/{$name}.{$this->templateFileExtension}";
		// Return content.
		return ob_get_clean();
	}
	
} // End class.