<?php
include_once("common/dbConnection.php");
include_once("common/lineguide-functions.php");
include_once("common/lineguide-database-functions.php");
include_once("common/event-functions.php");
include_once("common/source-functions.php");
include_once("common/formatting-functions.php");
include_once("common/map-functions.php");

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
?>
<h4>Introduction to the lineguides</h4>
<img class="photo-right" src="/images/geelong-region.gif" alt="Geelong Region Railway Lines" title="Geelong Region Railway Lines" usemap="#linemap" height="402" width="500" />
<map name="linemap" id="linemap">
<!-- <area shape=poly coords="214,259,118,277,11,232,19,218,211,252,214,259" href="" alt="Maroona Line" title="Maroona Line"> -->
<area shape=poly coords="246,282,278,273,278,263,243,262,246,282" href="lineguide.php?line=fyansford" alt="Fyansford Line" title="Fyansford Line">
<area shape=poly coords="300,276,283,276,282,287,298,291,301,277,300,276" href="lineguide.php?line=cunningham" alt="Cunningham Pier Line" title="Cunningham Pier Line">
<!--<area shape=poly coords="103,2,145,31,222,240,277,258,276,264,218,258,90,13,103,2" href="lineguide.php?line=ballarat" alt="Geelong - Ballarat Line" title="Geelong - Ballarat Line">-->
<area shape=poly coords="394,336,283,307,285,289,373,287,399,323,394,337,394,336" href="lineguide.php?line=queenscliff" alt="Queenscliff Line" title="Queenscliff Line">
<area shape=poly coords="60,378,277,349,290,248,499,143,500,75,278,221,277,287,54,315,60,378" href="lineguide.php?line=geelong" alt="Melbourne - Geelong - Warrnambool Line" title="Melbourne - Geelong - Warrnambool Line">
</map>

<ul><li>Track diagrams</li>
<li>Safeworking diagrams</li>
<li>Line events</li>
<li>Location histories</li></ul>
<p>They're all here.<br/><br/>
And the diagrams change according to the year you want to see.<br/><br/>
Choose a line on the map to the right, or a link from below to start.</p>

<h4 style="clear:both;" >The Lines...</h4><hr/>
<?
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
			$googleHeader = 'article';
			$googleHeaderKMLscript = generateKMLScript('kml-' . $line['lineId'] . '.kml');

			$pageTitle = $pageHeading = getLineName($line['lineName'])." Guide";
			$pageTitle = "$pageTitle - Google Map";
			include_once("common/header.php");
			drawLineguideHeadbar($line);

			echo "<div id=\"lineguide\">\n";
			echo "<h3>Google Map</h3>\n";
			echo '<div id="map" class="inlinemap"></div>';;
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