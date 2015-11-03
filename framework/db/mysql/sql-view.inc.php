<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

/**
* No direct access.
*/
// No Direct Accesss code

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
abstract class CJTSQLView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $driver = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $query = array(
		'columns' => array('*'),
		'filter' => array(),
		'orderBy' => array(),
		'limits' => array(),
	);
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTPinsBlockView
	*/
	public function __construct($driver) {
		$this->driver = $driver;
		// Initialize locals.
		$this->query = (object) $this->query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	abstract public function __toString();
	
	
	/**
	* put your comment there...
	* 
	* @param mixed $from
	* @param mixed $filter
	*/
	protected function buildQuery($from, $filter) 
	{
		
		$query = $this->query;
		
		$sql = array
		( 
			'columns' => null, 
			'from' => null, 
			'filter' => null, 
			'orderBy' => null, 
			'limits' => null 
		);
		
		$sql[ 'columns' ] = implode( ', ', $query->columns );
		
		// From.
		$sql[ 'from' ] = " FROM {$from}";
		
		// Where clause.
		if ( ! empty( $filter ) ) 
		{
			$sql[ 'filter' ] = " WHERE {$filter}";
		}
		
		// Order By.
		if ( ! empty( $query->orderBy ) ) 
		{
			$sql[ 'orderBy' ] = ' ORDER BY ' . implode( ',', $order );
		}
		
		// Limits.
		if ( ! empty( $query->limits ) ) 
		{
			$sql[ 'limits' ] = " LIMIT {$limits[0]}" . ( isset( $limits[ 1 ] ) ? ",{$limits}" : '' );
		}
		
		// Buile query
		$sql = "SELECT {$sql['columns']}\n\n{$sql['from']}\n\n{$sql['filter']}{$sql['orderBy']}{$sql['limits']};";
		
		return $sql;
	}
	
	/**
	* put your comment there...
	* 
	*/
	abstract public function exec();
	
	/**
	* put your comment there...
	* 
	*/
	public function &getQueryObject() {
		return $this->query;	
	}
	
} // End class.