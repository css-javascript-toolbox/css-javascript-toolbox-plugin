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
		# Getting XML content
		$xmlContent = ((gettype($feed) !== 'WP_Error') && ($feed['response']['code'] == 200)) ?
									wp_remote_retrieve_body($feed) :
									'<cjterrorrequest>
										<channel cjt_error="true">
											<item>
												<title>ERROR</title>
												<description>ERROR</description>
												<link>http://css-javascript-toolbox.com/</link>
											</item>
										</channel>
									</cjterrorrequest>';
		# Creating feed
		$this->feed = new SimpleXMLElement($xmlContent);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getAllItems() {
		// Initialize.
		$items = array();
		$xmlItems = $this->feed->channel->xpath('item');
		// Read only items count specifed by $count param.
		foreach ($xmlItems as $xmlItem) {
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
	* @param mixed $count
	*/
	public function getLatestItems($count) {
		# Initialize.
		$items = array();
		$xmlItems = $this->feed->channel->xpath('item');
		# Get all available items if the total items count is less that what is originally requested
		if (count($xmlItems) < $count) {
			$count = count($xmlItems);
		}
		# Read only items count specifed by $count param.
		for ($currentIndex = 0; $currentIndex < $count; $currentIndex++) {
			# Copy only title and link.
			$xmlItem = $xmlItems[$currentIndex];
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
		return !$this->feed || $this->feed->channel->attributes()->cjt_error;
	}

} // End class.
