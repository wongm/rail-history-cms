<?php

include_once(dirname(__FILE__) . "/../common/lineguide-database-functions.php");

function drawLineguideHeaders($line, $section='')
{
	$pageTitle = $pageHeading = getLineName($line['lineName'])." Guide";

	if ($section != '') {		
		$pageTitle = "$pageTitle - $section";
	}
	if (strtolower($section) == 'google map') {
		$googleHeader = true;
	}
	
	include_once(dirname(__FILE__) . "/../common/header.php");
	?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/lineguides">Line Guides</a> &raquo; <a href="/lineguide/<?php echo $line['lineLink'] ?>"><?php echo getLineName($line['lineName']) ?></a></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once(dirname(__FILE__) . "/../common/midbar.php"); ?>
<div id="lineguide">
<?php
}

function drawLineguideFooters($line, $section='')
{	
	//draw the credits if they exist
	if (strtolower($section) != 'google map') {
		if ($line['fullsources'] == '')
		{
			$line['fullsources'] = getObjectSources('railline', $line['lineId'], $line['credits']);
		}

		echo $line['fullsources'];
	}
	echo "</div>\n";

	$lastUpdatedDate = $line['updated'];
	
	// set parent item for nav.php
	global $pageNavigation;
	$pageNavigation = getLineguideNavigation($line);
	
	include_once(dirname(__FILE__) . "/../common/footer.php");
}

function drawSafeworkingDiagram($line, $section)
{
	$line = checkLineguideDiagramYears($line);
	$diagramData = getLineDiagram($line, $section, $trackPage);

	// test if an error was encounted, then display it
	if (sizeof($diagramData) > 1)
	{
		echo "<h3>Safeworking diagram - ".$line['yearHeader']."</h3>\n";
		echo "<p>Displays both the number of tracks and the safeworking system in use for a given year. ".$line['safeworkingDiagramNote']."</p>\n";
		drawInterestingYears($line['safeworkingYears'], $line['yearToDisplay'], $line['lineLink'], $section);
		drawLineDiagram($diagramData);
		drawSafeworkingLegend();
	}
	else
	{
		echo $diagramData;
	}
	drawLineguideFooters($line);
}

function validateTrackPage($line, $trackPage)
{
	// check to see if no page number passed in
	// in cases where the pages should be shown
	if ($trackPage == '' AND $line['trackSubpage'] == '')
	{
		return true;
	}
	else if ($trackPage != '' AND $line['trackSubpageCount'] > 1)
	{
		return true;
	}
	else if (is_numeric($trackPage) OR ($trackPage == '' AND $line['trackSubpageCount'] > 1)
		OR $trackPage > $line['trackSubpageCount']
		OR (!is_numeric($trackPage) AND $trackPage != '')
		OR $trackPage < 1)
	{
		draw404InvalidSubpage('lineguide/'.$line['lineLink'], "lineguide diagram page");
		return false;
	}
}

function drawTrackDiagram($line, $section, $trackPage)
{
	$line = checkLineguideDiagramYears($line);
	$diagramData = getLineDiagram($line, $section, $trackPage);

	// test if an error was encounted, then display it
	if (sizeof($diagramData) > 1)
	{
		if ($trackPage != '')
		{
			$subpageheader = " page $trackPage";
		}

		echo "<h3>Track diagram $subpageheader - ".$line['yearHeader']."</h3>\n";
		echo "<p>Displays the track layout for the line for a given year. To approximate scale. ".$line['trackDiagramNote']."</p>\n";
		drawInterestingYears($line['trackYears'], $line['yearToDisplay'], $line['lineLink'], $section);
		drawLineDiagram($diagramData);
	}
	else
	{
		echo $diagramData;
	}
	drawLineguideFooters($line);
}

function drawLineguideDiagramTabs($line)
{
	drawLineguideHeaders($line, 'Diagrams');
	echo "<h4>Track Diagrams</h4>\n";
	$trackDiagramTabs = split(';', $line['trackDiagramTabs']);

	for ($i = 0; $i < sizeof($trackDiagramTabs); $i++)
	{
		$year = $trackDiagramTabs[$i];
		$diagramData[] = array($line['lineLink'].'-'.$year,$line['lineName'].' '.$year,$year);
	}

	drawDiagramTabs($diagramData);
	drawLineguideFooters($line);
}

/*
 * Draws the table thingy of the interesting years for each line
 */
