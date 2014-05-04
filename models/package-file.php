<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTPackageFileModel extends CJTHookableClass {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $state = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $property
	*/
	public function getState($property) {
		return isset($this->state[$property]) ? $this->state[$property] : null;
	}

	/**
	* put your comment there...
	* 
	* @param CJTPackageFile
	*/
	public function install($package) {
		// Initialize.
		$model = null; // Current model for the element under the loop!
		$item = array(); // Object Item array to be inserted in the database.
		$addedObjects = array('template' => array(), 'block' => array()); // All the added objects mapped by object 'TYPE'.
		// Fetch package information with all readme and license tags locatted if
		// bundled with external files!
		$packageInfo = $package->getItem(null, array('readme' => 'readme.txt', 'license' => 'license.txt'));
		// Install only if the package record if not exists.
		$modelPackage = CJTModel::getInstance('package');
		if ($modelPackage->exists($packageInfo['name'])) {
			// State error!
			$this->state['error']['msg'] = cssJSToolbox::getText('The uploaded package is already installed!');
			// Terminate!
			return $this;
		}
		// Map definition xml TYPE attribute to CJT Model object to handle addding
		// objecs types.
		$objectModels = array('template' => 'template', 'block' => 'blocks');
		// Initialize Wordpress file system object.
		WP_Filesystem();
		$fileSystem =& $GLOBALS['wp_filesystem'];
		// Read all objects defined by the package.
		foreach ($package->document()->objects->object as $object) {
			// Prepare object + getting item to be saved into database.
			$item = $package->getItem($object);
			// Get current object type name.
			$objectType = (string) $object->attributes()->type;
			// Instantiate template model if not previously instantiated.
			if (!is_object($objectModels[$objectType])) {
				$objectModels[$objectType] = CJTModel::getInstance($objectModels[$objectType]);
			}
			// Referencing the model for current object type.
			$model = $objectModels[$objectType];
			$objectId = 0; // Always reset -- to don't map to package if nothing added!
			// Handle different object types.
			switch ($objectType) {
				case 'template':
					// Insert template only if not exists.
					if (!$model->exists($item['name'])) {
						// Import template(s) helper.
						cssJSToolbox::import('includes:templates:templates.class.php');
						// Set template revision.
						$model->inputs['item']['revision']['code'] = $item['code'];
						unset($item['code']);
						// Set template main data.
						$model->inputs['item']['template'] = $item;
						 /** Get template Type!
					  * Get type from external file extension if
						* the template code was linked to file.
						* If the template code were inline
						* then the type must be provided under
						* TYPE element!
						*/
						// If no type specified get it from the external file extension
						if (!isset($model->inputs['item']['template']['type'])) {
							// @WARNING: Get locatted file!
							$codeFileName = (string) $object->code->attributes()->locatted;
							if ($codeFileName) {
								// Get type from extension.
								$fileComponent = pathinfo($codeFileName);
								$model->inputs['item']['template']['type'] = CJTTemplates::getExtensionType($fileComponent['extension']);	
							}
						}
						// Add template.
						$addedTemplate = $model->save();
						// Expose Object ID to be added to the addedObjects List.
						$objectId = $addedTemplate->templateId;
						// Copy template folders.
						if ($foldersCollection = $object->folders) {
							// Get absolute path to template directory!
							$templateDirectory = ABSPATH . dirname($addedTemplate->file);
							// Process all <folders> tags!
							foreach ($foldersCollection as $folders) {
								// Get <folders> tag common path.
								$foldersPath = (string) $folders->attributes()->path;
								// Process <folder> tag.
								foreach ($folders as $folder) {
									// Folder absolute path.
									if ($detinationName = (string) $folder->attributes()->destination) {
										$folderPath =  $detinationName;	
									}
									else {
										$folderPath = $folder->attributes()->path;
									}
									$folderAbsPath = $package->getDirectory() . "/{$foldersPath}/{$folderPath}";
									// Create destination path.
									$folderDestinationPath = "{$templateDirectory}/{$folderPath}";
									if (!file_exists($folderDestinationPath)) {
										mkdir($folderDestinationPath, 0775);	
									}
									// Copy files (FLAT)!!.
									foreach (new DirectoryIterator($folderAbsPath) as $file) {
										if (!$file->isDot() && $file->isFile()) {
											$fileSystem->copy($file->getPathName(), "{$folderDestinationPath}/{$file->getFileName()}");
										}
									}
								}
							}
						}
					}
				break;
				case 'block';
					// Set other block internal data.
					$item['created'] = $item['lastModified'] = current_time('mysql');
					$item['owner'] = get_current_user_id();
					// Insert block into database.
					$objectId = $model->add($item);
					$model->save();
					// Initialize for linking templates.
					$modelTemplate = $objectModels['template'];
					// Link block templates.
					$links = $object->links->link ? $object->links->link : array();
					foreach ($links as $link) {
						// Get template object to link.
						$templateToLinkAttributes = (array) $link->attributes();
						$templateToLinkAttributes = $templateToLinkAttributes['@attributes'];
						$tblTemplate = CJTxTable::getInstance('template')
																										  ->setData($templateToLinkAttributes) // Query by the given attributes.
																										  ->load(array_keys($templateToLinkAttributes));
						if ($tblTemplate->get('id')) {
							// Cache template id.
							$templateId = $tblTemplate->get('id');
							// Always link as the block should be newely added
							// and in the normal cases its impossible to be alread linked!
							$tableBlockTemplate = CJTxTable::getInstance('block-template');
							$tableBlockTemplate->set('blockId', $objectId)
																								 ->set('templateId', $templateId)
																								 ->save();
							// Add only LINKED objects to the package objects map table!
							if (!key_exists($templateId, $addedObjects['template'])) {
								$addedObjects['template'][$templateId] = array('objectId' => $templateId, 'relType' => 'link');
							}
						}
					}
				break;
				default:
					//throw new Exception('Invalid object type specified!');
				break;
			}
			// Support pluggable nodes that will parsed only when it really used.
			$this->pluggables($object, $objectId, $package);
			// Add (associate with the package) last objectId only if the object is added
			// as a part of the package.
			if ($objectId) {
				$addedObjects[$objectType][$objectId] = array('objectId' => $objectId);
			}
		}
		// Add package to database!
		$modelPackage->save($packageInfo, $addedObjects);
		// Chaining.
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $packageFile
	*/
	public function parse($name, $file) {
		// Initialize.
		$package = FALSE;
		// Use a tmp dir with the same name as package without the extension.
		$packageFileComponent = explode('.', $name);
		$destinationDir = get_temp_dir() . $packageFileComponent[0];
		// Import WP_FileSystem and unzip_file functions required to unzip the file.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		// Initialize Wordpress file systems.
		WP_Filesystem();
		// Extract package archive in temporary location.
		if (($result = unzip_file($file, $destinationDir)) === true) {
			// Import package definition class.
			cssJSToolbox::import('framework:packages:package.class.php');
			$package = new CJTPackageFile($destinationDir);
		}
		return $package;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $node
	* @param mixed $objectId
	* @param mixed $package
	* @return CJTPackageFileModel
	*/
	public function pluggables($node, $objectId, & $package) {
		// Get packages definition xml factory.
		$factory = new CJT_Models_Package_Xml_Factory('models/package/xml/definition/objects');
		// Based on the object type get instance of the object
		// parser with the object node passed.
		// call plug method so the object would start parsing the
		// and plug the child elements.
		$object = $factory->create(null, (string) $node->attributes()->type, $node); // OBJECT CONSTR&UCTED HERE!
		// Set object id!
		$object->register()->offsetSet('id', $objectId);
		$object->register()->offsetSet('packageParser', $package);
		// Plug it!
		$object->transit()
								->processInners();
		// Chaining.
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $property
	* @param mixed $state
	*/
	public function setState($property, $state) {
		// Set State!
		$this->state[$property] = $state;
		// Chaining.
		return $this;
	}
} // End class.

// Hookable!
CJTPackageFileModel::define('CJTPackageFileModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));