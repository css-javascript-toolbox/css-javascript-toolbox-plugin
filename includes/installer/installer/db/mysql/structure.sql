/*
* CJT Database Version 1.2 structure.
*
* Owner: css-javascript-toolbox.com
* Author: Ahmed Said
* Date: 
* Description: 
*/

/*
* Template Authors Table Structure 
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_authors` (
  `name` varchar(80) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `attributes` int(4) NOT NULL DEFAULT '0',
  `guid` varchar(16) DEFAULT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  KEY `name` (`name`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/* 
* CJT Backups Header Table Structure.
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_backups` (
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'blocks',
  `owner` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/*
* Blocks Table Structure!
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_blocks` (
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `owner` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `lastModified` datetime NOT NULL,
  `pinPoint` int(4) NOT NULL DEFAULT '0',
  `state` enum('active','inactive') DEFAULT 'inactive',
  `location` enum('header','footer') DEFAULT 'header',
  `links` text,
  `expressions` text,
  `type` enum('block','revision','metabox') DEFAULT 'block',
  `backupId` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `flag` int(4) NOT NULL DEFAULT '0',
	`masterFile` INT(4) NOT NULL DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`backupId`),
  KEY `pinPoint` (`pinPoint`,`state`,`location`,`type`,`parent`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/*
* Blocks Table Structure!
* Since: 1.5
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_block_files` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`blockId` INT(11) NOT NULL,
	`name` VARCHAR(100) NOT NULL,
	`type` ENUM('css','javascript','php','html') NULL DEFAULT NULL,
	`description` VARCHAR(400) NULL DEFAULT NULL,
	`code` TEXT NULL,
	`order` SMALLINT(6) NULL DEFAULT '0',
	`tag` TEXT NULL,
	PRIMARY KEY (`id`, `blockId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;


/*
* Blocks Pins table Structure!
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_block_pins` (
  `blockId` int(11) NOT NULL,
  `pin` varchar(20) NOT NULL,
  `value` int(11) NOT NULL,
  `attributes` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blockId`,`pin`,`value`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/*
* Block Associated/Linked templates table structure! 
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_block_templates` (
  `blockId` int(11) NOT NULL,
  `templateId` int(11) NOT NULL,
  PRIMARY KEY (`blockId`,`templateId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/*
* Templates Table structure!
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_templates` (
  `name` varchar(80) NOT NULL,
  `queueName` varchar(80) NOT NULL,
  `description` text,
  `keywords` varchar(300) DEFAULT NULL,
  `license` text,
  `type` enum('css','javascript','php','html') NOT NULL,
  `email` varchar(90) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  `ownerId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `state` enum('published','draft','trash') NOT NULL DEFAULT 'published',
  `attributes` int(4) NOT NULL DEFAULT '0',
  `guid` varchar(16) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`, `type`),
  UNIQUE KEY `guid` (`guid`),
  KEY `keywords` (`keywords`),
  KEY `owner` (`ownerId`),
  KEY `author` (`authorId`),
  KEY `type` (`type`),
  KEY `attributes` (`attributes`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/*
* Template revisions table!
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_template_revisions` (
  `templateId` int(11) NOT NULL,
  `revisionNo` int(11) NOT NULL DEFAULT '0',
  `version` varchar(15) DEFAULT NULL,
  `changeLog` varchar(600) DEFAULT NULL,
  `state` enum('release','beta','release-candidate','alpha','revision') NOT NULL,
  `attributes` int(4) unsigned NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL,
  `file` varchar(400) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `templateid-revisionNo` (`revisionNo`,`templateId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/* Packages holder table */
CREATE TABLE IF NOT EXISTS`#__cjtoolbox_packages` (
	`name` VARCHAR(100) NOT NULL,
	`author` VARCHAR(150) NOT NULL,
	`webSite` VARCHAR(300) NOT NULL,
	`description` TEXT NOT NULL,
	`license` TEXT NOT NULL,
	`readme` TEXT NOT NULL,
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/* Package objects map */
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_package_objects` (
	`packageId` INT UNSIGNED NOT NULL,
	`objectId` INT UNSIGNED NOT NULL,
	`objectType` ENUM('block','template') NOT NULL,
	`relType` ENUM('add','link') NOT NULL DEFAULT 'add',
	PRIMARY KEY (`packageId`, `objectId`, `objectType`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/* DB Version 1.3 */
/* <Shortcode Parameters Tables> */
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_forms` (
  `blockId` int(11) NOT NULL COMMENT 'block to be associated with the form',
  `name` varchar(100) NOT NULL COMMENT 'Form name/title',
  `groupType` varchar(20) NOT NULL COMMENT 'parameters gooup type (tab, accordion, etc...)',
  PRIMARY KEY (`blockId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__cjtoolbox_form_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formId` int(11) NOT NULL COMMENT 'block to be associated with the form',
  `name` varchar(100) NOT NULL COMMENT 'group name/title',
  `description` text NULL COMMENT 'Parameters group description',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 3` (`formId`,`name`),
  KEY `formId` (`formId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__cjtoolbox_form_group_parameters` (
  `groupId` int(11) NOT NULL AUTO_INCREMENT,
  `parameterId` int(11) NOT NULL COMMENT 'block to be associated with the form',
  `renderer` varchar(30) DEFAULT NULL,
  `description` text NULL,
  `helpText` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`parameterId`),
  KEY `Index 2` (`groupId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__cjtoolbox_parameters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'paramter unique identifier',
  `blockId` int(11) unsigned NOT NULL,
  `parent` int(11) unsigned DEFAULT NULL,
  `name` varchar(60) NOT NULL,
  `type` varchar(20) NOT NULL,
  `defaultValue` text,
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`contentParam` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name` (`name`, `parent`, `blockId`),
  KEY `parent` (`parent`),
  KEY `blockId` (`blockId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__cjtoolbox_parameter_typedef` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameterId` int(11) NOT NULL,
  `text` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parameterId` (`parameterId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

CREATE TABLE `#__cjtoolbox_parameter_typeparams` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`parameterId` INT(11) NOT NULL,
	`name` TEXT NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `parameterId` (`parameterId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/* 1.4 */
CREATE TABLE `#__cjtoolbox_form_group_xfields` (
	`groupId` INT(11) NOT NULL,
	`text` TEXT NULL,
	UNIQUE INDEX `groupId` (`groupId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci
/* </Shortcode Parameters Tables> */