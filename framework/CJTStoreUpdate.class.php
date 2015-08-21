<?php
/**
* 
*/

/**
* 
*/
class CJTStoreUpdate {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $store;

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $license
	* @param mixed $pluginFile
	* @return CJTStoreUpdate
	*/
	public function __construct($name, $license, $pluginFile) {
		# Interact with store
		$this->store = new CJTStore( $name, $license, $pluginFile );
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	* @param mixed $action
	* @param mixed $args
	*/
	public function _overridePluginInformation($data, $action, $args) {
		# Act only with plugins_information action
		switch ( $action ) {
			case 'plugin_information' :
				# INitialize
				$store =& $this->getStore();
				$pluginInfo = $store->getPluginInformation();
				$pluginData = get_plugin_data( $store->getPluginFile() );
				# Make sure the requested Plugin is the one
				# associated with this object
				if ( $args && $args->slug && ( $this->getStore()->getSlug() == $args->slug ) ) {
					# Fill Plugin information data and return it back
					$data = (object) array(
						'version' => 			$pluginInfo[ 'currentVersion' ],
						'last_updated' => $pluginInfo[ 'lastUpdated' ],
						'author'  => 			$pluginData[ 'Author' ], 
						'requires' => 		$pluginInfo[ 'requires' ], 
						'tested' => 			$pluginInfo[ 'tested' ], 
						'homepage' => 		$pluginInfo[ 'url' ], 
						'downloaded' => 	$pluginInfo[ 'downloadsCount' ], 
						'slug' => 				$store->getSlug(),
						'name' => 				$pluginData[ 'Name' ],
						'sections' => array(
							'description'  => $pluginInfo[ 'description' ],
							'installation' => $pluginInfo[ 'installation' ],
							'faq'          => $pluginInfo[ 'faq' ],
							'screenshots'  => $pluginInfo[ 'screenshots' ],
							'changelog'    => $pluginInfo[ 'changeLog' ],
							'reviews'      => $pluginInfo[ 'reviews' ],
							'other_notes'  => $pluginInfo[ 'otherNotes' ]
						)
					);
				}
			break;
		}
		# Return either FALSE or plugin information if 
		# the plugin is belongs to CJT store
		return $data;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $transient
	*/
	public function _transientPluginUpdate($transient) {
		# INitialize
		$store =& $this->getStore();
		# Check For update
		$pluginBaseName = plugin_basename( $store->getPluginFile() );
		if ( $pluginUpdate = $store->hasUpdate() ) {
			# Add to update list
			$transient->response[ $pluginBaseName ] = (object) array(
				'id' => 					null,
				'plugin' => 			$pluginBaseName,
				'slug' => 				basename( $pluginBaseName, '.php' ),
				'new_version' => 	$pluginUpdate[ 'currentVersion' ],
				'url' => 					$pluginUpdate[ 'url' ],
				'package' => 			$pluginUpdate[ 'package' ],
			);
		}
		# Return transient array
		return $transient;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $itemName
	* @param mixed $license
	* @param mixed $pluginFile
	*/
	public static function & autoUpgrade($itemName, $license, $pluginFile) {
		# Get instance
		$instance = new CJTStoreUpdate( $itemName, $license, $pluginFile );
		# Start update and return $instance
		return $instance->update();
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getStore() {
		return $this->store;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & update() {
		# Hook for adding CJT Extension to UPDATE Plugins list
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, '_transientPluginUpdate' ) );
		# Hook for displaying Plugin Information form
		add_filter( 'plugins_api', array( $this, '_overridePluginInformation' ), 10, 3 );
		# Chain
		return $this;
	}
	
}