function drawInterestingYears($interestingYears, $yearToDisplay, $lineToDisplay, $section)
{
	if(!is_numeric($yearToDisplay) OR $yearToDisplay == "")
	{
		$yearToDisplay = '';
	}

	$diagramPage = $_REQUEST['page'];
	if (is_numeric($diagramPage))
	{
		$pageLink = "/page-$diagramPage";
	}

	$interestingYears = split(';', $interestingYears);
	$interestingYearsLength = sizeOf($interestingYears);

	if ($interestingYearsLength > '1')
	{
?>
<form name="pickYearForm" method="get" action="/lineguide.php">
<table class="lineyears"><tr>
<?php

	for ($i = 0; $i<$interestingYearsLength; $i++)
	{
?>
	<td><a href="/lineguide/<?php echo $lineToDisplay.'/'.$section.$pageLink.'/year-'.$interestingYears[$i].'">'.$interestingYears[$i].'</a>';?></td>
<?php
	}	// end while
?>
	<td><a href="/lineguide/<?php echo $lineToDisplay.'/'.$section.$pageLink.'">Reset View</a>';?></td>
	<td><label for="year">Year? </label><input type="text" name="year" id="year" size="4" onclick="highlight(this);" value="<?php echo $yearToDisplay;?>" />
	<input type="submit" value="Go!" /></td>
</tr></table>
<?php
	if ($pageLink != '')
	{
?>
<input type="hidden" id="page" name="page" value="<?php echo $diagramPage?>" />
<?php
	}
?>
<input type="hidden" id="line" name="line" value="<?php echo $lineToDisplay;?>" />
<input type="hidden" id="section" name="section" value="<?php echo $_REQUEST['section'];?>" />
</form>
<?php
	}	// end zero years if
}		// end function

function drawLocationTitle($thisUrl, $id, $thisName, $thisPhoto)
{
	// add the id if more than one location by same name exists
	// yards don't count as they have many leads to them
	if ($thisUrl != '' OR $id == '')
	{
		$base = convertToLink($thisName);
	}
	elseif (!isUniqueLocationName($thisName))
	{
		$base = $id;
	}

	if (showPhotos($thisPhoto))
	{
		$info = '<td><img src="/images/photos.gif" alt="Photo" title="Photo" /></td>';
	}

	if ($thisUrl != '')
	{
		if ($info != '')
		{
			return '<table><tr>'.$info.'<td><b><a href="/location/'.$base.'">'.$thisName.'</a></b></td></tr></table>';
		}
		else
		{
			return '<b><a href="/location/'.$base.'">'.$thisName.'</a></b>';
		}
	}
	else
	{
		return $thisName;
	}

}	// end function


function drawLineDiagram($diagramdata)
{
	$numberOfRows =  sizeof($diagramdata);
?>
<table class="diagram" border="0" cellspacing="0" cellpadding="0">
<?php

	for ($i = 0; $i < $numberOfRows; $i++)
	{
?>
<tr>
	<?php echo $diagramdata[$i][0]."\n";?>
	<?php echo $diagramdata[$i][1]."\n";?>
	<?php echo $diagramdata[$i][2]."\n";?>
</tr>
<?php
	}	// end for loop
?>
</table>
<?php

}	// end function






function drawSafeworkingLegend()
{	?>
<h4>Legend</h4><hr/>
<table id="legend">
<tr><td><img src="/images/safeworking/staff+ticket-square.gif" height="20" width="20" alt="" /></td><td>Staff and Ticket</td>
<td><img src="/images/safeworking/atc-square.gif" height="20" width="20" alt="" /></td><td>Automatic &amp; Track Control</td>
<td><img src="/images/safeworking/dlb-square.gif" height="20" width="20" alt="" /></td><td>Double Line Block</td></tr>

<tr><td><img src="/images/safeworking/les-square.gif" height="20" width="20" alt="" /></td><td>Large Electric Staff</td>
<td><img src="/images/safeworking/ctc-square.gif" height="20" width="20" alt="" /></td><td>Centralised Train Control</td>
<td><img src="/images/safeworking/tb-square.gif" height="20" width="20" alt="" /></td><td>Track Block</td></tr>

<tr><td><img src="/images/safeworking/mes-square.gif" height="20" width="20" alt="" /></td><td>Miniature Electric Staff</td>
<td><img src="/images/safeworking/saw-square.gif" height="20" width="20" alt="" /></td><td>Section Authority Working</td>
<td><img src="/images/safeworking/yard-limits-square.gif" height="20" width="20" alt="" /></td><td>Yard Limits</td></tr>

<tr><td><img src="/images/safeworking/composite-es-square.gif" height="20" width="20" alt="" /></td><td>Composite Electric Staff</td>
<td><img src="/images/safeworking/to-square.gif" height="20" width="20" alt="" /></td><td>Train Order Working</td>
<td><img src="/images/safeworking/tablet-square.gif" height="20" width="20" alt="" /></td><td>Tablet Working</td></tr>
</table>
<a href="/safeworking.php">What do they mean?</a>
<?php }	//end function


