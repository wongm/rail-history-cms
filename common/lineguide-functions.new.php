<?php

include_once("common/lineguide-database-functions.php");

/*
 * draws extra lineguide pages
 * these are stored in the articles table
 * if no articles is found for the given URL
 * then false is returned
 *
 */
function drawLineguideExtraPage($line, $section)
{
	$line = getLineguideExtraPage($line, $section);
	
	if ($line)
	{
		drawLineguideHeaders($line);
		drawSpecificLine($line, $header);
		drawLineguideFooters($line);
		return true;
	}
}

function drawLineguideHeaders($line)
{
	$pageTitle = getLineName($line['lineName'])." Guide";
	include_once("common/header.php");
	drawLineguideHeadbar($line);
	echo "<div id=\"lineguide\">\n";
}

function drawLineguideFooters($line)
{
	//draw the credits if they exist
	echo getObjectSources('railline', $line['lineId'], $line['credits']);
	echo "</div>\n";
	$updated = $line['updated'];
	include_once("common/footer.php"); 
}

function drawSafeworkingDiagram($line, $section)
{
	drawLineguideHeaders($line);
	$line = checkLineguideDiagramYears($line);
	$diagramData = getLineDiagram($line, $section, $trackPage);
	
	// test if an error was encounted, then display it
	if (sizeof($diagramData) > 1)
	{
		echo "<h4>Safeworking diagram - ".$line['yearHeader']."</h4>\n";
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

function drawTrackDiagram($line, $section, $trackPage)
{
	drawLineguideHeaders($line);
	$line = checkLineguideDiagramYears($line);
	$diagramData = getLineDiagram($line, $section, $trackPage);
	
	// test if an error was encounted, then display it
	if (sizeof($diagramData) > 1)
	{
		if ($trackPage != '')
		{
			$subpageheader = " page $trackPage";
		}
		
		echo "<h4>Track diagram $subpageheader - ".$line['yearHeader']."</h4>\n";
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
	drawLineguideHeaders($line);
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
<?
	
	for ($i = 0; $i<$interestingYearsLength; $i++)
	{
?>
	<td><a href="/lineguide/<? echo $lineToDisplay.'/'.$section.$pageLink.'/year-'.$interestingYears[$i].'">'.$interestingYears[$i].'</a>';?></td>
<?
	}	// end while
?>
	<td><a href="/lineguide/<? echo $lineToDisplay.'/'.$section.$pageLink.'">Reset View</a>';?></td>	
	<td><label for="year">Year? </label><input type="text" name="year" id="year" size="4" onclick="highlight(this);" value="<? echo $yearToDisplay;?>" />
	<input type="submit" value="Go!" /></td>
</tr></table>
<?
	if ($pageLink != '')
	{
?>
<input type="hidden" id="page" name="page" value="<?=$diagramPage?>" />		
<?
	}
?>
<input type="hidden" id="line" name="line" value="<? echo $lineToDisplay;?>" />
<input type="hidden" id="section" name="section" value="<? echo $_REQUEST['section'];?>" />
</form>	
<?
	}	// end zero years if
}		// end function

function drawLocationTitle($thisUrl, $id, $thisName, $thisPhoto)
{
	// add the id if more than one location by same name exists
	// yards don't count as they have many leads to them
	if ($thisUrl != '' OR $id == '')
	{
		$base = str_replace(' ', '-', strtolower($thisName));
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
<?

	for ($i = 0; $i < $numberOfRows; $i++)
	{
?>
<tr>	
	<?=$diagramdata[$i][0]."\n";?>
	<?=$diagramdata[$i][1]."\n";?>
	<?=$diagramdata[$i][2]."\n";?>
</tr>
<?
	}	// end for loop
?>
</table>
<?

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
<?	}	//end function


	
function drawLineguideHeadbar($lines)
{
	$itemstodisplay = getLineguidePages($lines, 'headbar');
	$pageTitle = getLineName($lines['lineName']." Line")." Home";
?>
<table class="headbar"><tr><td>
<a href="/lineguide/<?=$lines['lineLink']; ?>"><?=$pageTitle; ?></a>
<?
	if (sizeof($itemstodisplay) > 0)
	{
		foreach ($itemstodisplay as $line)
		{
			echo " :: <a href=\"/lineguide/".$lines['lineLink']."/".$line[0]."\">".$line[1]."</a>\n";
		}
	}
	
	echo "</td></tr></table>\n";
}	//end function

function drawSpecificLine($line, $contentsHeader = 'Contents')
{
	$section = $_REQUEST['section'];
	
	// check to see if photos will be shown
	include_once("common/gallery-functions.php"); 
	$line['showPhotos'] = false;
	
	if (showPhotos($line['photos']))
	{
		$line['showPhotos'] = checkForLocationImages($line['photos']);
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
	
	if ($line['caption'] != '')
	{	
		echo "<img class=\"photo-right\" src=\"/images/lineguide/$imglink.jpg\" alt=\"".$line['caption']."\" title=\"".$line['caption']."\"/>\n";
	}
	
	// only draw TOC of more than one tab...
	if ($totalTabs > 1)
	{
		echo "<h4 id=\"top\">$contentsHeader</h4>\n<ul>\n";

		for ($i = 0; $i < sizeof($descriptionTabs); $i++)
		{
?>
	<li><?=$descriptionTabs[$i]?></li>
<?
		}
		if ($line['showPhotos'])
		{
			echo "<li><a href=\"#photos\">Photos</a></li>\n";
		}	
		if ($line['credits'] != '')
		{
			echo "<li><a href=\"#sources\">Sources</a></li>\n";
		}
		
		echo "</ul>\n";
	}	
	// end no TOC if
	
	drawFormattedText($line['description']);
	
	if ($line['showPhotos'])
	{
		drawLocationImages($line['photos']);
	}
}	// end function



/*
 * just the basic details required to decide if it is shown
 */
function getBasicLocationForLineguide($databaseresult, $index)
{
	// retreive from DB
	$location['location_id'] = MYSQL_RESULT($databaseresult,$index,"location_id");
	$location['name'] = stripslashes(MYSQL_RESULT($databaseresult,$index,"name"));
	$location['line_id'] = MYSQL_RESULT($databaseresult,$index,"line_id");
	$location['tracks'] = MYSQL_RESULT($databaseresult,$index,"tracks");
	$location['display'] = MYSQL_RESULT($databaseresult,$index,"display");
	$location['type'] = MYSQL_RESULT($databaseresult,$index,"type");
	$location['image'] = MYSQL_RESULT($databaseresult,$index,"image");
	$location['km'] = MYSQL_RESULT($databaseresult,$index,"km");
	$location['km_accuracy'] = MYSQL_RESULT($databaseresult,$index,"kmaccuracy");
	$location['km_formatted'] = formatDistance($currentLocation['km'], $currentLocation['km_accuracy']);
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
	// incurrs a DB hit
	if (!isUniqueLocationName($location['name']))
	{
		$urlbase = $location['location_id'];
	}
	else
	{
		$urlbase = str_replace(' ', '-', strtolower($location['name']));
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
			
			if ($ida == $location['line_id'])
			{
				$junctionurl =  '/lineguide/'.$lineb.'/diagram/#km'.$location['km'];
				$url ='/location/'.$urlbase.'/'.$linea;
				$otherLineName = $nameb;
			}
			elseif ($idb == $location['line_id'])
			{
				$junctionurl =  '/lineguide/'.$linea.'/diagram/#km'.$location['km'];
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
	
	
				if ($currentLocation['url'] != "")
				{
					
				}
				else
				{
					
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
		$error = '<p class="error">Error - Invalid page number!</p>';
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
	$locationsOnRaillineSql = sprintf("SELECT * FROM locations l, locations_raillines r 
		WHERE l.location_id = r.location_id AND line_id = '%s' $pageBounds
		AND `open` <= '%s' AND `close` >= '%s' AND `display` != 'map' 
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
	
	/*
	 * decides on the type of diagram to be drawn,
	 * tracks in this sase,
	 * or safeworking in the other
	 */
	if ($section == 'diagram')
	{
		$toreturn = getTrackLineDiagram($lineLink, $locationsOnRaillineResult, $numberOfLocations, $trackPage);
	}
	else if ($section == 'safeworking')
	{
		$toreturn = getSafeworkingLineDiagram($lineLink, $locationsOnRaillineResult, $numberOfLocations);
	}
	
	print_r(print_r($toreturn));
	
	return $toreturn;
}

function getTrackLineDiagram($lineLink, $locationsOnRaillineResult, $numberOfLocations, $trackPage)
{
	$pastKm = MYSQL_RESULT($locationsOnRaillineResult,0,"km");
	$nextTracks = MYSQL_RESULT($locationsOnRaillineResult,0,"tracks");
	
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
	
	return $toreturn;
}		// end function

function getSafeworkingLineDiagram($lineLink, $locationsOnRaillineResult, $numberOfLocations)
{
	$pastKm = MYSQL_RESULT($locationsOnRaillineResult,0,"km");
	$nextTracks = MYSQL_RESULT($locationsOnRaillineResult,0,"tracks");
	
	$toreturn = array();
	
	while ($i<$numberOfLocations)
	{
		// get next location, just the basic details required to decide if it is shown
		$currentLocation = getBasicLocationForLineguide($locationsOnRaillineResult, $i);
		$imageToDisplay = '';
					
		$separation = (($currentLocation['km']*3) - ($pastKm*3));
		if($separation < 10) 
		{
			$separation = 10;
		}
		
		$currentLocation['tracks'] = getLineDiagramSectionTracks($line, $currentLocation, $pastTracks);
		
		$sqlSafeworking = "SELECT RE.safeworking, RE.safeworking_middle, RE.safeworking_why, ST.name, 
			STARTKM.km AS start_distance, ENDKM.km AS end_distance, MIDDLEKM.km AS middle_distance
			FROM railline_events RE 
			INNER JOIN safeworking_types ST ON safeworking = ST.link
			INNER JOIN locations_raillines STARTKM ON start_location = STARTKM.location_id 
			INNER JOIN locations_raillines ENDKM ON end_location = ENDKM.location_id 
			LEFT OUTER JOIN locations_raillines MIDDLEKM ON safeworking_middle = MIDDLEKM.location_id 
			WHERE STARTKM.km <= '".$currentLocation['km']."' AND ENDKM.km >= '".$currentLocation['km']."' 
			AND RE.date < '".$line['yearEnd']."' AND RE.line = '".$currentLocation['line_id']."' AND RE.safeworking != '' 
			ORDER BY RE.date DESC";
		$resultSafeworking = MYSQL_QUERY($sqlSafeworking);
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
		}
		
		if($currentLocation['km'] == $startDist || $currentLocation['km'] == $endDist || $currentLocation['km'] == $middleDist)
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
	}	// end while loop for safeworking
	
	return $toreturn;	
} 	// end function

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
		FROM railline_events RE, locations_raillines STARTKM, locations_raillines ENDKM 
		WHERE STARTKM.km <= '".$currentLocation['km']."' AND ENDKM.km >= '".$currentLocation['km']."' 
		AND RE.date < '".$line['yearEnd']."' AND RE.line = '".$currentLocation['line_id']."' AND RE.tracks != '' 
		AND RE.start_location = STARTKM.location_id AND RE.end_location = ENDKM.location_id 
		ORDER BY RE.date DESC";
	
	//$sqlTracks = "SELECT * FROM railline_events WHERE start_distance <= '".$currentLocation['km']."' AND end_distance >= '".($currentLocation['km'])."' AND date < '".$yearEnd."' AND line = '".$currentLocation['line_id']."' AND tracks != '' ORDER BY date DESC";
	$resultTracks = MYSQL_QUERY($sqlTracks);
	$resultRows = MYSQL_NUM_ROWS($resultTracks);
	
	if ($resultRows > 0)
	{
		
		// check the the track retreived is for this segment, not for after the station in question
		$nexteventstartkm = MYSQL_RESULT($resultTracks,0,"start_distance");
		if ($currentLocation['km'] > $nexteventstartkm)
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
			$resultLocationEvents = MYSQL_QUERY($sqlLocationEvents);
			
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
			elseif($line['yearToDisplay'] < DATE_ACTIVE_CROSSINGS) 
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
											ORDER BY year DESC");
			$yearAllResults = MYSQL_QUERY("SELECT * FROM location_years 
											WHERE `location` = '".$currentLocation['location_id']."'");
		
			// check it isn't a default image
			if (substr($currentLocation['image'],1,1) != '-')
			{
				// for particular year
				if(MYSQL_NUM_ROWS($yearLimitedResult) > 0)
				{
					$yearImage = '-'.MYSQL_RESULT($yearLimitedResult,'0',"year");
				}
				// more than one year, but none found (pick pic from location opening)
				else if (MYSQL_NUM_ROWS($yearAllResults) > 0)
				{
					$yearImage = '-open';
				}
				else
				{
					$yearImage = '';
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
<h4>Events</h4>
<?
	drawEventsTable(getMiscLineEvents($line['lineId']), 1);
	
	// override to check misc events
	if ($line['numLocations'] >= 1)
	{
?>
Events by Type :: <a href="/lineguide/<?=$line['lineLink']?>/events-by-date">Events by Date</a>
<?
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
	$sql = "SELECT * FROM raillines WHERE todisplay != 'hide' AND link != 'off-rail' ORDER BY `order` ASC";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	echo "<ul>\n";

	if ($numberOfRows>0) 
	{
		for ($j = 0; $j < $numberOfRows; $j++)
		{
			$line = getLineBasicDetails($result, $j);
			$itemstodisplay = getLineguidePages($line);	
?>
<li><a href="/lineguide/<?=$line["lineLink"];?>"><?=getLineName($line["lineName"]); ?></a>
<?
			if ($type = 'sitemap')
			{
				echo "<ul>\n";

				for ($i = 0; $i < sizeof($itemstodisplay); $i++)
				{	
?>
<li><a href="/lineguide/<? echo $line["lineLink"]; ?>/<? echo $itemstodisplay[$i][0]; ?>" ><? echo $itemstodisplay[$i][1]; ?></a></li>
<?		
				}
				
				echo "</ul></li>\n";
			}
			else
			{
				echo "</li>\n";
			}
		}
	}
	echo "</ul></li>\n";
}	// end function
?>