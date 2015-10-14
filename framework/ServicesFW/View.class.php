<?php
/**
* 
*/

/**
* 
*/
class CJTServicesMVCView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $controller;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $path;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $scripts = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $styles = array();

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $output;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $url;
	
	/**.
	* put your comment there...
	* 
	* @param CJTServicesMVCController $controller
	* @return {CJTServicesView|CJTServicesMVCController}
	*/
	public function __construct(CJTServicesMVCController & $controller) {
		# Initialize
		$this->controller =& $controller;
		# Enqueue styles and script hooks
		if( is_admin() ) {
			add_action( 'admin_print_styles', array( $this, '_enqueueStyles' ) );
			add_action( 'admin_print_scripts', array( $this, '_enqueueScripts' ) );
		}
		# Cache View Path and Urls
		$config = $this->controller->getConfig();
		$route = $this->controller->getRoute();	
		$viewConfig = $config[ 'views' ][ $route[ 'view' ] ];
		$this->path = $config[ 'path' ] . DIRECTORY_SEPARATOR . 
											'Views' . DIRECTORY_SEPARATOR .  
											str_replace( '/', DIRECTORY_SEPARATOR, $viewConfig[ 'path' ] );		
		$this->url = "{$config[ 'url' ]}/Views/{$viewConfig[ 'path' ]}/";
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		# Render
		return $this->output;
	}

	/**
	* put your comment there...
	* 
	*/
	public function _enqueueScripts() {
		foreach ( $this->scripts as $script ) {
			wp_enqueue_script( 	$script[ 'handle' ], 
													$script[ 'src' ], 
													$script[ 'dep' ], 
													$script[ 'version' ], 
													$script[ 'footer' ] 
													);
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function _enqueueStyles() {
		foreach ( $this->styles as $style ) {
			wp_enqueue_style( 	$style[ 'handle' ], 
													$style[ 'src' ], 
													$style[ 'dep' ], 
													$style[ 'version' ], 
													$style[ 'media' ] 
													);
		}
	}

	/***
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $url
	* @param mixed $version
	* @param mixed $footer
	*/
	public function enqueueScript($handle, $src = null, $dep = null, $version = null, $footer = null) {
		# Add style
		$this->scripts[] = compact( 'handle', 'src', 'dep', 'version', 'footer' );
		# Chain
		return $this;
	}

	/***
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $url
	* @param mixed $version
	* @param mixed $media
	*/
	public function enqueueStyle($handle, $src = null, $dep = null, $version = null, $media = null) {
		# Add style
		$this->styles[] = compact( 'handle', 'src', 'dep', 'version', 'media' );
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function dispatch() {
		# Get early output so its possible to use Wordpress hooks before they
		# are elapced
		$route = $this->controller->getRoute();
		$this->output = $this->getTemplate( isset( $route[ 'template' ] ) ? $route[ 'template' ] : $route[ 'action' ] );
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getPath() {
		return $this->path;
	}
	
	/**
	* 
	* 
	* @param mixed $name
	*/
	public function getResName($name) {
		return strtolower( get_class( $this ) ) . '-' . strtolower( $name );
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getResUrl($name) {
		return "{$this->url}/{$name}";
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getScriptUrl($name) {
		return $this->getResUrl( "{$name}.js" );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getStyleUrl($name) {
		return $this->getResUrl( "{$name}.css" );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	protected function getTemplate($name) {
		# Get view path
		$config = $this->controller->getConfig();
		$viewPath = $this->getPath();
		# Template Extension/Format
		$extension = isset( $viewConfig[ 'format' ] ) ? $viewConfig[ 'format' ] : $config[ 'defaultFormat' ];
		# Template file
		$templateFile = $viewPath . DIRECTORY_SEPARATOR . "{$name}.{$extension}.php";
		# Execute tempate file
		ob_start();
		require $templateFile;
		$result = ob_get_clean();
		# Returns
		return $result;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function messagesList() {
		ob_start();
		require __DIR__ . DIRECTORY_SEPARATOR . 'ViewTemplates' . DIRECTORY_SEPARATOR . 'MessagesList.html';
		return ob_get_clean();
	}

}