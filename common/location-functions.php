<?php


function drawInvalidLocation($locationToFind, $index=1)
{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$pageTitle = "404 Page Not Found";
	include_once(dirname(__FILE__) . "/../common/header.php");
	$message = "<p class=\"error\">Error - Invalid Location!</p>\n";
	drawLocationSearch($locationToFind, $index, $message);
	include_once(dirname(__FILE__) . "/../common/footer.php");
}

function drawDuplicateLocation($recheckresult)
{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$pageTitle = "404 Page Not Found";
	include_once(dirname(__FILE__) . "/../common/header.php");
	echo "<div class=\"locations\">\n";
	echo "<p class=\"error\">Multiple locations by that name found!</p>\n";
	drawLinedLocationsTable(getLocationsOnlyTable($recheckresult, 'line'));
	echo "</div>\n";
	include_once(dirname(__FILE__) . "/../common/footer.php");
}

/*
 * draws a Location to the page
 * give it an array with location data
 */
function drawLocation($location)
{
	$pageTitle = "Locations - ".$location['pageTitle'];
	$pageHeading = "Locations";
	include_once(dirname(__FILE__) . "/../common/header.php");
	include_once(dirname(__FILE__) . "/../common/event-functions.php");
?>
<table class="headbar">
	<tr><td><a href="/">Home</a> &raquo; <a href="/locations">Locations</a> &raquo; <?=$location['pageTitle']?></td>
	<td id="righthead"><? drawHeadbarSearchBox(); ?></td></tr>
</table>
<h3 id="top"><?=$location['pageTitle']?></h3>
<?php
	// working out if dot points section shown or not
	$dotpoints = 0;

	// for table of contents, number of text subsections
	if($location['description'] != "")
	{
		$descriptionTitles = getDescriptionTitles($location['description']);
		$numberOfTextSections = sizeof($descriptionTitles);
		$dotpoints = $dotpoints + $numberOfTextSections;
	}

	// show only location events
	if ( $location['isCrossing'] )
	{
		$locationEvents = getAllLocationLineEvents($location, false);
	}
	// show all events, both location and rail line
	else
	{
		$locationEvents = getAllLocationLineEvents($location, true);
		$dotpoints++;
	}

	$showEvents = (sizeof($locationEvents) > 0);

	// determine if diagrams are to be shown
	if ($location['showDiagrams'] != '')
	{
		$dotpoints++;
	}

	// extra list item wil be spat out later
	if ($location['hasAssociatedLocations'])
	{
		$dotpoints++;
	}

	if ($location["showAerial"])
	{
		$dotpoints++;
	}

	// check to see if photos will be shown
	if (showPhotos($location['photos']))
	{
		include_once(dirname(__FILE__) . "/../common/gallery-functions.php");
		$locationPhotos = getLocationImages($location['photos']);
		$showPhotos = (sizeof($locationPhotos) > 0);
		$dotpoints++;
	}
	else
	{
		$showPhotos = false;
	}

	// find location sources and credits
	$locationSources = getObjectSources('location', $location['id'], $location['credits']);

	echo "<div class=\"locations\">\n";
	
	// get pretty header photo
	$headerPicWasDrawn = drawHeaderPic('location', $location['id'], $location['pageTitle']);

	// draw essential location data
	drawLocationDataTable($location);

	// check if minimum number of dot points spat out
	if ($dotpoints > 2)
	{
		echo "<ul class=\"tableofcontents\">\n";

		if ($numberOfTextSections > 0)
		{
			foreach ($descriptionTitles as $titleItem)
			{
				echo "<li>$titleItem</li>\n";
			}
		}

		if ($showEvents)
		{
			echo "<li><a href=\"#events\">Events</a></li>\n";
		}

		if ($location['showDiagrams'] != '')
		{
			// get diagram data, and save it
			if ($location['showDiagrams'] == 'full' OR $location['showDiagrams'] == 'all')
			{
				$locationDiagrams = getLocationDiagrams($location);
			}

			// test retreived date, and output dot point
			if ($locationDiagrams != '')
			{
				echo "<li><a href=\"#diagrams\">Diagrams</a></li>\n";
			}
		}

		// gallery functions
		if ($showPhotos)
		{
			echo "<li><a href=\"#photos\">Photos</a></li>\n";
		}

		if ($location["showAerial"])
		{
			echo "<li><a href=\"#aerial\">Aerial Photos</a></li>\n";
		}

		if ($location["hasAssociatedLocations"])
		{
			echo "<li><a href=\"#other\">Other Locations</a></li>\n";
		}

		// for location sources and credits
		if ($locationSources != '')
		{
			echo "<li><a href=\"#sources\">Sources</a></li>\n";
		}

		echo "</ul>\n";
	}

	// display description
	if ($location['description'] != "")
	{
		drawFormattedText($location['description']);

		// need to add a break if the sidebar image is being displayed, and not enough text
		if (strlen($location['description']) < 500 AND $headerPicWasDrawn)
		{
			echo "<br clear=\"all\">\n";
		}
	}

	// get location events
	if ($showEvents)
	{
		echo "<h4 id=\"events\">Events</h4><hr/>\n";

		foreach ($locationEvents as $eventType)
		{
			if ($eventType[0] != 'Location')
			{
				echo "<h5>$eventType[0]</h5>\n";
			}
			drawEventsTable($eventType[1]);
		}
		echo "<p><a href=\"#top\" class=\"credit\">Top</a></p>\n";
	}

	// display diagrams depending on earlier settings
	drawLocationDiagrams($locationDiagrams);

	// display gallery if required
	if ($showPhotos)
	{
		drawLocationImages($locationPhotos, $location['photos']);
	}

	// display aerial photos if they exist
	if ($location["showAerial"])
	{
		echo "<h4 id=\"aerial\">Aerial Photos</h4><hr/>\n";

		if ($location["1945AerialUrl"] != '')
		{
			echo "<p>1945 photo map of Melbourne, produced by the Victorian Department of Lands and Survey, now accessable via the <a href=\"http://cat.lib.unimelb.edu.au/record=b2501041\">University of Melbourne</a>:</p>\n";
			$imgsize = getimagesize($_SERVER['DOCUMENT_ROOT'].$location["1945AerialUrl"]);
			echo '<p><img src="'.$location["1945AerialUrl"].'" alt="1945 aerial photo of '.$location['pageTitle'].'" title="1945 aerial photo of '.$location['pageTitle'].'" '.$imgsize[3].' \></p>'."\n";;
		}

		if ($location["morgansUrl"] != '')
		{
			echo "<p>Morgan's Street Directory, circa 1940s:</p>\n";
			$imgsize = getimagesize($_SERVER['DOCUMENT_ROOT'].$location["morgansUrl"]);
			echo '<p><img src="'.$location["morgansUrl"].'" alt="'.$location['pageTitle'].' in the Morgan\'s Street Directory" title="'.$location['pageTitle'].' in the Morgan\'s Street Directory" '.$imgsize[3].' \></p>'."\n";;
		}
	}

	if ($location["hasAssociatedLocations"])
	{
		echo "<h4 id=\"other\">Other Locations</h4><hr/>\n<ul class=\"tableofcontents\">";

		foreach ($location['associatedLocations'] as $associatedLocation)
		{
			echo "<li><a href=\"/location/".$associatedLocation[0]."\">".$associatedLocation[1]."</a></li>\n";
		}

		echo "</ul>\n";
	}

	// draw credits previously formatted by drawLocationSources()
	echo "$locationSources\n";

	drawLocationNeighbourBar($location['nextLocation'], $location['backLocation']);

	echo "</div>\n";

	$lastUpdatedDate = $location['updated'];
	include_once(dirname(__FILE__) . "/../common/footer.php");

} //end function


