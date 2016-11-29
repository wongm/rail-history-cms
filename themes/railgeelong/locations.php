<?php 
require_once("common/definitions.php");
require_once("common/location-functions.php");
require_once("common/formatting-functions.php");

/*
 * show a listing of certain type of location
 */
if(isset($_REQUEST['type']))
{
	$locationType = $_REQUEST['type'];
	$locationSort = '';
	
	if (isset($_REQUEST['sort'])) {
		$locationSort = str_replace('by-', '', $_REQUEST['sort']);
	}
	
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
	
	require_once("common/header.php");
	require_once('common/location-lineguide-functions.php');
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/locations/">Locations</a> &raquo; <?php echo $breadCrumbTitle?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once('common/midbar.php'); ?>
<h3>Locations database: <?php echo $breadCrumbTitle?></h3>
<?php
	drawLinedLocationsTable(getLocationsTable('', '', $sql, $locationType, $locationSort), 'type');
	require_once("common/footer.php");
}
?>