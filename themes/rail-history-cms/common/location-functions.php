<?php


function drawInvalidLocation($locationToFind, $index=1)
{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$pageTitle = "404 Page Not Found";
	global $_zp_themeroot;	
	require_once("header.php");
	$message = "<br clear=\"all\"><p class=\"error\">Error - Invalid Location!</p>\n";
	drawLocationSearch($locationToFind, $index, $message);
	require_once("footer.php");
}

function drawDuplicateLocation($recheckresult)
{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$pageTitle = "404 Page Not Found";
	global $_zp_themeroot;	
	require_once("header.php");
	echo "<div class=\"locations\">\n";
	echo "<br clear=\"all\"><p class=\"error\">Multiple locations by that name found!</p>\n";
	drawLinedLocationsTable(getLocationsOnlyTable($recheckresult, 'line'), 'search');
	echo "</div>\n";
	require_once("footer.php");
}

/*
 * draws a Location to the page
 * give it an array with location data
 */
function drawLocation($location)
{
	$pageTitle = "Locations - ".$location['pageTitle'];
	$pageHeading = "Locations";
	$canonical = getLocationCanonicalUrl($location);
	global $_zp_themeroot;	
	require_once("header.php");
	require_once("event-functions.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/locations/">Locations</a> &raquo; <?php echo $location['pageTitle']?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once("midbar.php"); ?>
<h3 id="top"><?php echo $location['pageTitle']?></h3>
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
	if (showPhotosConfigured($location['photos']))
	{
		require_once("linked-photo-functions.php");
		$showPhotos = getLinkedPhotoCount($location['photos']);
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
	
	// get diagram data, and save it
	$locationDiagrams = null;
	if ($location['showDiagrams'] == 'full' OR $location['showDiagrams'] == 'all')
	{
		$locationDiagrams = getLocationDiagrams($location);
	}

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

		// test retreived date, and output dot point
		if ($locationDiagrams != null)
		{
			echo "<li><a href=\"#diagrams\">Diagrams</a></li>\n";
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
			echo "<li><a href=\"#other\">Related Locations</a></li>\n";
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
	if (isset($locationDiagrams))
	{
		drawLocationDiagrams($locationDiagrams);
	}

	// display gallery if required
	if ($showPhotos)
	{
		drawLinkedPhotosFromGallery();
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
		echo "<h4 id=\"other\">Related Locations</h4><hr/>\n<ul class=\"tableofcontents\">";

		foreach ($location['associatedLocations'] as $associatedLocation)
		{
			echo "<li><a href=\"/location/".$associatedLocation[0]."/\">".$associatedLocation[1]."</a></li>\n";
		}

		echo "</ul>\n";
	}

	// draw credits previously formatted by drawLocationSources()
	echo "$locationSources\n";

	drawLocationNeighbourBar($location['nextLocation'], $location['backLocation']);

	echo "</div>\n";

	$lastUpdatedDate = $location['updated'];
	require_once("footer.php");

} //end function


function drawLocationNeighbourBar($nextLocation, $backLocation)
{
	if ($backLocation != '' OR $nextLocation != '')
	{
?>
<div class="pagelist">
	<?php echo $backLocation; ?><?php echo $nextLocation;?>
</div>
<?php
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
<?php if ($numberOfRows > 1)
	{
		drawDiagramTabs($diagramData);
	}
	// end "if more than one diagram"
	// if there is only ever one image
	elseif ($numberOfRows == 1)
	{
?>
<p><img src="/t/<?php echo $diagramData[0][0].'.gif'; ?>" alt="<?php echo $name.' '.$diagramData[0][1]; ?>" title="<?php echo $name.' '.$diagramData[0][1]; ?>" /></p>
<?php
	}	// end if
	?>
<p><a href="#top" class="credit">Top</a></p>
<?php
}	// end function


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
<?php
} //end function


function drawLocationSearch($locationSearch, $searchPageNumber, $message="")
{
	$maxRecordsPerPage = 50;
	$searchPageNumber--;

	if($locationSearch != '')
	{
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
		AND ".SQL_NEXTABLE." AND l.name like %s", db_quote("%$locationSearch%"));
	
		$queryLimitSQL = sprintf(" GROUP BY l.location_id ORDER BY l.location_id, l.name ASC LIMIT %s, %s",
			($index), ($maxRecordsPerPage));
	
		$result = query_full_array("SELECT l.location_id, l.name AS name, r.name AS linename, l.type, l.link, 
			length(l.description) AS description_length, l.photos, l.events, lr.line_id, lt.basic ".$queryBaseSQL.$queryLimitSQL);
		$numberOfRecords = sizeof($result);
	
		$resultMaxRows = query_full_array("SELECT l.location_id ".$queryBaseSQL." GROUP BY l.location_id");
		$totalNumberOfRecords = sizeof($resultMaxRows);
	}
	else
	{
		$totalNumberOfRecords = $numberOfRecords = 0;
	}
	?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/locations/">Locations</a> &raquo; Location search</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php
	require_once("midbar.php");

	if ($numberOfRecords > 0)
	{
		echo "<h3>Location search results</h3>\n";
	}

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
		require_once("location-lineguide-functions.php");
		
		// display number of results
		$extraBit = ', locations '.drawNumberCurrentDisplayedRecords($maxRecordsPerPage, $numberOfRecords, $searchPageNumber, null);
		echo "<p>$totalNumberOfRecords results found for \"".stripslashes($locationSearch)."\"$extraBit.</p>\n";

		// draw the actual results
		drawLinedLocationsTable(getLocationsOnlyTable($result, 'search', $locationSearch), 'search');

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
	else
	{
		$extraUrlYear = "";
	}
?>
<div class="datatable">
<?php
	if ($typeToDisplay == 'Miscellaneous')
	{
?>
	<b>Type: </b><a href="/locations/misc">Miscellaneous</a><br/>
<?php
	}
	else
	{
?>
	<b><?php echo $typeToDisplay?></b><br/>
<?php
		if (isset($branchlines) && sizeof($branchlines) > 0)
		{
			$lineLinkText = getLineLinksForLocation($branchlines);
			echo $lineLinkText['main'];
			echo isset($lineLinkText['branch']) ? $lineLinkText['branch'] : "";
			$lineLinkDistance = isset($lineLinkText['distance']) ? $lineLinkText['distance'] : "";
		}
		else
		{
			$lineLinkDistance = "";
?>
	<b>Line: </b><a href="/lineguide/<?php echo $lineLink?>/"><?php echo $lineName?></a><br/>
<?php
		}
		
		if (!$hideKm)
		{
			if ($location['trackSubpageCount'])
			{
				require_once("location-lineguide-functions.php");
				$extraPageBounds = getLineguideDistanceURL($location['trackSubpage'], $location['km']);
			}
?>
	<b>Distance from Melbourne: </b><?php echo formatDistance($km, $kmAccuracy)?><?php echo $lineLinkDistance;?><br/>
	<b>Track Diagram: </b><a href="/lineguide/<?php echo $lineLink?>/diagram<?php echo $extraPageBounds.$extraUrlYear?>/#km<?php echo $km?>">View</a><br/>
<?php
		}
	}	// end 'Miscellaneous' if statement

	if ($coords != '' AND $coords != '0')
	{
?>
	<b>Google Maps: </b>
		<a href="/location/<?php echo $id?>/satellite/" onClick="p(this.href); return false;">Satellite</a>&nbsp;/&nbsp;
		<a href="/location/<?php echo $id?>/map/" onClick="p(this.href); return false;">Map</a><br/>
<?php
	}

	if (!$isCrossing AND $openPlain != DATE_UNKNOWN_OPEN)
	{
?>
	<b>Opened: </b><?php echo formatDate($open, $approxOpen)?><br/>
<?php
	}

	if(!$isCrossing  AND !$stillOpen)
	{
?>
	<b>Closed: </b><?php echo formatDate($close, $approxClose)?><br/>
<?php
	}
	drawAdminEditableLink("editLocations.php?location=$id", "ID=$id");
?>
</div>
<?php

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

			$mainText .= '<a href="/lineguide/'.$branchlines[$i]['link'].'/">'.$branchlines[$i]['name']."</a>";
			$mainCount++;
		}
		else
		{
			if ($branchCount != 0)
			{
				$branchText .= ",&nbsp;";
			}

			$branchText .= '<a href="/lineguide/'.$branchlines[$i]['link'].'/">'.$branchlines[$i]['name']."</a>";

			$branchDistanceText = "";
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

function getLocationCanonicalUrl($location)
{
	if (strlen($location['locationLink']))
	{
		$link = $location['locationLink'];
	}
	else
	{
		$link = $location['id'];		
	}
	
	return "http://www.railgeelong.com/location/$link/";	
}	// end function


?>