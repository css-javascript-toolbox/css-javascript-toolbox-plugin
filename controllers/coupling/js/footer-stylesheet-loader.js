
/**
* Simply load all footer style sheets by directly adding them
* to the header tag using a 'link' element.
*/
jQuery.each(cjt_footer_linked_stylesheets,
	jQuery.proxy(function(index, style) {
		var link = '<link href="' + style + '" rel="stylesheet" type="text/css" />';
		jQuery('head').append(link);
	}, this)
);