<?php
/**
* @version FILE_VERSION
* Localization file for jquery.block.js script.
*/

/**
* Localization for Javascript  variable.
* 
* Localization text for backups script.
*/
return array(
	'confirmactivate' => cssJSToolbox::getText("External request need to be made to to css-javascript-toolbox offical web site! The operation will take an effect once its successed! You can always deactivate license key throught this form!\n
																																													Are you sure would you like to process?"),
	'confirmdeactivate' => cssJSToolbox::getText("External request need to be made to to css-javascript-toolbox offical web site! The operation will take an effect once its successed! You can always activate license key throught this form!\n
																																													Are you sure would you like to process?"),
	'confirmreset' => cssJSToolbox::getText('The reset operation will clear the license fields and clear component license cache! This is really great once you need to stop using a previously entered key! This operation help your key to stay secure when you really need that! All cached data would be cleared now! You can reactivate your key anytime later!'),
	/* Action button names */
	'activateActionButtonCaption' => cssJSToolbox::getText('Activate'),
	'deactivateActionButtonCaption' => cssJSToolbox::getText('Deactivate'),	
	/* Actions State name to be showed while in progress! */
	'activateActionStateName' => cssJSToolbox::getText('Activating Key'),
	'deactivateActionStateName' => cssJSToolbox::getText('Deactivating key'),
	'checkActionStateName' => cssJSToolbox::getText('Checking Key'),
	'site_inactiveStateName' => cssJSToolbox::getText('Key is not activated/used yet'),
	'inactiveStateName' => cssJSToolbox::getText('Key is inactive'),
	'resetActionStateName' => cssJSToolbox::getText('Reseting License Information'),
	/* State names */
	'invalidStateName' => cssJSToolbox::getText('Invalid'),
	'validStateName' => cssJSToolbox::getText('Valid'),
	'errorStateName' => cssJSToolbox::getText('Error'),
	'activateStateName' => cssJSToolbox::getText('Activated'),
	'deactivateStateName' => cssJSToolbox::getText('Deactivated'),
	'resetStateName' => cssJSToolbox::getText('Reseted'),
	
	/* Request message to be showed after the request is completed! */
	'readvalidRequestMessage' => cssJSToolbox::getText('License key is activate! Thank you!'),
	'resetvalidRequestMessage' => cssJSToolbox::getText('License Cache has been reseted!'),
	
	'activatevalidRequestMessage' => cssJSToolbox::getText('License key is activated!'),
	'activateinvalidRequestMessage' => cssJSToolbox::getText('Could not activate the provided license key!'),
	'activateerrorRequestMessage' => cssJSToolbox::getText('Could not activate License Key due to the server error!'),
	
	'deactivatevalidRequestMessage' => cssJSToolbox::getText('The license key is deactivated!'),
	'deactivateinvalidRequestMessage' => cssJSToolbox::getText('Could not deactivate the provided license key!'),
	'deactivateerrorRequestMessage' => cssJSToolbox::getText('Could not deactivate License Key due to the server error!'),
	
	'checkvalidRequestMessage' => cssJSToolbox::getText('The ckeck operation detects that the provided key is a valid key!'),
	'checkinvalidRequestMessage' => cssJSToolbox::getText('The check operation detects that the provided key is an invalid key!'),
	'checkerrorRequestMessage' => cssJSToolbox::getText('Could not check License Key due to the server error!'),
	'checksite_inactiveRequestMessage' => cssJSToolbox::getText('The ckeck operation detects that the provided key is a valid key! The key is not being activated yet.'),
	'checkinactiveRequestMessage' => cssJSToolbox::getText('The ckeck operation detects that the provided key is a valid key! The license key need to be activated from the CJT Website.'),
	
	'resetButtonCaption' => cssJSToolbox::getText('Reset'),
	/* Validation */
	'invalidName' => cssJSToolbox::getText('Invalid License name!'),
	'invalidKey' => cssJSToolbox::getText('Invalid License key!'),
	'componentNameIsAbsolute' => cssJSToolbox::getText('Component name is absolute!'),
);