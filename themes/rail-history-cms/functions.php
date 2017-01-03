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


/**
 * Prints the album description of the current album.
 *
 * @param bool $editable
 */
function printAlbumDescAndLink($editable=false) 
{
	global $_zp_current_album;
	
	$desc = htmlspecialchars(getAlbumDesc());
	$desc = str_replace("\r\n", "\n", $_zp_current_album->getDesc());
	$desc = str_replace("\n", '<br />', $desc);
	
	$lineLink = $_zp_current_album->get('line_link');
	$locationId = $_zp_current_album->get('location_id');
	
	$name = $linkContent = "";
	if ($lineLink != '')
	{
		$name = $_zp_current_album->get('line_name');
		$url = "/lineguide/$lineLink";
	}	
	else if ($locationId != 0 AND $locationId != '')
	{
		$name = $_zp_current_album->get('location_name');
		$url = "/location/$locationId/";
	}
	
	if ($name != '')
	{
		$linkContent = "For more details see <a href=\"$url\">$name</a>.";
	}
	
	if ($editable AND zp_loggedin())
	{
		printMWEditableAlbumDesc(true);
		echo '<div class="albumdesc">'.$linkContent.'</div>';
	}
	else
	{
		$len = strlen($desc);
		if ($len > 1 AND substr($desc, $len-1, 1) != '.') {
			$desc .= ".";
		}
		
		echo "<div class=\"albumdesc\">$desc $linkContent</div>";
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