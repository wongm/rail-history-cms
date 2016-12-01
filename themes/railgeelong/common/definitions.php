<?php

// for searching by date links in the EXIF info box
DEFINE ('SEARCH_URL_PATH', "/page/search/");
DEFINE ('UPDATES_URL_PATH', "/gallery/recent/");
DEFINE ('SITEMAP_URL_PATH', "/sitemap.php");
DEFINE ('COPYRIGHT_URL_PATH', "/copyright.php");
DEFINE ('CREDITS_URL_PATH', "/credits.php");
DEFINE ('SOURCES_URL_PATH', "/sources.php");
DEFINE ('CONTACT_URL_PATH', "/page/contact/");
DEFINE ('GALLERY_PATH', '/gallery/');

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
	define ('TIME_FORMAT', '%B %d, %Y %H:%M %p');
}

$server = $_SERVER['SERVER_NAME'];
if ($server == 'localhost')
{
	define('GOOGLE_KEY','ABQIAAAAYC6wPz4_TXO8W21k3ZQsxxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRtkZ1P0Ak4-BtWFnDfiXlK-RmYgg');
}
else
{
	//railgeelong.com key
	define('GOOGLE_KEY','ABQIAAAAugzXsqqH3uhS-f10_b0zUBSSVOiGwpfQUHQXPiT4GcEe-nTa6RTLOirELIgs59SYCsR0rfBLa5a8bg');
}
define('GOOGLE_KEY_v3','AIzaSyDJ4gZMOYwBqPtilw3EqoT35UwrNqOHA40');

define('TYPE_SIGNAL_BOX', 29);
define('TYPE_YARD', 31);
define('TYPE_JUNCTION', 27);
define('TYPE_STATION', 15);
define('TYPE_RMSP', 37);
define('TYPE_INDUSTRY', 30);
define('TYPE_CROSSING_LOOP', 33);
define('TYPE_BLOCK_POINT', 34);
define('TYPE_TIMING_LOOP', 18);
define('TYPE_MISC', 40);

define("IMPORTANT_LOCATION","l.type IN (".TYPE_JUNCTION.",".TYPE_RMSP.",".TYPE_CROSSING_LOOP.","
	.TYPE_STATION.",".TYPE_SIGNAL_BOX.",".TYPE_INDUSTRY.",".TYPE_YARD.",".TYPE_BLOCK_POINT.")");
define("SQL_NEXTABLE"," l.display != 'tracks' AND (".IMPORTANT_LOCATION." 
	OR (l.events = '1' OR l.photos != '0') OR l.description != '') ");
	
define('DATE_UNKNOWN_OPEN', '0001-01-01');
define('DATE_UNKNOWN_CLOSE', '9999-01-01');
define('DATE_NULL', '0000-00-00');

if (!defined('DATE_FORMAT')) {
	define('DATE_FORMAT', '%W, %e %M %Y');
}

define('SHORT_DATE_FORMAT', '%M %e, %Y');
define('DATE_ACTIVE_CROSSINGS', 1975);
define('FRONT_PAGE_MAX_IMAGES', 6);
define('RANDOM_MAX', 23);

/*
 * pass it a location type
 * and get true / false if it is a station
 */
function typeIsStation($type)
{	
	switch ($type) 
	{
		case '15':	//stations
		case '23':
		case '24':
		case '25':
		case '26':
		//case '37':	//RMSP
		//case '27':	//junction
		//case 'TYPE_SIGNAL_BOX':	//signal box
		//case '30':	//industry
		//case '31':	//yards
			return true;
			break;
		default:
			return false;
			break;
	}
}

/*
 * pass it a location type
 * and get true / false if it is a crossing
 */
function typeIsCrossing($type)
{
	switch ($type) 
	{
		case '8':	//	on the level
		case '9':
		case '10':
		case '11':
		case '12':
		case '13':
		case '14':
		case '11':
		case '38':
		case '39':
		case '1':	//	road bridge
		case '2':
		case '3':
		case '4':
		//case '5':	// watercourse
			return true;
			break;
		default:
			return false;
			break;	
	}
}

function typeIsActiveCrossing($type)
{
	switch ($type) 
	{
		case '9':	//  Level Crossing  FL
		case '10':	// 	Level Crossing 	BB
		case '11':	// 	Level Crossing 	BB PG
		case '12':	// 	Level Crossing 	PG
			return true;
			break;
		default:
			return false;
			break;
	}
}
?>