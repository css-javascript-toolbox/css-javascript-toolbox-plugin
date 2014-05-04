/*
* CJT Database Version 1.2 Upgrade structure.
*
* Owner: css-javascript-toolbox.com
* Author: Ahmed Said
* Date: 
* Description: 
*/

/* Upgrade database collation to utf8_general_ci */
ALTER TABLE #__cjtoolbox_backups CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_blocks CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_block_pins CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_authors CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_block_templates CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_templates CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_template_revisions CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_packages CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE #__cjtoolbox_package_objects CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;

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

CREATE TABLE IF NOT EXISTS `#__cjtoolbox_parameter_typeparams` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`parameterId` INT(11) NOT NULL,
	`name` TEXT NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `parameterId` (`parameterId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;

/* 1.4 */
CREATE TABLE IF NOT EXISTS `#__cjtoolbox_form_group_xfields` (
	`groupId` INT(11) NOT NULL,
	`text` TEXT NULL,
	UNIQUE INDEX `groupId` (`groupId`)
) CHARACTER SET = utf8, COLLATE=utf8_general_ci;
/* </Shortcode Parameters Tables> */

/* Block Files Table */
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

/* Move Old code field to thew code files */
INSERT INTO #__cjtoolbox_block_files (blockId, id, name, description, code) SELECT id, 1, 'Master', "Block MASTER code file, this file doesn't has type and cannot be deleted however fields might be updated", code FROM #__cjtoolbox_blocks;

/* Delete old 'code' field and add new masterFile field */
ALTER TABLE #__cjtoolbox_blocks DROP COLUMN `code`;
ALTER TABLE #__cjtoolbox_blocks ADD COLUMN `masterFile` INT(4) NOT NULL DEFAULT '1' AFTER flag