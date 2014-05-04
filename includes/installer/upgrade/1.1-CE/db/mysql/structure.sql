/* Move Old code field to thew code files */
INSERT INTO #__cjtoolbox_block_files (blockId, id, name, description, code) SELECT id, 1, 'Master', "Block MASTER code file, this file doesn't has type and cannot be deleted however fields might be updated", code FROM #__cjtoolbox_blocks;

/* Delete old 'code' field and add new masterFile field */
ALTER TABLE #__cjtoolbox_blocks DROP COLUMN `code`;
ALTER TABLE #__cjtoolbox_blocks ADD COLUMN `masterFile` INT(4) NOT NULL DEFAULT '1' AFTER flag