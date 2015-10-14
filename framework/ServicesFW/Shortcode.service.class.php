<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesShortcodeService extends CJTServicesWPService {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $callback;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $shortcodes = array();

	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		
		$this->callback = array( $this, '_doShortcode' );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $attrs
	* @param mixed $content
	* @param mixed $tag
	*/
	public function _doShortcode($attrs, $content, $tag) {
		
		# Get requested Shortcode config
		$shortcode =& $this->shortcodes[ $tag ];
		$shortcodeConfig =& $shortcode[ 'config' ];
		
		# Push Shortcode parameters
		$shortcodeConfig['parameters'] = array( 'attrs' => & $attrs, 'content' => & $content, 'tag' => & $tag );
		
		# Get Controller
		$this->controller =& $this->getControllerFactory()->getController( array_merge( $this->config, $shortcodeConfig ) );
		
		# Dispatch action
		$this->controller->dispatch( $shortcodeConfig[ 'route' ][ 'action' ] );
		
		# Save models state
		$this->controller->saveState();
		
		# Return shortcoee replacement
		return $this->controller->getResponse();		
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & addShortcode($name) {
		
		# Create shortcode structure, add it to list
		$this->shortcodes[ $name ] = array( 'name' => $name, 'config' => & $this->serviceConfig[ $name ] );
		
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	protected abstract function defineShortcodes();
	
	/**
	* put your comment there...
	* 
	*/
	public function & start() {
		
		# Let Model class define Shortcodes
		$this->defineShortcodes();
		
		# Add shortcodes at the correct hook
		foreach ( $this->shortcodes as $shortcode ) {
			
			# Register Wordpress shortcode
			add_shortcode( $shortcode[ 'name' ], $this->callback );
			
		}
		
		return $this;
	}
	
}
