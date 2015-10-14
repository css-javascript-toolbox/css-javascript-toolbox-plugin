<?php
/***
* 
*/

/**
* 
*/
class CJTServicesMVCViewJson 
{
	
	/**
	* put your comment there...
	* 
	*/
	public function dispatch() {}
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString()
	{
		
		$data = get_object_vars( $this );
		
		return json_encode( $data );
	}

}
