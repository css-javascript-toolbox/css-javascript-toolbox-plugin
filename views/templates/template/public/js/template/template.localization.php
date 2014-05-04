<?php
/**
* @version FILE_VERSION
* Localization file for jquery.block.js script.
*/

/**
* Localization for Javascript $VAR$ variable.
* 
* Localization text for backups script.
*/
return array(
	'invalidName' => cssJSToolbox::getText(sprintf('The %s name cannot be left blank.  Please choose a unique name using characters: A-Z, 0-9, -, _ and space characters only', cssJSToolbox::getText('template'))),
	'AlreadyInUse' => cssJSToolbox::getText(sprintf('Sorry, but the %s name you entered is already in use. Please choose another name!', cssJSToolbox::getText('template'))),
	'languageIsNotSelected' => cssJSToolbox::getText(sprintf('Please set the %s to be one of these selections: %s', cssJSToolbox::getText('language type'), cssJSToolbox::getText('CSS, JavaScript, HTML, or PHP', 'language type'))),
	'stateIsNotSelected' => cssJSToolbox::getText(sprintf('Please set the %s to be one of these selections: %s', cssJSToolbox::getText('template state'), cssJSToolbox::getText('Published, Draft, or Trash'))),
	'confirmSetType' => cssJSToolbox::getText('You won\'t be able to revert the type name once its set! Are you sure would you like to use the selected type?'),
);