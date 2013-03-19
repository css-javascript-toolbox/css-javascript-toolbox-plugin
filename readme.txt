# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
#   Date: March 19, 2013
#   Product Name: CSS & Javascript Toolbox V6
#   Description: Easily add custom CSS and JavaScript code to individual Pages, Posts, Categories, and URLs.
#   Type: WordPress Plugin, Premium
#   Product Version: 6.1.27.1
#   Author: http://wipeoutmedia.com
#   Original package file: css-javascript-toolbox.zip
#   Web Site: http://css-javascript-toolbox.com/
#   Contact: support@css-javascript-toolbox.com
#   Twitter: https://twitter.com/CJToolbox
#   User Manual: /docs/CJT_V6_User_Manual.pdf
#   Online Documentation: http://css-javascript-toolbox.com/css-javascript-toolbox-v6/
#   License: /license.txt
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
#   Installation
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

For more information and support about the installation process, please visit the 
CJT website here:  http://css-javascript-toolbox.com/css-javascript-toolbox-v6/

Requirements (Platform requirements)
	WordPress: Minimum 3.3, tested up to 3.5.1
	PHP: >= 5.2
		
Install (Installing Plugin Files)
	1. Upload the 'css-javascript-toolbox' folder to the '/wp-content/plugins/' directory
	2. Activate the plugin through the 'Plugins' menu in WordPress
	3. Click 'CSS & JavaScript Toolbox' link in the main navigation (left side of your Dashboard).

Setup
	The setup process is really simple! 
	After installing the plugin through WordPress plugins page admin notice will be shown on the top of the page! 
	You can then follow the links defined there!
	Another method is by going to the CJT main page and follow the instructions there!
	Have any problem with the setup of the Plugin, please post a question on the support forum or 
	contact us here: support@css-javascript-toolbox.com
	
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
#   Uninstall
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

1. Open WordPress Plugins page.
2. Select CSS Javascript Toolbox Plugin.
3. Deactivate & Delete the Plugin.

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
#   FAQ
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #	

Q: Can I move the blocks around?
	A: Yes by hovering your mouse cursor over the code block title bar until it turns into a four-sided arrow, 
	this allow you to move the blocks. Clicking the block title bar allows you to open and close the blocks.

Q: I'm using the URL List and my code is not working?
	A: Make sure you have copied and pasted the page, post, or category URL exactly as it appears in the address bar. 
	For example, you may have inadvertently included an extra forward slash at the end of your URL.
	
Q: Where did my CSS & JavaScript Block I created go?
	A: If you have added a new CSS/JS block, created a title and clicked the Save All Changes button, 
	and you refreshed the page when your block did not contain any code, then when the page reloads, 
	your new 'empty' block will disappear. You must have code inside the block for it to permanently save.

Q: Why use the Footer switch in Location/Hook?
	A: Hook location feature gives you control over the location of outputting the CSS/JS code. 
	This is useful in case overriding another Plugin CSS is required. Also sometimes its better to put your JS code 
	in the footer to avoid slowing down your page load.

Q: I received a weird error, what do I do now?
	A: Sometimes a bug decides to rear its ugly head and when this happens, this is when we need your help. 
	If you receive an error, if it be a PHP error, or some functionality that isn't working for whatever reason, 
	please send us an email.
	
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
#   Directory structure
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

|-access.points
|-controllers
|-docs
|-framework
|---access-points
|---css
|-----images
|---db
|-----mysql
|---events
|-----observers
|-----subjects
|---extensions
|---html
|-----components
|-------checkbox-list
|---------public
|-----------css
|---------tmpl
|---installer
|---js
|-----ace
|-------pluggable
|-----ajax
|-------cjt-server
|-------cjt-server-queue
|-------scripts-loader
|-------styles-loader
|-----cookies
|-------jquery.cookies.2.2.0
|-----hash
|-------md5
|-----installer
|-----misc
|-------simple-error-dialog
|-----ui
|-------jquery.link-progress
|-------jquery.toolbox
|-----utilities
|-----wordpress
|-------script-localizer
|---mvc
|---php
|-----evaluator
|---settings
|---third-party
|-----easy-digital-download
|---types
|-includes
|---installer
|-----installer
|-------db
|---------mysql
|-------includes
|---------templates
|-----upgrade
|-------0.2
|---------includes
|-------0.3
|---------includes
|-languages
|-models
|---fields
|---settings
|-tables
|-views
|---backups
|-----manager
|-------public
|---------css
|---------js
|-----------backups
|-------tmpl
|---blocks
|-----block
|-------public
|---------css
|---------images
|-----------edit-block-name
|-----------editor-toolbox
|-----------toolbox
|-------------editor-languages
|---------js
|-----------ajax
|-----------block
|-----------jquery.block
|-------tmpl
|---------templates
|-----cjt-block
|-------helpers
|-------public
|---------css
|---------images
|-----------editor-toolbox
|-----------pages-panel
|---------js
|-----------jquery.block
|-------tmpl
|---------templates
|-----create-metabox
|-------public
|---------css
|---------js
|-----------metabox
|-------tmpl
|-----info
|-------tmpl
|-----manager
|-------public
|---------css
|---------images
|-----------toolbox
|---------js
|-----------ajax-multioperation
|-----------blocks
|-----------blocks-page
|-------tmpl
|---------help
|-----metabox
|-------public
|---------css
|---------images
|-----------editor-toolbox
|---------js
|-----------jquery.block
|-----------metabox
|-------tmpl
|-----new
|-------public
|---------css
|---------js
|-----------add-new-block
|-------tmpl
|-----revisions
|-------public
|---------css
|---------js
|-----------revisions
|-------tmpl
|---extensions
|-----plugins-list
|-------public
|---------css
|---------images
|-----------extensions
|---------js
|-----------default
|-----------extensions
|-----------plugins
|-------tmpl
|---installer
|-----install
|-------public
|---------css
|---------images
|---------js
|-----------default
|-------tmpl
|---------upgrades
|-----notice
|-------public
|---------js
|-----------default
|-------tmpl
|---settings
|-----manager
|-------public
|---------css
|---------js
|-----------settings
|-------tmpl
|---------pages
|---setup
|-----activation-form
|-------public
|---------css
|---------images
|---------js
|-----------default
|-------tmpl
|-----setup
|-------public
|---------js
|-----------default
|-------tmpl
|---templates
|-----info
|-------tmpl
|-----lookup
|-------public
|---------css
|---------images
|---------js
|-----------lookup
|-------tmpl
|-----manager
|-------helpers
|-------public
|---------css
|---------images
|---------js
|-----------manager
|-------tmpl
|-----template
|-------public
|---------css
|---------js
|-----------template
|-------tmpl