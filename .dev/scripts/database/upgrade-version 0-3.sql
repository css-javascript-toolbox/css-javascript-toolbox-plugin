
/* Copying Blocks Data to V6 Test Lab. */
DELETE FROM 60wp35_options WHERE option_name IN('cjtoolbox_data', 'meta-box-order_cjtoolbox');
INSERT INTO 60wp35_options (option_name, option_value) select option_name, option_value from `03_options` where option_name = 'cjtoolbox_data';

/* Blocks Order */
DELETE FROM 60wp35_usermeta WHERE meta_key = 'meta-box-order_settings_page_cjtoolbox';
INSERT INTO 60wp35_usermeta (user_id, meta_key, meta_value) select 1, meta_key, meta_value from `03_usermeta` where user_id = 1 and meta_key = 'meta-box-order_settings_page_cjtoolbox' ;
DELETE FROM 60wp35_usermeta WHERE meta_key = 'meta-box-order_cjtoolbox';

/* Closed Metaboxes */
DELETE FROM 60wp35_usermeta WHERE meta_key = 'closedpostboxes_settings_page_cjtoolbox';
INSERT INTO 60wp35_usermeta (user_id, meta_key, meta_value) select 1, meta_key, meta_value from `03_usermeta` where user_id = 1 and meta_key = 'closedpostboxes_settings_page_cjtoolbox' ;
DELETE FROM 60wp35_usermeta where meta_key = 'closedpostboxes_cjtoolbox';

/* Copy Template table and data. */
DROP TABLE IF EXISTS 60wp35_cjtoolbox_cjdata;
CREATE TABLE 60wp35_cjtoolbox_cjdata LIKE `03_cjtoolbox_cjdata`;
INSERT INTO 60wp35_cjtoolbox_cjdata SELECT * FROM `03_cjtoolbox_cjdata`;

/* If tables is already created cause of test delete them */
DROP TABLE IF EXISTS 60wp35_cjtoolbox_authors;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_backups;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_blocks;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_block_pins;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_block_templates;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_templates;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_template_revisions;

/* Update Version number. */
UPDATE 60wp35_options SET option_value = '0.2' WHERE option_name = 'cjtoolbox_db_version';

/* Clean up install state */
DELETE FROM 60wp35_options WHERE option_name = 'state.CJTInstallerModel.operations';
DELETE FROM 60wp35_options WHERE option_name = 'settings.CJTInstallerModel.noticeDismissed';