// for use on lineguide pages
// sets child items for use in nav.php
function getLineguideNavigation($line)
{
	$itemsToDisplay = getLineguidePages($line, 'headbar');
	$pageTitle = getLineName($line['lineName'])." History";
	
	// initial home link
	$pageNavigation['regions'] = $line['regions'];
	$pageNavigation[0]['url'] = "/lineguide/".$line['lineLink'];
	$pageNavigation[0]['title'] = getLineName($line['lineName']);
	
	// loop through the different pages
	if (sizeof($itemsToDisplay) > 1)
	{
		$i = 0;
		
		foreach ($itemsToDisplay as $singleline)
		{
			$url = $_REQUEST['section'];
			$url = str_replace('-by-date', '', $url);
			if (isset($_REQUEST['page']))
			{
				$url .= "/page-".$_REQUEST['page'];
			}

			// check to see what page we are on, so don't show link
			if ($url == $singleline[0] OR $singleline[0] == '')
			{
				if (strlen($singleline[1]) > 0)
				{
					$pageNavigation[0][$i]['url'] = "/lineguide/".$line['lineLink']."/".$singleline[0];
					$pageNavigation[0][$i]['title'] = $singleline[1];
					//$lineguideMenuHTML .= " :: <span>".$singleline[1]."</span>\n";
					//$currentBreadcrumbTitle = " &raquo; " . $singleline[1];
				}
			}
			else
			{				
				$pageNavigation[0][$i]['url'] = "/lineguide/".$line['lineLink']."/".$singleline[0];
				$pageNavigation[0][$i]['title'] = $singleline[1];
				//$lineguideMenuHTML .= " :: <a href=\"/lineguide/".$line['lineLink']."/".$singleline[0]."\">".$singleline[1]."</a>\n";
			}
			
			$i++;
			
		}
	}

	return $pageNavigation;
	//echo $lineguideMenuHTML .= "</td></tr></table>\n";
}	//end function

function drawSpecificLine($line, $contentsHeader = 'Contents')
{
	$section = $_REQUEST['section'];

	// check to see if photos will be shown
	if (showPhotos($line['photos']))
	{
		include_once(dirname(__FILE__) . "/../common/gallery-functions.php");
		$linePhotos = getLocationImages($line['photos']);
		$showPhotos = (sizeof($linePhotos) > 0);
	}
	else
	{
		$showPhotos = false;
	}

	// fix for multiple subpages and their own lead images
	if ($section != '')
	{
		$imglink = $line['lineLink'].'-'.$section;
	}
	else
	{
		$imglink = $line['lineLink'];
	}

	$headbarTabs = getLineguidePages($line);
	$totalTabs = sizeof($headbarTabs);
	$descriptionTabs = getDescriptionTitles($line['description']);
	$totalTabs +=  sizeof($descriptionTabs);

	$line['fullsources'] = getObjectSources('railline', $line['lineId'], $line['credits']);

	if ($line['caption'] != '')
	{
		echo "<img class=\"photo-right\" src=\"/images/lineguide/$imglink.jpg\" alt=\"".$line['caption']."\" title=\"".$line['caption']."\"/>\n";
	}

	// only draw TOC of more than one tab...
	if ($totalTabs > 1)
	{
		echo "<h3 id=\"top\">$contentsHeader</h3>\n<ul>\n";

		for ($i = 0; $i < sizeof($descriptionTabs); $i++)
		{
?>
	<li><?php echo $descriptionTabs[$i]?></li>
<?php
		}
		if ($showPhotos)
		{
			echo "<li><a href=\"#photos\">Photos</a></li>\n";
		}
		if ($line['fullsources'] != '')
		{
			echo "<li><a href=\"#sources\">Sources</a></li>\n";
		}

		echo "</ul>\n";
	}
	// end no TOC if

	drawFormattedText($line['description']);

	if ($showPhotos)
	{
		drawLocationImages($linePhotos);
	}
}	// end function



/*
 * just the basic details required to decide if it is shown
 */
function getBasicLocationForLineguide($databaseresult, $index)
{
	// retreive from DB
	$location['location_id'] = MYSQL_RESULT($databaseresult,$index,"location_id");
	$location['link'] = MYSQL_RESULT($databaseresult,$index,"link");
	$location['name'] = stripslashes(MYSQL_RESULT($databaseresult,$index,"name"));
	$location['line_id'] = MYSQL_RESULT($databaseresult,$index,"line_id");
	$location['tracks'] = MYSQL_RESULT($databaseresult,$index,"tracks");
	$location['display'] = MYSQL_RESULT($databaseresult,$index,"display");
	$location['type'] = MYSQL_RESULT($databaseresult,$index,"type");
	$location['image'] = MYSQL_RESULT($databaseresult,$index,"image");
	$location['km'] = MYSQL_RESULT($databaseresult,$index,"km");
	$location['km_accuracy'] = MYSQL_RESULT($databaseresult,$index,"kmaccuracy");
	$location['km_formatted'] = formatDistance($location['km'], $location['km_accuracy']);
	$location['photo'] = MYSQL_RESULT($databaseresult,$index,"photos");
	$location['event'] = MYSQL_RESULT($databaseresult,$index,"events");
	$location['description_length'] = strlen(MYSQL_RESULT($databaseresult,$index,"description"));

	// fix up URL link to location, done in getLocationUrlLForLineguide if required later
	$location['url'] = MYSQL_RESULT($databaseresult,$index,"url");

	return $location;
}	//end function


