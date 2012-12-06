<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJTWPEE extends CJTEE {
	
	/**
	* 
	*/
	const WPEE_HOOK_ACTION = 'action';

	/**
	* 
	*/
	const WPEE_HOOK_CUSTOM = 'custom';
	
	/**
	* 
	*/
	const WPEE_HOOK_FILTER = 'filter';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $hookType;
	
		/**
	* Trigger event
	* 
	* @param mixed $type
	* @param mixed $params
	*/
	public function __call($type, $params) {
		$result = false;
		// Get event type subject!
		$subject = $this->__get($type);
		// Notify observers!
		if ($subject) {
			$result = call_user_func(array(&$subject, 'callIndirect'), $params);
		}
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $prefix
	* @return CJTWPEE
	*/
	protected function __construct($hookType = self::WPEE_HOOK_ACTION, $attributes = self::ATTR_EXPLICIT_EVENT, $subjectDefaults = array(), $prefix = self::EVENT_PREFIX) {
		$this->hookType = $hookType;
		// Initialize parent!
		parent::__construct($attributes, $subjectDefaults, $prefix);
	}
	 
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	protected function prepareTypeDefinition($type) {
		// Get hook type for the event type!
		$typeDefinition = parent::prepareTypeDefinition($type);
		$hookType = isset($typeDefinition['type']) ? $typeDefinition['type'] : $this->hookType;
		// Get classes + files definition based on the hook type!
		switch ($hookType) {
			case self::WPEE_HOOK_ACTION:
				$typeDefinition['class'] = 'CJTEEWordpressHookAction';
				$typeDefinition['file'] = 'action.subject.php';
				$typeDefinition['observerClass'] = 'CJTWordpressActionHookObserver';
				$typeDefinition['observerFile'] = 'wordpress-hook-action.observer.php';
			break;
			case self::WPEE_HOOK_FILTER:
				$typeDefinition['class'] = 'CJTEEWordpressHookFilter';
				$typeDefinition['file'] = 'filter.subject.php';
				$typeDefinition['observerClass'] = 'CJTWordpressFilterHookObserver';
				$typeDefinition['observerFile'] = 'wordpress-hook-filter.observer.php';
			break;
		}
		return $typeDefinition;
	}
	
} // End class.