function drawLocationNeighbourBar($nextLocation, $backLocation)
{
	if ($backLocation != '' OR $nextLocation != '')
	{
?>
<table class="nextables"><tr>
	<td><?=$backLocation; ?><?=$nextLocation;?></td>
</tr></table>
<?
	}
}	// end function

/*
 * draws all track diagrams given an array from "getLocationDiagrams"
 */
function drawLocationDiagrams($diagramData)
{
	$numberOfRows = sizeof($diagramData);
	if ($diagramData == '')
	{
		return;
	}
?>
<h4 id="diagrams">Diagrams</h4><hr />
<p>NOTE: Diagrams are not to scale.</p>
<?	if ($numberOfRows > 1)
	{
		drawDiagramTabs($diagramData);
	}
	// end "if more than one diagram"
	// if there is only ever one image
	elseif ($numberOfRows == 1)
	{
?>
<p><img src="/t/<? echo $diagramData[0][0].'.gif'; ?>" alt="<? echo $name.' '.$diagramData[0][1]; ?>" title="<? echo $name.' '.$diagramData[0][1]; ?>" /></p>
<?
	}	// end if
	?>
<p><a href="#top" class="credit">Top</a></p>
<?
}	// end function



function drawMainPage()
{
?>
<table class="headbar">
	<tr><td><a href="/">Home</a> &raquo; Locations</td>
	<td id="righthead"><? drawHeadbarSearchBox(); ?></td></tr>
</table>
<h3>Introduction to the locations database</h3>
<div class="locations">
<p>Here is a listing of all the railway locations in the Geelong Region. Either view by type, or search by name. You can also browse by line from the <a href="/lineguide/">lineguides</a>. The sort order can be altered in all cases.</p>
<h4>By Type</h4>
<ul class="tableofcontents"><li><a href="/locations/stations">Stations</a></li>
<li><a href='/locations/industries'>Industries</a></li>
<li><a href='/locations/signalboxes'>Signal Boxes</a></li>
<li><a href='/locations/yards'>Yards</a></li>
<li><a href='/locations/misc'>Miscellaneous</a></li></ul>
</div>
<?
	drawLocationSearchBox();
?>
<h4>About the Location listings</h4>
<hr/>
<p>Each location in the database can have a written history, tables of location and line events, track diagrams, and photographs. The listings show what information is available to view for each location.</p>
<p>The 'star guide' shows how detailed each location history is:<br/><br/>
<img src="/images/rank5.gif" alt="Essay" title="Essay" /> Essay<br/><br/>
<img src="/images/rank4.gif" alt="Very Detailed" title="Very Detailed" /> Very Detailed<br/><br/>
<img src="/images/rank3.gif" alt="Detailed" title="Detailed" /> Detailed<br/><br/>
<img src="/images/rank2.gif" alt="Beginning" title="Beginning" /> Beginning<br/><br/>
<img src="/images/rank1.gif" alt="Basic" title="Basic" /> Basic<br/><br/>
I recommend having a look at the page on <a href="/location/south-geelong">South Geelong</a> for an example of a detailed page.  ;-)</p>
<?
} //end function