/*
 * more complex, formatting related ones, creates a DB hit for each time accessed!
 */
function getFullLocationForLineguide($location)
{
	$url = $location['url'];
	
	// check if unique name
	if (strlen($location['link']))
	{
		$urlbase = $location['link'];
	}
	else
	{
		$urlbase = $location['location_id'];
	}

	// special setup for junctions
	if ($location['type'] == TYPE_JUNCTION)
	{
		$junctionsql = "SELECT * FROM raillines r
			INNER JOIN locations_raillines lr ON lr.line_id = r.line_id
			WHERE lr.location_id = '".mysql_real_escape_string($location['location_id'])."'";
		$junctionresult = MYSQL_QUERY($junctionsql, locationDBconnect());

		if (MYSQL_NUM_ROWS($junctionresult) > 1)
		{
			$linea = MYSQL_RESULT($junctionresult,0,"link");
			$lineb = MYSQL_RESULT($junctionresult,1,"link");
			$namea = MYSQL_RESULT($junctionresult,0,"name");
			$nameb = MYSQL_RESULT($junctionresult,1,"name");
			$ida = MYSQL_RESULT($junctionresult,0,"line_id");
			$idb = MYSQL_RESULT($junctionresult,1,"line_id");

			include_once(dirname(__FILE__) . "/../common/location-lineguide-functions.php");

			if ($ida == $location['line_id'])
			{
				$extraPageURL = getLineguideDistanceURL(MYSQL_RESULT($junctionresult,1,"trackSubpage"), $location['km']);

				$junctionurl =  "/lineguide/$lineb/diagram$extraPageURL/#km".$location['km'];
				$url ='/location/'.$urlbase.'/'.$linea;
				$otherLineName = $nameb;
			}
			elseif ($idb == $location['line_id'])
			{
				$extraPageURL = getLineguideDistanceURL(MYSQL_RESULT($junctionresult,0,"trackSubpage"), $location['km']);

				$junctionurl =  "/lineguide/$linea/diagram$extraPageURL/#km".$location['km'];
				$url ='/location/'.$urlbase.'/'.$lineb;
				$otherLineName = $namea;
			}
		}
		elseif($location['url'] != '')
		{
			$junctionurl = '/lineguide/'.$location['url'];
			$url ='/location/'.$urlbase;
		}
		else
		{
			$junctionurl = '';
			$url = '/location/'.$urlbase;
		}
	}
	else
	{
		$junctionurl = '';
		if ($location['event'] == 1 OR showPhotos($location['photo']) OR $location['description_length'] > 2)
		{
			$url = '/location/'.$urlbase;
		}

		//specific actions for certain types of location
		switch ($location['type'])
		{
			//case '15':	//stations
			//case '37':	//RMSP
			//case '30': 	//industry
			//case '31':	//yards
			//case '33':	//crossing loop
			//	$url = '/locations/'.$urlbase;
			//	break;
			case '18': 	//timing loop
				$url = '/articles/timingloops';
				break;
			case '29':	//signal box
				$turl = '/location/'.$urlbase.'/box';
				break;
			default:
		}
	}

	// final fix for junction string
	if ($junctionurl != '')
	{
		$junctionText = ' <b><i><a href="'.$junctionurl.'">[Junction with '.$otherLineName.' line]</a></i></b>';
	}
	else
	{
		$junctionText = '';
	}

	// final fix for URLs
	if($url != '')
	{
		$location['safeworking_title'] = $title = '<b><a href="'.$url.'">'.$location['name'].'</a></b>';
	}
	else
	{
		$title = $location['name'];
		$location['safeworking_title'] = '<b>'.$location['name'].'</b>';
	}

	// set location title
	if (showPhotos($location['photo']))
	{
		$photoextra = '<td><img src="/images/photos.gif" alt="Photo" title="Photo" /></td>';
		$location['title'] = '<table><tr>'.$photoextra.'<td>'.$title.'&nbsp;&nbsp;'.$junctionText.'</td></tr></table>';
	}
	else
	{
		$location['title'] = $title.'&nbsp;&nbsp;'.$junctionText;
	}

	return $location;
}	//end function


/*
 * return $toreturn
 */
