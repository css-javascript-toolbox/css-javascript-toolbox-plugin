<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesDashboardPageService {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $config;
	
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
	private $controllerFactory;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $dashboardsConfig;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $menuFilterName = 'admin_menu';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $menus;

	/**
	* put your comment there...
	* 
	*/
	public function __construct() {}

	/**
	* put your comment there...
	* 
	*/
	public function _adminMenuHook() {
		# Define menus
		$callback = array( $this, '_display' );
		$this->defineMenus( $callback );
		# Bind to menus load event and map it to for load callback
		foreach ( $this->menus as $hookSlug => $menu ) {
			#  Bind load event
			add_action( "load-{$hookSlug}", array( & $this, '_pageLoad' ) );
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function _display() {
		
		# Save state
		$this->controller->saveState();
		
		# Output
		echo $this->controller->getResponse();
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function _pageLoad() {
		# Get current hook slug
		$hookSlug = substr( current_filter(), strlen( 'load-' ) ) ;
		# Get menu associated to current request hook slug
		$menu =& $this->menus[ $hookSlug ];
		$menuConfig =& $menu[ 'config' ];
		# Get request route
		$router = new CJTServicesHTTPRequestRouter( $this->config[ 'requestParams' ] );
		$router->getRoute( $menuConfig[ 'route' ] );
		# Get controller
		$this->controller =& $this->getControllerFactory()->getController( array_merge( $this->config, $menuConfig ) );
		# Dispatch call
		$this->controller->dispatch( $menuConfig[ 'route' ][ 'action' ] );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $slug
	* @param mixed $hookSlug
	* @return CJTServicesDashboardPageService
	*/
	protected function addMenu($slug, $hookSlug) {
		# Add menu and hold its configuration reference
		$this->menus[ $hookSlug ] = array( 'slug' => $slug, 'config' => & $this->dashboardsConfig[ $slug ] );
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $dispatchController
	* @param mixed $config
	* @param mixed $dashboardConfigs
	* @return CJTServicesDashboardPageService
	*/
	public function & attach(& $controllerFactory, $config, $dashboardConfigs) {
		# Set
		$this->controllerFactory =& $controllerFactory;
		$this->config =& $config;
		$this->dashboardsConfig =& $dashboardConfigs;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected abstract function defineMenus(& $callback);

	/**
	* put your comment there...
	* 
	*/
	public function getDashboardsConfig() {
		return $this->dashboardsConfig;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getControllerFactory() {
		return $this->controllerFactory;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & start() {
		# Define menus when admin menu hooks is fired
		add_action( $this->menuFilterName, array( $this, '_adminMenuHook' ) );
		# Chain
		return $this;
	}

}