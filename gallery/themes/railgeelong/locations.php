<?php 
include_once("../common/dbConnection.php");
include_once("../common/location-functions.php");
include_once("../common/formatting-functions.php");

$locationType = $_REQUEST['type'];
$locationSearch = $_REQUEST['search'];
$locationSearchPage = $_REQUEST['page'];
$locationSort = str_replace('by-', '', $_REQUEST['sort']);

/*
 * show a listing of certain type of location
 */
if($locationType != "")
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
	$breadCrumbTitle = $pageTitle;
	
	include_once("header.php");
	include_once('../common/location-lineguide-functions.php');
?>
<table class="headbar">
	<tr><td><a href="/">Home</a> &raquo; <a href="/locations">Locations</a> &raquo; <?=$breadCrumbTitle?></td>
	<td id="righthead"><? drawHeadbarSearchBox(); ?></td></tr>
</table>
<h3>Locations database: <?=$breadCrumbTitle?></h3>
<?php
	drawLinedLocationsTable(getLocationsTable('', '', $sql, $locationType, $locationSort), 'type');
	include_once("footer.php");
}
/*
 * find a location by name
 */
elseif($locationSearch != "")	
{
	$pageTitle = "Location search - \"$locationSearch\"";
	include_once("header.php");
	drawLocationSearch($locationSearch, $locationSearchPage);
	include_once("footer.php");
}
?>