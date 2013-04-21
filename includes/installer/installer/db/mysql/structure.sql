/*
* CJT Database Version 1.1 structure.
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
);

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
);

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
  `code` text,
  `links` text,
  `expressions` text,
  `type` enum('block','revision','metabox') DEFAULT 'block',
  `backupId` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `flag` int(4) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`backupId`),
  KEY `pinPoint` (`pinPoint`,`state`,`location`,`type`,`parent`)
);

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
);

/*
* Block Associated/Linked templates table structure! 
* Since: 2.0
*/
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_block_templates` (
  `blockId` int(11) NOT NULL,
  `templateId` int(11) NOT NULL,
  PRIMARY KEY (`blockId`,`templateId`)
);

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
);

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
);

/* VERSION 1.1 */

/* Packages holder table */
CREATE TABLE IF NOT EXISTS`#__cjtoolbox_packages` (
	`name` VARCHAR(100) NOT NULL,
	`author` VARCHAR(60) NOT NULL,
	`authorMail` VARCHAR(60) NOT NULL,
	`uri` VARCHAR(150) NOT NULL,
	`description` VARCHAR(500) NOT NULL,
	`id` INT AUTO_INCREMENT NOT NULL,
	PRIMARY KEY (`id`)
);

/* Package objects map */
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_package_objects` (
	`packageId` INT UNSIGNED NOT NULL,
	`objectId` INT UNSIGNED NOT NULL,
	`objectType` VARCHAR(8) NOT NULL,
	PRIMARY KEY (`packageId`, `objectId`, `objectType`)
)
