<?php
/**
* 
*/


/**
* 
*/
class CJTServicesForm 
{

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $data = array();

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $definition = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $data
	* @return CJTServicesForm
	*/
	public function __construct( $name, $data = null )
	{
		
		$this->name = $name;
		
		if  ( is_array( $data ) )
		{
			$this->setArray( $data );	
		}
		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	private function _type_array( $value )
	{
		if ( ! is_array( $value ) )
		{
			$value = array();
		}
		
		return $value;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	private function _type_boolean( $value )
	{
		return ( bool ) $value;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	private function _type_float( $value )
	{
		return ( float ) $value;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	private function _type_integer( $value )
	{
		return intval( $value );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	private function _type_string( $value )
	{
		return strval( $value );
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function apply()
	{

		$definitionsGroups = array_chunk( $this->definition, 2 );
		
		foreach ( $definitionsGroups as $groupDef )
		{
			
			$groupDefName = $groupDef[ 0 ];
			
			foreach ( $groupDef[ 1 ] as $name => $definition )
			{

				# Set containers/groups if not set
				$groupNames = explode( '.', $groupDefName );
				
				
				$dataCopy = $this->data;
				$pointer =& $dataCopy;
				
				foreach ( $groupNames as $groupName )
				{
					# For now lets use array for any doesnt exists container
					if ( ! isset( $pointer[ $groupName ] ) )
					{
						$pointer[ $groupName ] = array();
					}
					
					$pointer =& $pointer[ $groupName ];
					
				}

				# Set cast value
				$typeHandler = "_type_{$definition[ 'type' ]}";
				$value = $this->getValue( "{$groupDefName}.{$name}" );
				
				$pointer[ $name ] = $this->$typeHandler( $value );

				$this->data = $dataCopy;
			}
			
		}
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $definition
	*/
	public function & define( $definitions )
	{
		
		$this->definition = func_get_args();

		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getArray()
	{
		return $this->data;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	protected function & getElement( $path )
	{
		$pointer =& $this->data;
		
		$names = explode( '.', $path );
		
		foreach ( $names as $name )
		{
			
			if ( ! isset( $pointer[ $name ] ) )
			{
				$pointer = null;
				
				return $pointer;
			}
			
			$pointer =& $pointer[ $name ];
		}
		
		return $pointer;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function getElementId( $path )
	{
		
		$elementId = "{$this->name}-" . str_replace( '.', '-', $path );
		
		return $elementId;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function getElementName( $path )
	{
		
		$elementName = "{$this->name}[" . str_replace( '.', '][', $path ) . ']';
		
		return $elementName;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName()
	{
		return $this->name;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function getValue( $path )
	{
  	return $this->getElement( $path );
	}

	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function & setArray( $data )
	{
		
		$this->data = $data;
		
		# Apply definition ( e.g cast values if needed )
		$this->apply();
		
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $path
	* @param mixed $data
	*/
	public function & setValue( $path, $value )
	{
		
		$element =& $this->getElement( $path );
		
		$element = $value;
		
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & validate()
	{
		return true;
	}

}
