/*
* CJT Database Version 2.0 structure.
*
* Owner: css-javascript-toolbox.com
* Author: Ahmed Said
* Date: 
* Description: 
*/

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
) CHARACTER SET = utf8, COLLATE=utf8_general_ci
