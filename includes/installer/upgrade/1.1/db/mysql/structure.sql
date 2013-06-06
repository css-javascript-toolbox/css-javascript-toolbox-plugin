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
ALTER TABLE #__cjtoolbox_package_objects CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci