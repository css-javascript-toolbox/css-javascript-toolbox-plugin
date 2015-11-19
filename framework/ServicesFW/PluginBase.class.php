<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesPluginBase implements CJTServicesIPluginBase 
{

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
	private $dir;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $file;
	
	/**	
	* put your comment there...
	* 
	* @var mixed
	*/
	private $services;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $url;
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	* @param mixed $config
	*/
	protected function __construct( $file, $config )
	{
		
		$this->file = $file;
		$this->dir = dirname( $file );
		$this->url = plugin_dir_url( $file );
		$this->config = $config;

	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $item
	*/
	public function getConfig( $configNames ) 
	{
		
		$configs = array();
		
		# Each passed parameter is configuration to be merged to former one
		foreach ( func_get_args() as $configName ) 
		{
			# Generaly target config point to all configs
			# below loop is to point to the correct item
			$targetConfig =& $this->config;
			
			# Explode name and get array element recusively
			foreach ( explode( '.', $configName ) as $itemName )  
			{
				
				if ( ! isset( $targetConfig[ $itemName ] ) ) 
				{
					throw new Exception( "Config: {$itemName} doesn't exists in {$configName}!!!" );
				}
				
				# Go deeper as requested
				$targetConfig =& $targetConfig[ $itemName ];
			}
			
			$configs = array_merge( $configs, $targetConfig );
		}
		
		return $configs;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $serviceConfig
	*/
	public function & getController( $serviceConfig ) 
	{
		
		return CJTServicesMVCController::getInstance( $serviceConfig, $serviceConfig[ 'route' ] );
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getDir()
	{
		return $this->dir;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getFile()
	{
		return $this->file;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName()
	{
		return basename( $this->dir );
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getUrl()
	{
		return $this->url;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $list
	*/
	protected function & loadServices( $list ) 
	{
		
		foreach ( func_get_args() as $service ) 
		{
			
			$this->services[ get_class( $service ) ] =& $service;
			
			# Start
			$service->start();
		}
		
		return $this;
	}
	
}