function getLineDiagram($line, $section, $trackPage)
{
	extract($line);

	// check for subpage to be shown, reset if invalid
	$invalidpage = ($trackPage != '' AND (!is_numeric($trackPage) OR $trackPage <= 0 OR $trackPage > $trackSubpageCount));

	//check year values input
	if ($invalidpage)
	{
		echo '<p class="error">Error - Invalid page number!</p>';
		return;
	}
	// fix up sub-distances if subpages have been selected and setup
	else
	{
		$pageBounds = split(';',$trackSubpage);
		$pageBounds = split('-',$pageBounds[$trackPage-1]);
		if (sizeof($pageBounds) == 2)
		{
			$lowerBound = $pageBounds[1]-10;
			$pageBounds = " AND km >= $pageBounds[0] AND km <= $pageBounds[1] ";
		}
		else
		{
			$pageBounds = '';
		}
	}

	/*
	 * find locations from the DB for selected line,
	 * filtering for the apropriate dates,
	 * as well as `display` types - we only want `line` or `tracks` or `both`
	 * order them by KM too
	 */
	$locationsOnRaillineSql = sprintf("SELECT *
		FROM locations l
		INNER JOIN locations_raillines r ON l.location_id = r.location_id
		WHERE line_id = '%s' $pageBounds
		AND l.open <= '%s' AND l.close >= '%s' AND l.display != 'map'
		ORDER BY km ASC", mysql_real_escape_string($lineId),
		mysql_real_escape_string($yearStart), mysql_real_escape_string($yearEnd));
	$locationsOnRaillineResult = MYSQL_QUERY($locationsOnRaillineSql, locationDBconnect());
	$numberOfLocations = MYSQL_NUM_ROWS($locationsOnRaillineResult);

	// end of error checking
	// if error found then return it and quit drawing
	if ($numberOfLocations == 0)
	{
		return '<p class="error">Error - No records found!</p>';
	}

	// otherwise start outputting data
	$pastKm = MYSQL_RESULT($locationsOnRaillineResult,0,"km");
	$nextTracks = MYSQL_RESULT($locationsOnRaillineResult,0,"tracks");

	/*
	 * decides on the type of diagram to be drawn,
	 * tracks in this sase,
	 * or safeworking in the other
	 */
	if ($section == 'diagram')
	{
		if ($trackPage > 1)
		{
			$toreturn[] = array('<td></td>',
								'<td class="l" align="center" height="30"><img src="/t/2-break.gif" height="30" width="148" alt=""/></td>',
								'<td class="t"><i><a href="/lineguide/'.$lineLink.'/diagram/page-'.($trackPage-1).'">Continued on page '.($trackPage-1).'</a></i></td>'
								);
		}

		for ($i = 0; $i < $numberOfLocations; $i++)
		{
			// get next location
			$currentLocation = getBasicLocationForLineguide($locationsOnRaillineResult, $i);
			$currentLocation = getFullLocationForLineguide($currentLocation);
			$imageToDisplay = '';

			// find the gap between locations and shrink it very small and fix a minimum size
			$separation = (($currentLocation['km']*150) - ($pastKm*150));
			if($separation < 5)
			{
				$separation = 0;
			}
			elseif($separation < 20)
			{
				$separation = 20;
			}

			$tracksToDisplay = getLineDiagramSectionTracks($line, $currentLocation, $pastTracks);
			$imageToDisplay = getLineDiagramLocationImage($line, $currentLocation, $tracksToDisplay);

			// for areas where line not open yet
			// 'false' = no tracks found = line not open
			if (is_numeric($tracksToDisplay))
			{
				// for verrrry big gaps
				if ($separation > 500)
				{
					$separation = 100;
					$toreturn[] = array('<td></td>',
										'<td class="l" height="'.$separation.'"><img src="/t/'.$tracksToDisplay.'.gif" height="'.$separation.'" width="148" alt=""/></td>',
										'<td></td>'
										);
					$toreturn[] = array('<td></td>',
										'<td class="g" height="30">Gap: '.($currentLocation['km']-$pastKm).'km </td>',
										'<td></td>'
										);
				}
				// end "for verrrry big gaps"

				// zero track if
				if ($i != '0' AND $separation > '0' AND $nextTracks != '9' AND $currentLocation['tracks'] != '0')	// OR $separation > '120') )	//start zero track if
				{
					$toreturn[] = array('<td></td>',
										'<td class="l" height="'.$separation.'"><img src="/t/'.$tracksToDisplay.'.gif" height="'.$separation.'" width="148" alt=""/></td>',
										'<td></td>'
										);
				}
				//end zero track if

				// add location row
				$toreturn[] = array('<td class="d" id="km'.$currentLocation['km'].'">'.$currentLocation['km_formatted'].'</td>',
									'<td class="l" align="center"><img src="/t/'.$imageToDisplay.'" alt="" /></td>',
									'<td class="t">'.$currentLocation['title'].'</td>'
									);

				// end add location row

			} // end "for areas where line not open yet"

			$pastKm = $currentLocation['km'];
			$nextTracks = $currentLocation['tracks'];
			$pastTracks = $tracksToDisplay;
		}	// end while loop

		if ($trackSubpageCount > $trackPage AND $trackPage  != 0 AND $pastKm > $lowerBound)
		{
			$toreturn[] = array('<td></td>',
								'<td class="l" align="center" height="30"><img src="/t/1-break.gif" height="30" width="148" alt=""/></td>',
								'<td class="t"><i><a href="/lineguide/'.$lineLink.'/diagram/page-'.($trackPage+1).'">Continued on page '.($trackPage+1).'</a></i></td>'
								);
		}	// end trackpage if
	}
	elseif ($section == 'safeworking')
	{
		$currentLocation = getBasicLocationForLineguide($locationsOnRaillineResult, 0);
		$endDist = $currentLocation['km'];

		while ($i < $numberOfLocations)
		{
			$currentLocation = getBasicLocationForLineguide($locationsOnRaillineResult, $i);
			$imageToDisplay = '';

			// loop to find the skip forward locations not being shown, and find next location on the line
			while ($endDist > $currentLocation['km'] AND $i < ($numberOfLocations-1))
			{
				$i++;
				// get next location, just the basic details required to decide if it is shown
				$currentLocation = getBasicLocationForLineguide($locationsOnRaillineResult, $i);
			}

			$currentLocation['tracks'] = getLineDiagramSectionTracks($line, $currentLocation, $pastTracks);
			$separation = (($currentLocation['km']*3) - ($pastKm*3));
			if($separation < 10)
			{
				$separation = 10;
			}

			$sqlSafeworking = "SELECT RE.safeworking, RE.safeworking_middle, RE.safeworking_why, ST.name,
				STARTKM.km AS start_distance, ENDKM.km AS end_distance, MIDDLEKM.km AS middle_distance
				FROM railline_events RE
				INNER JOIN safeworking_types ST ON safeworking = ST.link
				INNER JOIN locations_raillines STARTKM ON start_location = STARTKM.location_id
				INNER JOIN locations_raillines ENDKM ON end_location = ENDKM.location_id
				LEFT OUTER JOIN locations_raillines MIDDLEKM ON safeworking_middle = MIDDLEKM.location_id
				WHERE STARTKM.km <= '".$currentLocation['km']."' AND ENDKM.km >= '".$currentLocation['km']."'
				AND RE.date < '".$line['yearEnd']."' AND RE.line = '".$currentLocation['line_id']."'
				AND RE.safeworking != ''
				ORDER BY RE.date DESC";

			$resultSafeworking = MYSQL_QUERY($sqlSafeworking, locationDBconnect());

			if(MYSQL_NUM_ROWS($resultSafeworking) != 0)
			{
				$nextSafeworking = MYSQL_RESULT($resultSafeworking,0,"safeworking");
				$nextSwName = MYSQL_RESULT($resultSafeworking,0,"name");
				$startDist = MYSQL_RESULT($resultSafeworking,0,"start_distance");

				if (MYSQL_RESULT($resultSafeworking,0,"safeworking_why") != 'closed')
				{
					$middleloc = MYSQL_RESULT($resultSafeworking,0,"safeworking_middle");
					if ($middleloc != 0)
					{
						$middleDist = MYSQL_RESULT($resultSafeworking,0,"middle_distance");
					}
				}
				$endDist = MYSQL_RESULT($resultSafeworking,0,"end_distance");
				$nextSafeworking = $nextSafeworking.'-'.$currentLocation['tracks'];
			}
			else
			{
				$nextSafeworking = 'yard-limits';
				$safeworkingName = 'Yard Limits';

				if ($i+1 < $numberOfLocations)
				{
					$endDist = getBasicLocationForLineguide($locationsOnRaillineResult, $i+1);
					$endDist = $endDist["km"];
				}
			}

			if($currentLocation['km'] == $startDist
				|| $currentLocation['km'] == $endDist
				|| $currentLocation['km'] == $middleDist)
			{
				// update location, add more details to it beofre printing
				$currentLocation = getFullLocationForLineguide($currentLocation);
				$pastKm = $currentLocation['km'];

				$toreturn[] = array('<td></td>',
									'<td class="s" alt="'.$safeworkingName.'" title="'.$safeworkingName.'" style="background: white url(/images/safeworking/'.$safeworking.'.gif) repeat-y; "  height="'.$separation.'"></td>',
									'<td></td>'
									);

				$toreturn[] = array('<td class="d" id="km'.$currentLocation['km'].'">'.$currentLocation['km_formatted'].'</td>',
									'<td class="s"><img src="/images/safeworking/sw.gif" width="10" alt="" /></td>',
									'<td class="t">'.$currentLocation['safeworking_title'].'</td>'
									);

			}	// end thisKm if
			$i++;
			$safeworking = $nextSafeworking;
			$safeworkingName = $nextSwName;
			$pastTracks = $currentLocation['tracks'];
		}	// end while loop for safeworking

	} // end safeworking else

	return $toreturn;

}	// end function

