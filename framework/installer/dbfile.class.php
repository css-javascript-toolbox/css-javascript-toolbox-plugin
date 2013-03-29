<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTDBFileInstaller {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $file;
	
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
	protected $statements;
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	* @param mixed $name
	* @return CJTDBFileInstaller
	*/
	public function __construct($file, $name= null) {
		// Get content of the file!
		$this->file = is_file($file) ? file_get_contents($file) : $file;
		$this->name = $name;
		// Parse DB file ststements!
		$this->parse();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function exec() {
		// Initialize!
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		// Execute all statements!
		foreach ($this->statements as $statement) {
			// Terminate the statement with ;
			$statement = "{$statement};";
			// Execute statement!
			$driver->exec($statement);
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	* @param mixed $name
	*/
	public static function getInstance($file, $name = null) {
		return new CJTDBFileInstaller($file, $name);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function parse() {
		// Initialize!
		$statementEndChar = ';';
		// SIMPLY! get statements!
		$this->statements = explode($statementEndChar, $this->file);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function &statements() {
		return $this->statements;	
	}
	
} // End class.