function drawLocationSearchBox()
{
?>
<h4>By Name</h4>
<form name="SearchForm" method="get" action="/locations/">
<p><label for="search"><b>Location : </b></label>
<input type="text" name="search" id="search" value="" />
<input type="submit" value="Search" />
</p>
</form>
<?
} //end function


function drawLocationSearch($locationSearch, $searchPageNumber, $message="")
{
	$maxRecordsPerPage = 50;
	$searchPageNumber--;

	if($locationSearch == '')
	{
		return;
	}

	if ($searchPageNumber == '' OR $searchPageNumber < 0 OR !is_numeric($searchPageNumber))
	{
		$index = 0;
	}
	else
	{
		$index = $searchPageNumber*$maxRecordsPerPage;
	}

	$queryBaseSQL = sprintf("FROM locations l
	INNER JOIN locations_raillines lr ON lr.location_id = l.location_id
	INNER JOIN raillines r  ON r.line_id = lr.line_id
	INNER JOIN location_types lt ON lt.type_id = l.type
	LEFT OUTER JOIN locations ol ON l.name = ol.name
	WHERE r.todisplay != 'hide'  AND l.name != '' AND l.display != 'tracks' AND l.type != 18
	AND ".SQL_NEXTABLE." AND l.name like '%s'", mysql_real_escape_string("%$locationSearch%"));

	$queryLimitSQL = sprintf(" GROUP BY l.location_id ORDER BY l.location_id, l.name ASC LIMIT %s, %s",
		mysql_real_escape_string($index), mysql_real_escape_string($maxRecordsPerPage));

	$result = MYSQL_QUERY("SELECT l.location_id, l.name, r.name, l.type,
		length(l.description) AS description_length, l.photos, l.events, lr.line_id, lt.basic,
		count(l.location_id) AS unique_name ".$queryBaseSQL.$queryLimitSQL, locationDBconnect());
	$numberOfRecords = MYSQL_NUM_ROWS($result);

	$resultMaxRows = MYSQL_QUERY("SELECT count(l.location_id) ".$queryBaseSQL." GROUP BY l.location_id", locationDBconnect());
	$totalNumberOfRecords = MYSQL_NUM_ROWS($resultMaxRows);
	?>
<table class="headbar">
	<tr><td><a href="/">Home</a> &raquo; <a href="/locations">Locations</a> &raquo; Location search</td>
	<td id="righthead"><? drawHeadbarSearchBox(); ?></td></tr>
</table>
<h3>Location search results</h3>
<?php

	if ($message)
	{
		echo $message;
	}

	if ($numberOfRecords == 0)
	{
		echo '<p>Sorry. No locations by that name found!</p>';
		drawLocationSearchBox();
	}
	else
	{
		include_once(dirname(__FILE__) . "/../common/location-lineguide-functions.php");
		
		// display number of results
		$extraBit = ', locations '.drawNumberCurrentDispayedRecords($maxRecordsPerPage,$numberOfRecords,$searchPageNumber);
		echo "<p>$totalNumberOfRecords results found for \"".stripslashes($locationSearch)."\"$extraBit.</p>\n";

		// draw the actual results
		drawLinedLocationsTable(getLocationsOnlyTable($result, 'search', $locationSearch));

		// draw navigation links
		drawNextAndBackLinks($index, $totalNumberOfRecords, $maxRecordsPerPage, '?search='.$locationSearch.'&page=', true);
	}

} //end function


function drawLocationDataTable($location)
{
	extract($location);

	if(!$stillOpen)
	{
		$opentest = substr($openPlain, 0, 4);
		if ($opentest == 0001)
		{
			$extraUrlYear = '/year-'.(substr($closePlain, 0, 4)-1);
		}
		else
		{
			$extraUrlYear = '/year-'.$opentest;
		}
	}
?>
<div class="datatable">
<?
	if ($typeToDisplay == 'Miscellaneous')
	{
?>
	<b>Type: </b><a href="/locations/misc">Miscellaneous</a><br/>
<?
	}
	else
	{
?>
	<b><?=$typeToDisplay?></b><br/>
<?
		if (sizeof($branchlines) > 0)
		{
			$lineLinkText = getLineLinksForLocation($branchlines);
			echo $lineLinkText['main'];
			echo $lineLinkText['branch'];
		}
		else
		{
?>
	<b>Line: </b><a href="/lineguide/<?=$lineLink?>"><?=$lineName?></a><br/>
<?
		}
		
		if (!$hideKm)
		{
			if ($location['trackSubpageCount'])
			{
				include_once(dirname(__FILE__) . "/../common/location-lineguide-functions.php");
				$extraPageBounds = getLineguideDistanceURL($location['trackSubpage'], $location['km']);
			}
?>
	<b>Distance from Melbourne: </b><?=formatDistance($km, $kmAccuracy)?><?=$lineLinkText['distance'];?><br/>
	<b>Track Diagram: </b><a href="/lineguide/<?=$lineLink?>/diagram<?=$extraPageBounds.$extraUrlYear?>/#km<?=$km?>">View</a><br/>
<?
		}
	}	// end 'Miscellaneous' if statement

	if ($coords != '' AND $coords != '0')
	{
?>
	<b>Google Maps: </b>
		<a href="/aerial.php?view=satellite&id=<?=$id?>"  onClick="p(this.href); return false;">Satellite</a>&nbsp;/&nbsp;
		<a href="/aerial.php?view=map&id=<?=$id?>"  onClick="p(this.href); return false;">Map</a><br/>
<?
	}

	if (!$isCrossing AND $openPlain != DATE_UNKNOWN_OPEN)
	{
?>
	<b>Opened: </b><?=formatDate($open, $approxOpen)?><br/>
<?
	}

	if(!$isCrossing  AND !$stillOpen)
	{
?>
	<b>Closed: </b><?=formatDate($close, $approxClose)?><br/>
<?
	}

	global $editablelinkforadmin;
	if ($editablelinkforadmin)
	{
		echo "<b>Edit: </b><a href=\"/backend/editLocations.php?location=$id\" target=\"_new\">ID=$id</a><br/>\n";
	}
?>
</div>
<?

}	// end function

function getLineLinksForLocation($branchlines)
{
	$size = sizeof($branchlines);
	$initialKm = $branchlines[0]['km'];

	$mainText = $branchText = '';
	$mainCount = $branchCount = 0;

	for ($i = 0; $i < $size; $i++)
	{
		if ($branchlines[$i]['type'] == 'main')
		{
			if ($mainCount != 0)
			{
				$mainText .= ",&nbsp;";
			}

			$mainText .= '<a href="/lineguide/'.$branchlines[$i]['link'].'">'.$branchlines[$i]['name']."</a>";
			$mainCount++;
		}
		else
		{
			if ($branchCount != 0)
			{
				$branchText .= ",&nbsp;";
			}

			$branchText .= '<a href="/lineguide/'.$branchlines[$i]['link'].'">'.$branchlines[$i]['name']."</a>";

			if ($branchlines[$i]['km'] != $initialKm)
			{
				$branchDistanceText .= " (".$branchlines[$i]['km']."km via ".$branchlines[$i]['name'].")";
			}

			$branchCount++;
		}
	}

	if ($mainCount == 1)
	{
		$lineLinkText['main'] = "\t<b>Line:</b>&nbsp;$mainText<br>\n";
	}
	else if ($mainCount > 1)
	{
		$lineLinkText['main'] = "\t<b>Lines:</b>&nbsp;$mainText<br>\n";
	}

	if ($branchText != '')
	{
		$branchTextLength = strlen($branchText);
		$branchText = substr($branchText, 0, $branchTextLength-4);
		$branchText = "\t<b>Junction with:</b> ".getLineName($branchText);

		if ($branchCount != 1)
		{
			$branchText .= "s";
		}

		$lineLinkText['branch'] = $branchText."</a><br/>\n";
		$lineLinkText['distance'] = $branchDistanceText;
	}

	return $lineLinkText;

}	// end function
?>