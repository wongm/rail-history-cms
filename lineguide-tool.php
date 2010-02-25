<?php 
include_once("common/dbConnection.php");
include_once("common/lineguide-functions.php");
include_once("common/lineguide-database-functions.php");
include_once("common/event-functions.php");
include_once("common/source-functions.php");
include_once("common/formatting-functions.php");

$lineToDisplay = 	$_REQUEST['line'];
$sort = 			$_REQUEST['sort'];
$yearToDisplay = 	$_REQUEST['year'];
$trackPage = 		$_REQUEST['page'];
$section= 			$_REQUEST['section'];
$sort = str_replace('by-', '', $sort);
$yearToDisplay = str_replace('year-', '', $yearToDisplay);

/* General list all thingy */
if ($lineToDisplay == '')
{
	$pageTitle = "Line Guides";
	include_once("common/header.php");
	echo getConfigVariable('lineguide');
	drawAllLineguideDotpoints(false);
	include_once("common/footer.php");
}
/* For specific line */
else
{
	$line = getLine($lineToDisplay, $yearToDisplay);
	
	if ($line['lineId'] == '')
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
			include_once('common/location-lineguide-functions.php');
			drawLineguideHeaders($line, 'Locations');
			drawAdminEditableLink("/backend/listLineLocations.php?line=".$line['lineLink'], "Edit locations");
			echo "<h3>Locations</h3>\n";
			drawLinedLocationsTable(getLocationsTable($line['lineId'], $lineToDisplay, '', '', $sort));
			drawLineguideFooters($line);
		}
		// lineguide Google map	
		elseif ($section == 'map' AND $line['showGoogleMap'])
		{
			include_once("common/aerial-functions.php");
			drawLineguideHeaders($line, 'Google Map');
			drawLineguideGoogleMap($line);
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
}
?>