/*
 * finds the number of tracks for a given section of line
 *
 * $lineId = the ID number of the line in question
 * $km = point from which we want the number of tracks
 *
 */
function getLineDiagramSectionTracks($line, $currentLocation, $pastTracks)
{
	$tracksToDisplay = false;

	$sqlTracks = "SELECT RE.tracks, RE.line, STARTKM.km AS start_distance, ENDKM.km AS end_distance
		FROM railline_events RE
		INNER JOIN locations_raillines STARTKM ON RE.start_location = STARTKM.location_id
		INNER JOIN locations_raillines ENDKM ON RE.end_location = ENDKM.location_id
		WHERE STARTKM.km <= '".$currentLocation['km']."' AND ENDKM.km >= '".$currentLocation['km']."'
		AND RE.date < '".$line['yearEnd']."' AND RE.line = '".$currentLocation['line_id']."' AND RE.tracks != ''
		ORDER BY RE.date DESC";

	//$sqlTracks = "SELECT * FROM railline_events WHERE start_distance <= '".$currentLocation['km']."' AND end_distance >= '".($currentLocation['km'])."' AND date < '".$yearEnd."' AND line = '".$currentLocation['line_id']."' AND tracks != '' ORDER BY date DESC";
	$resultTracks = MYSQL_QUERY($sqlTracks, locationDBconnect());
	$resultRows = MYSQL_NUM_ROWS($resultTracks);

	if ($resultRows > 0)
	{
		// check the the track retreived is for this segment, not for after the station in question
		$nexteventstartkm = MYSQL_RESULT($resultTracks,0,"start_distance");
		if ($currentLocation['km'] >= $nexteventstartkm)
		{
			$tracksToDisplay = MYSQL_RESULT($resultTracks,0,"tracks");
		}
		else
		{
			$tracksToDisplay = $pastTracks;
		}
	}
	// if checking the DB doesn't get anywhere, or we are showing all locations, replace with what was there before
	elseif ($currentLocation['tracks'] == 9 OR $currentLocation['tracks'] == 0 OR $line['yearEnd'] == '0000-01-01')
	{
		$tracksToDisplay = $currentLocation['tracks'];
	}
	// 'false' = no tracks found = line not open
	else
	{
		$tracksToDisplay = false;
	}

	return $tracksToDisplay;
}	// end function

