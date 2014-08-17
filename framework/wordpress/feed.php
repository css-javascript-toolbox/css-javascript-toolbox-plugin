<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Wordpress_Feed {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $feed;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $fields;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $path;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $site;
	
	/**
	* put your comment there...
	* 
	* @param mixed $site
	* @param mixed $path
	* @return CJT_Framework_Wordpress_Feed
	*/
	public function __construct($site, $path, $fields) {
		# Initialize.
		$this->site =& $site;
		$this->path =& $path;
		$this->fields = $fields;
		# Request server => get raw XML feed
		$feed = wp_remote_get("http://{$this->site}/{$this->path}");
		if (gettype($feed) !== 'WP_Error') {
			$this->feed = new SimpleXMLElement(wp_remote_retrieve_body($feed));
		}
	}

	/**
	* put your comment there...
	* 
	* @param mixed $count
	*/
	public function getLatestItems($count) {
		// Initialize.
		$items = array();
		// Read only items count specifed by $count param.
		for ($currentIndex = 0; $currentIndex < $count; $currentIndex++) {
			# Copy only title and link.
			$xmlItem = $this->feed->channel->item[$currentIndex];
			# Read fields.
			$item = array();
			foreach ($this->fields as $field) {
				$item[$field] = (string) $xmlItem->$field;
			}
			# Add to list
			$items[] = $item;
		}
		# Return items.
		return $items;		
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getPath() {
		return $this->path;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getSite() {
		return $this->site;
	}

	/**
	* put your comment there...
	* 
	*/
	public function isError() {
		return !$this->feed;
	}

} // End class.
