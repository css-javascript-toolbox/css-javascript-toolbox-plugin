<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesModel {
	
	/**
	* 
	*/
	const MESSAGE_ERROR = 'error';
	
	/**
	* 
	*/
	const MESSAGE_NOTICE = 'notice';
	
	/**
	* 
	*/
	const MESSAGE_WARNING = 'warning';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $_storage;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $messsages = array();
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		# Create Model Storage
		$this->_storage = new CJTServicesStorage( get_class( $this ), array() );
		# Load state
		$this->readState();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function clearMessages() {
		# Clear
		$this->messsages = array();
		# CHain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getStorage() {
		return $this->_storage;
	}

	/**
	* put your comment there...
	* 
	*/
	public function pullMessages() {
		# Get messages copy
		$messages = $this->messsages;
		# CLean messages
		$this->clearMessages();
		# Return copied messages
		return $messages;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function readState() {
		# Read database state
		$values = $this->getStorage()->getValue();
		# For every found first lever property set object variable
		foreach( $values as $name => $value ) {
			# Set object property value
			$this->$name = $value;
		}
	}

	/**
	* put your comment there...
	* 
	* @param mixed $message
	*/
	public function & setInformation($message) {
		return $this->setMessage( self::MESSAGE_NOTICE, $message );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $message
	*/
	public function & setError($message) {
		return $this->setMessage( self::MESSAGE_ERROR, $message );
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $message
	*/
	public function & setMessage($type, $message) {
		# Queue message
		$this->messsages[ $type ][] = $message;
		
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $message
	*/
	public function & setNotice($message) {
		return $this->setMessage( self::MESSAGE_NOTICE, $message );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $message
	*/
	public function & setWarning($message) {
		return $this->setMessage( self::MESSAGE_WARNING, $message );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $location
	*/
	public function redirect($location) {
		# Redirect
		wp_redirect( $location );
		# Save state
		$this->saveState();
		# Temrinate
		die();
	}

	/**
	* put your comment there...
	* 
	*/
	public function & saveState() {
		# Initialize
		$values = array();
		# Save all not underscored vars value to database storage
		$props = get_object_vars( $this );
		foreach( $props as $name => $value ) {
			# Save variable if there is no _ at the begining of the string
			if ( strpos( $name, '_' ) !== 0 ) {
				$values[ $name ] = $value;
			}
		}
		# Set new value and save to database
		$this->getStorage()->setValue( $values )->update();
		# Chaining
		return $this;
	}

}