/*
 * finds the image for a given location on a lineguide diagram
 *
 * $line = the array for line in question
 * $currentLocation = the arrray for the location in question
 * $tracksToDisplay = the number of tracks passing though this location
 *
 */
function getLineDiagramLocationImage($line, $currentLocation, $tracksToDisplay)
{
	// set up image url for crossings with events
	if ($currentLocation['image'] == "")
	{
		// test for crossing
		if (typeIsCrossing($currentLocation['type']))
		{
			$imageToDisplay = $tracksToDisplay."-".$currentLocation['type'];
			$sqlLocationEvents = "SELECT * FROM location_events
									WHERE date < '".$line['yearEnd']."'
									AND location = '".$currentLocation['location_id']."'
									ORDER BY date DESC";
			$resultLocationEvents = MYSQL_QUERY($sqlLocationEvents, locationDBconnect());

			if(MYSQL_NUM_ROWS($resultLocationEvents) > 0)
			{
				$crossingType = MYSQL_RESULT($resultLocationEvents,'0',"details");

				if (is_numeric($crossingType))
				{
					$imageToDisplay = $tracksToDisplay."-".$crossingType;
				}
				// reset type if we don't know when active protection was provided
				// and the year is before when active protection is common
				elseif($line['yearToDisplay'] < DATE_ACTIVE_CROSSINGS
					AND $imageToDisplay == ''
					AND typeIsActiveCrossing($currentLocation['type']))
				{
					$imageToDisplay = $tracksToDisplay."-8";
				}
			}
			elseif($line['yearToDisplay'] < DATE_ACTIVE_CROSSINGS
				AND typeIsActiveCrossing($currentLocation['type']))
			{
				$imageToDisplay = $tracksToDisplay."-8";
			}
		}
		else
		{
			$imageToDisplay = $tracksToDisplay."-".$currentLocation['type'];
		}
	}

	// set up images
	if ($imageToDisplay == '')
	{
		if ($yearHeader != 'All Locations')
		{
			$yearLimitedResult = MYSQL_QUERY("SELECT * FROM location_years
											WHERE `location` = '".$currentLocation['location_id']."'
											AND `year` <= '".$line['yearEnd']."'
											ORDER BY year DESC", locationDBconnect());

			// check it isn't a default image
			if (substr($currentLocation['image'],1,1) != '-')
			{
				// for particular year
				if(MYSQL_NUM_ROWS($yearLimitedResult) > 0)
				{
					$yearImage = '-'.MYSQL_RESULT($yearLimitedResult,'0',"year");
				}
				// more than one year, but none found (pick pic from location opening)
				else
				{
					$yearAllResults = MYSQL_QUERY("SELECT * FROM location_years
											WHERE location = '".$currentLocation['location_id']."'", locationDBconnect());

					if (MYSQL_NUM_ROWS($yearAllResults) > 0)
					{
						$yearImage = '-open';
					}
					else
					{
						$yearImage = '';
					}
				}
			}
			else
			{
				$yearImage = '';
			}

			// for junctions - so both sides don't show the same pic!
			if ($currentLocation['junctionurl'] != '' OR $currentLocation['type'] == TYPE_JUNCTION )
			{
				$imageToDisplay = $currentLocation['image'].'-'.$line['lineId'].$yearImage;
			}
			else
			{
				$imageToDisplay = $currentLocation['image'].$yearImage;
			}
		}
		else
		{
			$imageToDisplay = $currentLocation['image'];
		}
	}

	// show the max sized station
	$imageToDisplay = $imageToDisplay.'.gif';

	return $imageToDisplay;
}	// end function

/*
 * draws the events subpage for a lineguide
 *
 * $line = arrray for the line in question
 *
 */
function drawLineguideEventsSection($line)
{
?>
Events by Type :: <a href="/lineguide/<?php echo $line['lineLink']?>/events-by-date">Events by Date</a>
<?php
	drawEventsTable(getMiscLineEvents($line['lineId']), 1);

	// override to check misc events
	if ($line['numLocations'] >= 1)
	{
		drawLineEvents($line['lineId'], '');
	}

	drawLineEvents($line['lineId'], 'Line Opened');
	drawLineEvents($line['lineId'], 'Location Opened');
	drawLineEvents($line['lineId'], 'Track Amplified');
	drawLineEvents($line['lineId'], 'Gauge Conversion');
	drawLineEvents($line['lineId'], 'Safeworking');
	drawLineEvents($line['lineId'], 'Location Closed');
	drawLineEvents($line['lineId'], 'Line Closed');
}

/*
 * checks the the input year for a lineguide diagram is inside the bounds of the line
 *
 * $line = arrray for the line in question
 *
 * returns the $line array, but with the years altered if the input is incorrect
 * also shows an error message if incorrect
 *
 */
function checkLineguideDiagramYears($line)
{
	if ($line['yearHeader'] == 'All Locations')
	{}
	else if($line['yearStart'] < $line['openYear'])
	{
		$line['yearStart'] = $line['openYear'];
		$line['yearHeader'] = substr($line['openYear'], 0, 4);
		$line['yearEnd'] = $line['yearHeader'].'-12-31';
		$faultMessage = '<p class="error">The '.$line['lineName'].' line was not open in '.
			$line['yearToDisplay'].', so have shown the opening year of '.$line['yearHeader'].' instead</p>';
	}
	else if($line['yearEnd'] > $line['closeYear'])
	{
		$line['yearEnd'] = $line['closeYear'];
		$line['yearHeader'] = substr($line['closeYear'], 0, 4);
		$line['yearStart'] = $line['yearHeader'].'-01-01';
		$faultMessage = '<p class="error">The '.$line['lineName'].' line had closed by '.
			$line['yearToDisplay'].', so have shown the last year of operation instead</p>';
	}
	else if($line['yearHeader'] > date("Y"))	// AND ($lineId == '4' OR $lineId == '5') ) {
	{
		$faultMessage = '<p class="error">Error - Do you think this is a time traveling Delorean?
			I can\'t see the future of the '.$line['lineName'].' line so have reset the year to '.date("Y").'</p>';
		$line['yearHeader'] = date("Y");
		$line['yearEnd'] = $line['closeYear'].'-12-31';
		$line['yearStart'] = $line['yearHeader'].'-01-01';
	}

	if ($faultMessage != '')
	{
		echo $faultMessage;
	}

	return $line;
}




function drawAllLineguideDotpoints($type)
{
	$sql = "SELECT *, count(lr.line_id) AS line_locations
	FROM raillines r
	LEFT OUTER JOIN locations_raillines lr ON lr.line_id = r.line_id
	WHERE todisplay != 'hide' AND link != 'off-rail'
	GROUP BY lr.line_id
	ORDER BY `order` ASC";
	$result = MYSQL_QUERY($sql, locationDBconnect());
	$numberOfRows = MYSQL_NUMROWS($result);

	if ($numberOfRows>0)
	{
		echo "<ul class=\"tableofcontents\">\n";

		for ($j = 0; $j < $numberOfRows; $j++)
		{
			$line = getLineBasicDetails($result, $j);
?>
<li><a href="/lineguide/<?php echo $line["lineLink"];?>"><?php echo getLineName($line["lineName"]); ?></a>
<?php
			if ($type == 'sitemap')
			{
				$itemsToDisplay = getLineguidePages($line);
				echo "<ul class=\"tableofcontents\">\n";

				for ($i = 0; $i < sizeof($itemsToDisplay); $i++)
				{
?>
<li><a href="/lineguide/<?php echo $line["lineLink"]; ?>/<?php echo $itemsToDisplay[$i][0]; ?>" ><?php echo $itemsToDisplay[$i][1]; ?></a></li>
<?php
				}

				echo "</ul></li>\n";
			}
			else
			{
				echo "</li>\n";
			}
		}
		echo "</ul>\n";
	}
}	// end function
?>