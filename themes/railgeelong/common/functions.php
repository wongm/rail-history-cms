<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

// for searching by date links in the EXIF info box
DEFINE ('DATE_SEARCH', false);
//DEFINE ('ARCHIVE_URL_PATH', "/gallery/archive");
DEFINE ('SEARCH_URL_PATH', "/gallery/page/search");
DEFINE ('UPDATES_URL_PATH', "/gallery/recent");
//DEFINE ('EVERY_ALBUM_PATH', "/gallery/everything");
DEFINE ('CONTACT_URL_PATH', "/contact.php");
//DEFINE ('RANDOM_ALBUM_PATH', "/gallery/random");
DEFINE ('GALLERY_PATH', '/gallery');

DEFINE ('COPYRIGHT', '<p>All photographs copyright Marcus Wong unless otherwise noted.</p>');

if ($_zp_options != '')
	{
	// dynamic from the DB
	define ('MAXIMAGES_PERPAGE', $_zp_options['images_per_page']);
	define ('MAXALBUMS_PERPAGE', $_zp_options['albums_per_page']);
	define ('THUMBNAIL_IMAGE_SIZE', $_zp_options['thumb_size']);
	define ('NORMAL_IMAGE_SIZE', 640);
	define ('TIME_FORMAT', $_zp_options['date_format']);
}
else
{
	define ('MAXIMAGES_PERPAGE', 24);
	define ('MAXALBUMS_PERPAGE', 24);
	define ('THUMBNAIL_IMAGE_SIZE', 250);
	define ('NORMAL_IMAGE_SIZE', 640);
	define ('MAXIMAGES_LOCATIONPAGE', 9);
	DEFINE ('GALLERY_PATH', '/gallery');
	define ('TIME_FORMAT', '%B %d, %Y %H:%M %p');
}

zp_register_filter('checkPageValidity', 'railGeelongTheme::checkLinks');
zp_register_filter('admin_toolbox_album', 'railGeelongTheme::addAlbumLink');
zp_register_filter('admin_toolbox_global', 'railGeelongTheme::addGlobalLink');
zp_register_filter('getLink', 'railGeelongTheme::setCustomGalleryPath');

/**
 * Plugin option handling class
 *
 */
class railGeelongTheme {
	
	static function setCustomGalleryPath($url, $object, $title) {
		if ($object instanceof Album OR $object instanceof Image){
			//echo 'is an album';
			return "/gallery$url";
		}
	}
	
	static function checkLinks($count, $gallery_page, $page) {
    	
		print_r($_GET);
		echo 'checkLinks';
    	
    	switch ($gallery_page)
    	{
        	case 'lineguide.php':
        	case 'lineguides.php':
        	case 'location.php':
        	case 'locations.php':
        	case 'locations-home.php':
        	case 'popular.php':
        	case 'ratings.php':
        	case 'recent.php':
        	case 'regions.php':
        	case 'updates.php':
        	    return true;
		}
	}
    
    static function addGlobalLink() {
    	echo "<li>";
    	printLinkHTML(WEBPATH.'/page/recent-uncaptioned', 'Uncaptioned images', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-uncaptioned-albums', 'Uncaptioned albums', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-duplicates', 'Duplicate images', NULL, NULL, NULL);
    	echo "</li>";
    }
}

function printFacebookTag()
{
	$path = 'http://' . $_SERVER['HTTP_HOST'] . getImageThumb();		
	$description = "A history of the railways of Geelong and District";	
	if (strlen(getImageDesc()) > 0)	{
		$description = getImageDesc() + ". $description";
	}	
	echo "<meta property=\"og:image\" content=\"$path\" />\n";
	echo "<meta property=\"og:title\" content=\"" . getImageTitle() . "\" />\n";	
	echo "<meta property=\"og:description\" content=\"$description\" />\n";
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
		echo "<div id=\"albumDescEditable\" style=\"display: block;\">" . $desc . "</div>\n";
		echo "<script type=\"text/javascript\">initEditableDesc('albumDescEditable');</script>\n";
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
}

function getMyPageURL($defaultURL)
{
	$defaultURL = str_replace('/page/search/', '/gallery/search/', $defaultURL);
	$defaultURL = str_replace('/page/page/', '/page/', $defaultURL);
	return str_replace('/gallery/everything/', '/gallery/everything/page/', $defaultURL);
}

/**
 * Prints a list of all pages.
 *
 * @param string $class the css class to use, "pagelist" by default
 * @param string $id the css id to use
 */
function drawGalleryPageNumberLinks($url='')
{
	$total = getTotalPages();
	$current = getCurrentPage();

	echo '<p>';

  	if ($total > 0)
  	{
		echo 'Page: ';
	}

	if ($current > 3 AND $total > 7)
	{
		echo "\n <a href=\"".$url.getMyPageURL(getPageURL(1))."\" title=\"First page\">1</a>&nbsp;";

		if ($current > 4)
		{
			echo "...&nbsp;";
		}
	}

	for ($i=($j=max(1, min($current-2, $total-6))); $i <= min($total, $j+6); $i++)
	{
		if ($i == $current)
		{
			echo $i;
		}
		else
		{
			echo '<a href="'.$url.getMyPageURL(getPageURL($i)).'"\" title="Page '.$i.'">'.($i).'</a>';
		}
		echo "&nbsp;";
	}
	if ($i <= $total)
	{
		if ($current < $total-5)
		{
			echo "...&nbsp;";
		}

		echo "<a href=\"".$url.getMyPageURL(getPageURL($total))."\" title=\"Last page\">" . $total . "</a>";
	}
	echo '</p>';
}

function my_checkPageValidity($request, $gallery_page, $page) {
	if (isset($_GET['wongm'])) {
        echo '<BR>inside my_checkPageValidity';
//            die();
    }

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
$_zp_page_check = 'my_checkPageValidity'; // opt-in, standard behavior

?>