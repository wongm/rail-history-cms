<?php 
include_once("../common/dbConnection.php");
include_once("../common/location-functions.php");
include_once("../common/location-database-functions.php");
include_once("../common/source-functions.php");
include_once("../common/formatting-functions.php");

$locationName = str_replace('-', ' ', $_REQUEST['name']);
$locationBox = str_replace('-', ' ', $_REQUEST['box']);
$locationId = str_replace('-', ' ', $_REQUEST['id']);

if (!is_numeric($locationId) AND $locationId != '')
{
	$locationName = $locationId;
	$locationId = '';
}

// fix for auto modrewrite stuff
if (is_numeric($locationName) AND $locationId == '')
{
	$locationId = $locationName;
	$locationName = '';
}

$lineLink = $_REQUEST['line'];
$locationType = $_REQUEST['type'];
$locationSearch = $_REQUEST['search'];
$locationSearchPage = $_REQUEST['page'];
$locationSort = str_replace('by-', '', $_REQUEST['sort']);

// show specific location - when losts of info given
if($locationBox != "" OR $locationName != "" OR $locationId != "")
{
	$location = getLocation($locationName, $locationBox, $locationId, $lineLink);
	
	if (!$location['error'])
	{
		drawLocation($location);
	}
	else if ($location['error'] == 'duplicates')
	{
		drawDuplicateLocation($location['duplicates']);
	}
	else if ($location['error'] == 'empty')
	{
		drawInvalidLocation($location['locationToFind']);
	}
}
// show a listing of certain type of location
elseif($locationType != "")
{
	switch ($locationType)
	{
		case 'yards':
			$pageTitle = 'Yards and Sidings';
			$sql = " l.type='31' ";
			break;
		case 'misc':
			$pageTitle = 'Miscellaneous locations';
			$sql = " l.type='40' ";
			break;
		case 'stations':
			$pageTitle = 'Stations';
			$sql = " (l.type='15' OR l.type='37') ";
			break;
		case 'industries':
			$pageTitle = 'Industries';
			$sql = " l.type='30' ";
			break;
		case 'signalboxes':
			$pageTitle = 'Signal Boxes';
			$sql = " l.type='29' ";
			break;
		default:
			draw404InvalidSubpage('locations');
			return;
	}
	include_once("header.php");
	include_once('../common/location-lineguide-functions.php');
	drawLinedLocationsTable(getLocationsTable('', '', $sql, $locationType, $locationSort));
	include_once("footer.php");
}
// find a location by name
elseif($locationSearch != "")	
{
	$pageTitle = "Location Search - \"$locationSearch\"";
	include_once("header.php");
	drawLocationSearch($locationSearch, $locationSearchPage);
	include_once("footer.php");
}
// a default opening info page
else	
{
	$pageTitle = 'Locations';
	include_once("header.php");
	drawMainPage();	
	include_once("footer.php");
} ?>

