<?php
/**
* 
*/

/**
* 
*/
return (object) array(
	'core' => (object) array('siteHook' => ((object) array('tag' => 'template_redirect', 'priority' => 11))),
	'database' => (object) array(
		'tables' => (object) array(
			'blocks' => 'cjtoolbox_blocks',
			'blockPins' => 'cjtoolbox_block_pins',
			'backups' => 'cjtoolbox_backups',
			'templates' => 'cjtoolbox_templates',
			'authors' => 'cjtoolbox_authors',
			'blockTemplates' => 'cjtoolbox_block_templates',
		),
	),
	'templates' => (object) array(
		'types' => array(
			'javascript' => (object) array('extension' => 'js'),
			'css' => (object) array('extension' => 'css'),
			'php' => (object) array('extension' => 'php'),
			'html' => (object) array('extension' => 'html'),
		)
	),
	'fileSystem'  => (object) array(
		'contentDir' => 'cjt-content',
		'templatesDir' => 'templates',
	),
);