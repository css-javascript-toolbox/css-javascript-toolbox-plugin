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
