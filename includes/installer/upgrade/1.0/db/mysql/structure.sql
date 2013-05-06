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
	`author` VARCHAR(150) NOT NULL,
	`webSite` VARCHAR(300) NOT NULL,
	`description` TEXT NOT NULL,
	`license` TEXT NOT NULL,
	`readme` TEXT NOT NULL,
	`id` INT(11) NOT NULL AUTO_INCREMENT,
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
