<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Template
extends CJT_Models_Package_Xml_Definition_Abstract {

	/**
	* 
	*/
	const VIRTUAL_PATH = 'template';

	/**
	* Do nothing!
	* 
	*/
	public function transit() {
		// Initialize.
		$model = CJTModel::getInstance('template');
		$node = $this->getNode();
		$register = $this->register();
		// Prepare object + getting item to be saved into database.
		$template = $register['packageParser']->getItem($node);
		// Insert template only if not exists.
		if (!$model->exists($template['name'])) {
			// Import template(s) helper.
			cssJSToolbox::import('includes:templates:templates.class.php');
			// Set template revision.
			$model->inputs['item']['revision']['code'] = $template['code'];
			unset($template['code']);
			// Set template main data.
			$model->inputs['item']['template'] = $template;
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
				$codeFileName = (string) $node->code->attributes()->locatted;
				if ($codeFileName) {
					// Get type from extension.
					$fileComponent = pathinfo($codeFileName);
					$model->inputs['item']['template']['type'] = CJTTemplates::getExtensionType($fileComponent['extension']);	
				}
			}
			// Add template.
			$addedTemplate = $model->save();
			// Expose template fields to be by used by other objects.
			$register['templateId'] = $addedTemplate->templateId;
			$register['templateFile'] = $addedTemplate->file;
		}					
		// Chaining.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function virtualPath() {
		// Block object is unique accross all the file
		// use unique global path that can be  accessed from everywhere
		// without knowing the real path as it might change based on the 
		// child model!
		return self::VIRTUAL_PATH;
	}

} // End class