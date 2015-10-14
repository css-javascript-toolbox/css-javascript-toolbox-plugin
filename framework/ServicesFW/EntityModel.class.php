<?php
/**
* 
*/

/**
* 
*/
abstract class CJTServicesEntityModel {
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	* @return CJTServicesItemModel
	*/
	public function __construct($data = null) {
		# Make sure data is array
		if( ! $data ) {
			$data = array();
		}
		# Fill with data
		$this->exchangeArray( $data );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function exchangeArray($data) {
		# Fetch properties for all model members
		foreach ( get_object_vars( $this ) as $name => $value ) {
			$this->$name = isset( $data[ $name ] ) ? $data[ $name ] : null;
		}
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getArray() {
		# Gett all vars
		$vars = get_object_vars( $this );
		# Exclude NULL values
		foreach ( $vars as $name => $value ) {
			if ( $value === null ) {
				unset( $vars[ $name ] );
			}
		}
		return $vars;
	}

	/**
	* put your comment there...
	* 
	* @param CJTServicesEntityModel $model
	* @return CJTServicesEntityModel
	*/
	public function isEqual(CJTServicesEntityModel & $model) {
		return md5( print_r( get_object_vars( $this ), true ) ) == md5( print_r( get_object_vars( $model ), true ) );
	}
}
