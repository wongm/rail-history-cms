<?php
include_once(dirname(__FILE__) . "/../../../common/dbConnection.php");
include_once(dirname(__FILE__) . "/../../../common/lineguide-functions.php");
include_once(dirname(__FILE__) . "/../../../common/lineguide-database-functions.php");
include_once(dirname(__FILE__) . "/../../../common/event-functions.php");
include_once(dirname(__FILE__) . "/../../../common/source-functions.php");
include_once(dirname(__FILE__) . "/../../../common/formatting-functions.php");
include_once(dirname(__FILE__) . "/../../../common/map-functions.php");

$lineToDisplay = '';
$yearToDisplay = '';
$trackPage = '';
$section = '';
$sort = '';

if (isset($_REQUEST['line'])) {
	$lineToDisplay = $_REQUEST['line'];
}
if (isset($_REQUEST['year'])) {
	$yearToDisplay = $_REQUEST['year'];
	$yearToDisplay = str_replace('year-', '', $yearToDisplay);
}
if (isset($_REQUEST['page'])) {
	$trackPage = $_REQUEST['page'];
}
if (isset($_REQUEST['section'])) {
	$section= $_REQUEST['section'];
}
if (isset($_REQUEST['sort'])) {
	$sort = $_REQUEST['sort'];
	$sort = str_replace('by-', '', $sort);
}

$line = getLine($lineToDisplay, $yearToDisplay);

if (!isset($line['lineId']) || $line['lineId'] == '')
{
	draw404InvalidSubpage("lineguide", "line");
}
else
{
	// draw all events for line
	if ($section == 'events' AND $line['showEvents'])
	{
		drawLineguideHeaders($line, 'Events');
		drawAdminEditableLink("/backend/listLineEvents.php?line=".$line['lineLink'], "Edit events");
		echo "<h3>Events</h3>\n";
		drawLineguideEventsSection($line);
		drawLineguideFooters($line);
	}
	elseif ($section == 'events-by-date' AND $line['showEvents'])
	{
		drawLineguideHeaders($line, 'Events by Date');
		drawAdminEditableLink("/backend/listLineEvents.php?line=".$line['lineLink'], "Edit events");
		echo "<h3>Events</h3>\n";
		echo "<a href=\"/lineguide/".$line['lineLink']."/events\">Events by Type</a> :: Events by Date\n";
		drawLineEvents($line['lineId'], 'By Date');
		drawLineguideFooters($line);
	}
	// listing of locations for line
	elseif ($section == 'locations' AND $line['showLocations'])
	{
		include_once(dirname(__FILE__) . "/../../../common/location-lineguide-functions.php");
		drawLineguideHeaders($line, 'Locations');
		drawAdminEditableLink("/backend/listLineLocations.php?line=".$line['lineLink'], "Edit locations");
		echo "<h3>Locations</h3>\n";
		drawLinedLocationsTable(getLocationsTable($line['lineId'], $lineToDisplay, '', '', $sort), 'line');
		drawLineguideFooters($line);
	}
	// lineguide Google map
	elseif ($section == 'map' AND $line['showGoogleMap'])
	{
		include_once(dirname(__FILE__) . "/../../../common/aerial-functions.php");
		$googleHeader = 'article';
		$googleHeaderKMLscript = generateKMLScript('kml-' . $line['lineId'] . '.kml');

		$pageTitle = $pageHeading = getLineName($line['lineName'])." Guide";
		$pageTitle = "$pageTitle - Google Map";
		include_once(dirname(__FILE__) . "/../../../common/header.php");
		drawLineguideHeaders($line, 'Google Map');
		
		echo "<h3>Google Map</h3>\n";
		echo '<div id="map" class="inlinemap"></div>';
		drawLineguideFooters($line);
	}
	// 'home' page for line with links
	elseif ($section == '')
	{
		drawLineguideHeaders($line, 'Home');
		drawAdminEditableLink("/backend/editLines.php?line=".$line['lineLink'], "Edit line");
		drawSpecificLine($line);
		drawLineguideFooters($line);
	}
	// diagram page with tabs
	elseif ($section == 'diagram' AND $line['trackDiagramTabs'] != '')
	{
		drawLineguideDiagramTabs($line);
	}
	// safeworking diagram pages for line
	else if ($section == 'safeworking' AND $line['showSafeworking'])
	{
		drawLineguideHeaders($line, 'Safeworking Diagram');
		drawAdminEditableLink("/backend/listLineEvents.php?line=".$line['lineLink'], "Edit events");
		drawSafeworkingDiagram($line, $section);
	}
	// track diagram pages for line
	elseif ($section == 'diagram')
	{
		if ($line['showTrack'] AND validateTrackPage($line, $trackPage))
		{
			drawLineguideHeaders($line, 'Track Diagram');
			drawAdminEditableLink("/backend/listLineLocations.php?line=".$line['lineLink'], "Edit locations");
			drawTrackDiagram($line, $section, $trackPage);
		}
		else
		{
			$url = "/lineguide/".$line['lineLink']."/";
			header("Location: ".$url,TRUE,301);
		}
	}
	// 'extra' pages from articles DB
	else
	{
		// get any other extra pages, returns false if none done
		$extraPage = getLineguideExtraPage($line, $section);

		if ($extraPage)
		{
			drawLineguideHeaders($extraPage, $extraPage["header"]);
			drawAdminEditableLink("/backend/editArticles.php?link=".$section, "Lineguide article");
			drawSpecificLine($extraPage, $extraPage["header"]);
			drawLineguideFooters($extraPage);
		}
		// final fall through - redirect to 'home' page for line
		else
		{
			draw404InvalidSubpage("lineguide/$lineToDisplay", "line guide page");
		}
	}
}
?>