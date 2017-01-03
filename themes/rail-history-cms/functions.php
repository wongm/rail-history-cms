<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

require_once('common/functions-gallery-formatting.php');
require_once('common/definitions.php');

zp_register_filter('admin_toolbox_global', 'railHistoryCMSTheme::admin_toolbox_global');
zp_register_filter('getLink', 'railHistoryCMSTheme::setCustomGalleryPath');

/**
 * Plugin option handling class
 *
 */
class railHistoryCMSTheme {
	
	static function setCustomGalleryPath($url, $object, $title) {
		
		if ($url == "/page/recent/"){
			return "/gallery/recent/";
		}
		if ($object instanceof Album OR $object instanceof Image){
			return "/gallery$url";
		}
	}
    
    static function admin_toolbox_global($zf) {
    	echo "<li>";
    	printLinkHTML(WEBPATH.'/page/recent-uncaptioned/', 'Uncaptioned images', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-uncaptioned-albums/', 'Uncaptioned albums', NULL, NULL, NULL);
    	echo "</li>";
		return $zf;
    }
}

function railHistoryCMS_checkPageValidity($request, $gallery_page, $page) {
    switch (stripSuffix($gallery_page))
	{
    	case 'lineguide':
    	case 'lineguides':
    	case 'location':
    	case 'locations':
    	case 'popular':
    	case 'ratings':
    	case 'recent':
    	case 'regions':
    	case 'updates':
    	    return true;
	}
    return checkPageValidity($request, $gallery_page, $page);
}

global $_zp_page_check;
$_zp_page_check = 'railHistoryCMS_checkPageValidity'; // opt-in, standard behavior

?>