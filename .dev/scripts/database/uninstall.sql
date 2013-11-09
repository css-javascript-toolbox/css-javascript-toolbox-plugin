/* CSS & Javascript Toolbox Version 6.0 Uninstall Script */

/* Delete TABLES */
DROP TABLE IF EXISTS 60wp35_cjtoolbox_authors;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_backups;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_blocks;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_block_pins;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_block_templates;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_templates;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_template_revisions;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_packages;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_package_objects;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_parameters;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_parameter_typedef;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_forms;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_form_groups;
DROP TABLE IF EXISTS 60wp35_cjtoolbox_form_group_parameters;

/* Delete Version 0.3 and 0.8 Tables */
DROP TABLE IF EXISTS 60wp35_cjtoolbox_cjdata;
/* Delete versions 0.3 and 0.8 options records */
DELETE FROM 60wp35_options where option_name = 'cjtoolbox_data';
DELETE FROM 60wp35_options where option_name = 'cjtoolbox_tools_backups';

/* Remove Version Number */
DELETE FROM 60wp35_options where option_name = 'cjtoolbox_db_version';

/* Clean up install state */
DELETE FROM 60wp35_options WHERE option_name = 'state.CJTInstallerModel.operations';
DELETE FROM 60wp35_usermeta WHERE meta_key = 'settings.CJTInstallerModel.noticeDismissed';

/* Licenses Cache */
DELETE FROM 60wp35_options WHERE option_name = 'cache.CJTSetupModel.licenses';

/* Remove metabox order */
DELETE FROM 60wp35_usermeta where meta_key = 'meta-box-order_settings_page_cjtoolbox';
DELETE FROM 60wp35_options where option_name = 'meta-box-order_cjtoolbox';