/*
* CJT Database Version 1.1 Upgrade structure.
*
* Owner: css-javascript-toolbox.com
* Author: Ahmed Said
* Date: 
* Description: 
*/

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
	`objectType` ENUM('block','template') NOT NULL,
	`relType` ENUM('add','link') NOT NULL DEFAULT 'add',
	PRIMARY KEY (`packageId`, `objectId`, `objectType`)
)
