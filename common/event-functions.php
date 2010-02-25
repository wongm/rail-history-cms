<?
/*
 * draws a table of all of the events for a line
 * it gets called multiple times to draw events of different ypes
 */
function drawLineEvents($lineId, $type)
{
	if ($type == 'Location Opened' OR $type == 'Location Closed')
	{
		$dataarray = getLineLocationEvents($lineId, $type);
	}
	else
	{
		$location['lineId'] = $lineId;
		$dataarray = getLocationLineEvents($type, $location);
	}
	if ($dataarray[0] != '')
	{
		echo "<h4>$type</h4>\n";
		drawEventsTable($dataarray);
	}
}
	
function getLineLocationEvents($lineId, $type)	
{
	// array format - DATE - EVENT DSECRIPTION
	$eventData = array();
	
	if ($type == 'Location Opened')
	{
		$desc = 'opened';
		$sql = "SELECT DATE_FORMAT(open, '".DATE_FORMAT."')  AS fdate, openAccuracy AS dateaccuracy, 
			l.location_id, l.name, basic, type_id, '".$desc."' AS constantdesc 
			FROM locations l
			INNER JOIN location_types lt ON l.type = lt.type_id 
			INNER JOIN locations_raillines lr ON lr.location_id = l.location_id
			WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") 
			AND line_id = $lineId AND name != '' AND l.open != '".DATE_UNKNOWN_OPEN."' 
			ORDER BY l.open";
	}
	elseif ($type == 'Location Closed')
	{
		$desc = 'closed';
		$sql = "SELECT DATE_FORMAT(close, '".DATE_FORMAT."') AS fdate, closeAccuracy AS dateaccuracy, 
			l.location_id, l.name, basic, type_id, '".$desc."' AS constantdesc 
			FROM locations l
			INNER JOIN location_types lt ON l.type = lt.type_id 
			INNER JOIN locations_raillines lr ON lr.location_id = l.location_id
			WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") AND line_id = $lineId 
			AND name != ''  AND close != '".DATE_NULL."' AND close != '".DATE_UNKNOWN_CLOSE."' 
			ORDER BY close ASC";
	}
	
	$result = MYSQL_QUERY($sql, locationDBconnect());
	$numberOfRows = MYSQL_NUM_ROWS($result);
	if ($numberOfRows > 0)
	{	
		for ($i = 0; $i < $numberOfRows; $i++)
		{
			$fDate = formatDate(MYSQL_RESULT($result,$i,"fdate"), MYSQL_RESULT($result,$i,"dateaccuracy"));
			$type = MYSQL_RESULT($result,$i,"basic");
			$type = eregi_replace('industry', '(industry)', $type);
			$name = stripslashes(MYSQL_RESULT($result,$i,"name"));
			$eventData[$i]['details'] = $name.' '.mb_strtolower($type).' '.$desc;
			$eventData[$i]['date'] = $fDate;
		}
	}
	return $eventData;

}	// end function 

/*
 * returns array of all events for given type
 */
function getMiscLineEvents($lineId)
{
	$sql = "SELECT DATE_FORMAT(date, '".DATE_FORMAT."') AS fdate, dateAccuracy, description
	FROM railline_events
	WHERE (line = '".$lineId."') AND display != 'hide' 
	AND (safeworking_why = 'plain') 
	ORDER BY date ASC";
	//	echo $sql;
	$result = MYSQL_QUERY($sql, locationDBconnect());
	$numberOfRows = MYSQL_NUM_ROWS($result);
	
	if ($numberOfRows > 0)
	{
		for ($i=0; $i<$numberOfRows; $i++)
		{	
			$details = stripslashes(MYSQL_RESULT($result,$i,"description"));
			$date = formatDate(MYSQL_RESULT($result,$i,"fdate"), MYSQL_RESULT($result,$i,"dateAccuracy"));
			$eventData[] = array($date, $details);
		}
	}
	return $eventData;
}
	
function getLocationLineEvents($type, $location)
{
	$locationID = $location['id'];
	
	if ($location['numberoflines'] == '')
	{
		$lineId = $location['lineId'];
		$lineSQL = " line = '$lineId' ";
	}
	else if ($location['numberoflines'] == 1)
	{	
		$locationKm = $location['km'];
		$lineId = $location['lineId'];
		$lineSQL = " line = '$lineId' ";
	}
	// for locations on multiple lines
	else
	{
		$locationKm = $location['branchlines'][0]['km'];
		$lineId = $location['branchlines'][0]['lineId'];
		$lineSQL = " line IN ($lineId";
		
		for ($i = 1; $i < sizeof($location['branchlines']); $i++)
		{
			$lineSQL .= ", ".$location['branchlines'][$i]['lineId'];
		}
		$lineSQL .= ") ";
	}
	
	// array format - DATE - EVENT DESCRIPTION
	$eventData = array();
	
	if ($locationID != "")
	{
		$sqlBetween = " AND ( RE.start_location = '".$locationID."' OR RE.end_location = '".$locationID."' 
			OR ( STARTKM.km <= '".$locationKm."' AND ENDKM.km >= '".$locationKm."'  ) ) ";
		$sqlOn = "AND ( RE.start_location = '".$locationID."' OR RE.end_location = '".$locationID."' 
			OR RE.safeworking_middle = '".$locationID."')";
	}
	
	// sorts out what events we want
	switch ($type)
	{
		case 'Line Opened':
			$sqlBit = $sqlBetween." AND safeworking_why != 'singled' AND (safeworking_why = 'opened')  ";
			//$sqlBit = $sqlBetween." AND tracks = '1' ";
			break;
		case 'Track Amplified':
			$sqlBit = $sqlBetween." AND ((RE.tracks > 1 AND RE.safeworking_why != 'opened') OR RE.safeworking_why = 'singled' ) ";
			//$sqlBit = $sqlBetween." AND tracks > '1' ";
			break;
		case 'Safeworking':
			$sqlBit = $sqlOn." AND RE.safeworking != '' ";
			break;
		case 'Line Closed':
			$sqlBit = $sqlBetween." AND RE.tracks = '0' ";
			break;
		case 'Gauge Conversion':
			$sqlBit = $sqlBetween." AND gauge != 'BG' ";
			break;
		case 'By Date':
			$sqlBit = " AND (( safeworking_why != 'singled' AND (safeworking_why = 'opened') ) 
				OR ( ((RE.tracks > 1 AND safeworking_why != 'opened') OR RE.safeworking_why = 'singled' ) ) 
				OR ( RE.safeworking != '' ) OR ( RE.tracks = '0' ) OR ( gauge != 'BG' ) 
				OR ( RE.description != '' AND RE.start_location = '' AND RE.end_location = '' )) ";
			break;
		default:
			$sqlBit = " AND RE.description != '' AND start_location = '' AND end_location = '' ";	
			break;			
	}
	
	
	if ($type != 'By Date')
	{
		$sql = "SELECT event_id, DATE_FORMAT(date, '".DATE_FORMAT."') AS fdate, RE.tracks, safeworking, safeworking_why,
		gauge, start_location, end_location, date, line, RE.description, RE.tracks, 
		safeworking_middle, safeworking_why, source, sourcedetail, dateAccuracy,
		STARTKM.km AS start_distance, ENDKM.km AS end_distance, MIDDLEKM.km AS middle_distance, 
		ST.name AS safeworking_type, gauge AS location_name, 
		STARTLOCATION.name AS start_name, ENDLOCATION.name AS end_name, MIDDLELOCATION.name AS middle_name
		FROM railline_events RE
		INNER JOIN locations_raillines STARTKM ON start_location = STARTKM.location_id
		INNER JOIN locations STARTLOCATION ON start_location = STARTLOCATION.location_id
		INNER JOIN locations_raillines ENDKM ON end_location = ENDKM.location_id 
		INNER JOIN locations ENDLOCATION ON end_location = ENDLOCATION.location_id 
		LEFT OUTER JOIN safeworking_types ST ON safeworking = ST.link
		LEFT OUTER JOIN locations_raillines MIDDLEKM ON safeworking_middle = MIDDLEKM.location_id
		LEFT OUTER JOIN locations MIDDLELOCATION ON safeworking_middle = MIDDLELOCATION.location_id
		WHERE $lineSQL $sqlBit AND RE.display != 'hide' 
		GROUP BY event_id
		ORDER BY date ASC, start_distance DESC";
		
		//if ($type == 'Safeworking' )
		//	echo "<pre>$sql</pre><hr>";
	}
	// super convoluted to make sure all events get in - union all
	else
	{
		$sql = "SELECT event_id, DATE_FORMAT(date, '".DATE_FORMAT."') AS fdate, date AS plaindate, RE.tracks AS tracks, 
		safeworking, gauge, start_location, STARTKM.km AS start_distance, 
		end_location, ENDKM.km AS end_distance, date, line, RE.description, 
		safeworking_middle, '-', safeworking_why, ST.name AS safeworking_type, source, sourcedetail, dateAccuracy,
		STARTLOCATION.name AS start_name, ENDLOCATION.name AS end_name, MIDDLELOCATION.name AS middle_name, 
		'-' AS location_name
		FROM railline_events RE
		INNER JOIN locations_raillines STARTKM ON start_location = STARTKM.location_id
		INNER JOIN locations STARTLOCATION ON start_location = STARTLOCATION.location_id
		INNER JOIN locations_raillines ENDKM ON end_location = ENDKM.location_id 
		INNER JOIN locations ENDLOCATION ON end_location = ENDLOCATION.location_id 
		LEFT OUTER JOIN safeworking_types ST ON safeworking = ST.link
		LEFT OUTER JOIN locations_raillines MIDDLEKM ON safeworking_middle = MIDDLEKM.location_id
		LEFT OUTER JOIN locations MIDDLELOCATION ON safeworking_middle = MIDDLELOCATION.location_id
		WHERE $lineSQL AND (( safeworking_why != 'singled' 
		AND (safeworking_why = 'opened') ) OR ( ((RE.tracks > '1' AND safeworking_why != 'opened') 
		OR safeworking_why = 'singled' ) ) OR ( safeworking != '' ) OR ( RE.tracks = '0' ) 
		OR ( gauge != 'BG' ) OR ( RE.description != '' AND start_location = '' AND end_location = '' ))  
		AND RE.display != 'hide'
		UNION
		SELECT l.location_id AS event_id, DATE_FORMAT(l.open, '".DATE_FORMAT."') AS fdate, l.open AS plaindate, 'opened' AS tracks, 
		'-', '-', '-', '-', 
		'-', '-', l.open AS date, line_id AS line, '', 
		'-', '-', basic, '-', '-', '-', l.openAccuracy AS dateAccuracy, 
		'-', '-', '-', l.name AS location_name
		FROM locations l
		INNER JOIN locations_raillines lr ON lr.location_id = l.location_id 
		INNER JOIN location_types lt ON type = type_id 
		WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") AND line_id = '$lineId' 
		AND name != '' AND open != '".DATE_UNKNOWN_OPEN."' AND open != '".DATE_UNKNOWN_CLOSE."' 
		UNION
		SELECT l.location_id AS event_id, DATE_FORMAT(l.close, '".DATE_FORMAT."')  AS fdate, l.close AS plaindate, 'closed' AS tracks, 
		'-', '-', '-', '-', 
		'-', '-', l.close, line_id AS line, '', 
		'-', '-', basic, '-', '-', '-', l.closeAccuracy AS dateAccuracy, 
		'-', '-', '-', l.name AS location_name 
		FROM locations l
		INNER JOIN locations_raillines lr ON lr.location_id = l.location_id 
		INNER JOIN location_types lt ON type = type_id 
		WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") AND line_id = '$lineId' 
		AND name != '' AND close != '".DATE_NULL."' AND close != '".DATE_UNKNOWN_CLOSE."' 
		ORDER BY plaindate ASC";
	}
	
	$tram = false;
	$result = MYSQL_QUERY($sql, locationDBconnect());
	$numberOfRows = MYSQL_NUM_ROWS($result);
	
	if ($numberOfRows > 0 AND $sqlBit != '' )
	{
		$i=0;
		$j=0; // for data row for output array
		while ($i<$numberOfRows)
		{
			//if ($type == 'Safeworking' )
			//	echo "outer i = $i<br>";
			
			$eventId = MYSQL_RESULT($result,$i,"event_id");
			$tracks = MYSQL_RESULT($result,$i,"tracks");
			$safeworking = stripslashes(MYSQL_RESULT($result,$i,"safeworking"));
			$safeworkingType = MYSQL_RESULT($result,$i,"safeworking_why");
			$gauge = stripslashes(MYSQL_RESULT($result,$i,"gauge"));
			$eventLocationName = stripslashes(MYSQL_RESULT($result,$i,"location_name"));
			$startID = MYSQL_RESULT($result,$i,"start_location");
			$endID = MYSQL_RESULT($result,$i,"end_location");
			$middleID = MYSQL_RESULT($result,$i,"safeworking_middle");
			$plainDate = MYSQL_RESULT($result,$i,"date");
			$description = stripslashes(MYSQL_RESULT($result,$i,"description"));
			$date = MYSQL_RESULT($result,$i,"fdate");
			$dateAccuracy = MYSQL_RESULT($result,$i,"dateAccuracy");
			$sourcedetail = MYSQL_RESULT($result,$i,"sourcedetail");
			
			$startLocationName = stripslashes(MYSQL_RESULT($result,$i,"start_name"));
			$endLocationName = stripslashes(MYSQL_RESULT($result,$i,"end_name"));
			
			// clear null values
			if (!is_numeric($middleID))
			{
				$middleID = 0;
			}
			
			switch ($tracks)
			{
				case '0':
					$details = "Line closed $startLocationName to $endLocationName";
					break;
				case '1':
					$details = "Line opened $startLocationName to $endLocationName";
					break;
				case '2':
					$details = "Line duplicated $startLocationName to $endLocationName";
					break;
				case '3':
					$details = "Line triplicated $startLocationName to $endLocationName";
					break;
				case '4':
					$details = "Line quadruplicated $startLocationName to $endLocationName";
					break;
				case '6':
					$details = "Line amplified to six tracks $startLocationName to $endLocationName";
					break;	
				case 'opened':
				case 'closed':
					$details = $eventLocationName.' '.strtolower($safeworkingType).' '.$tracks;
					$details = eregi_replace('industry', '(industry)', $details);
					break;
			}
			
			switch ($safeworkingType)
			{
				case 'singled':
				case 'opened':
					$details = "Line $safeworkingType $startLocationName to $endLocationName";	
					break;
			}
			
			switch ($gauge)
			{
				case 'dg':
					$details = 'Converted to Dual Gauge between '.$startLocationName.' and '.$endLocationName;	
					break;
				case 'sg':
					$details = 'Converted to Standard Gauge between '.$startLocationName.' and '.$endLocationName;	
					break;	
				case 'ng':
					$details = 'Converted to Narrow Gauge between '.$startLocationName.' and '.$endLocationName;	
					break;
			}
			
			// nice names for opening events
			if ($type == 'Line Opened')
			{
				$numberEventsSameDay = getNumberEventsSameDay($result, $i);
				
				// chains together events of the same date
				if ($numberEventsSameDay > 1 AND !$tram AND $locationKm != '')	
				{
					$details = 'Line opened '.$startLocationName;
					
					for ($x = 1; $x < $numberEventsSameDay; $x++)
					{
						$middleName = MYSQL_RESULT($result,$x,"end_name");
						$details .= " - $middleName";
					}
					$i += $numberEventsSameDay;
					$pastdate = $date;
				}
			}
			
			if ($type == 'Track Amplified')
			{
				$numberEventsSameDay = getNumberEventsSameDay($result, $i);
				
				// chains together events of the same date
				if ($numberEventsSameDay > 1 AND !$tram)
				{
					$subdetails = split(' ', $details);
					$details = 'Line '.$subdetails[1].' '.$startLocationName;
					
					for ($x = 1; $x < $numberEventsSameDay; $x++)
					{
						$middleName = MYSQL_RESULT($result,$x,"end_name");
						$details .= " - $middleName";
					}
					$i += $numberEventsSameDay;
					$pastdate = $date;
				}
			}
			
			// nice names for safeworking events
			elseif ($type == 'Safeworking' 
				OR ($type == 'By Date' AND $safeworking != '' AND $safeworking != '-' AND $date != $pastdate) )
			{
				$nicename = stripslashes(MYSQL_RESULT($result,$i,"safeworking_type"));
				$numberEventsSameDay = getNumberEventsSameDay($result, $i);
				
				// chains together events of the same date
				if ($numberEventsSameDay > 1)
				{
					// only flog the DB if we know there are multiple locations, by skipping forward in the event records already found
					// for specific location
					if ($locationID != "")
					{
						$sqlmultilocations = " WHERE (STARTKM.km <= '".$locationKm."' AND ENDKM.km >= '".$locationKm."') 
							AND line = '".$lineId."' AND date = '".$plainDate."' ";
					}
					// for lineguide
					else
					{
						$sqlmultilocations = " WHERE line = '".$lineId."' AND date = '".$plainDate."' ";
					}
					
					$sqlmultilocations = "SELECT STARTNAME.name, ENDNAME.name, MIDDLENAME.name, 
						STARTKM.km AS start_distance, ENDKM.km AS end_distance 
						FROM railline_events
						INNER JOIN locations_raillines STARTKM ON start_location = STARTKM.location_id
						INNER JOIN locations STARTNAME ON start_location = STARTNAME.location_id
						INNER JOIN locations_raillines ENDKM   ON end_location = ENDKM.location_id 
						INNER JOIN locations ENDNAME ON end_location = ENDNAME.location_id
						LEFT OUTER JOIN locations_raillines MIDDLEKM ON safeworking_middle = MIDDLEKM.location_id
						LEFT OUTER JOIN locations MIDDLENAME ON safeworking_middle = MIDDLENAME.location_id".
						$sqlmultilocations."
						AND (safeworking_why = 'replaced' OR safeworking_why = 'opened') AND safeworking != ''
						GROUP BY start_location, end_location
						ORDER BY STARTKM.km ASC";
						
					//echo $sqlmultilocations."<br>";
					
					$multiLocations = MYSQL_QUERY($sqlmultilocations, locationDBconnect());
					$sameDate = MYSQL_NUM_ROWS($multiLocations);
					
					//echo "i = $i<br>";
					//echo "samedate = $sameDate<hr>";
					
					if ($sameDate > 1)
					{
						$startName = stripslashes(MYSQL_RESULT($multiLocations,0,"STARTNAME.name"));
						$details = "$nicename provided $startName";
						
						for ($x = 0; $x < $sameDate; $x++)
						{
							$middleName = stripslashes(MYSQL_RESULT($multiLocations,$x,"ENDNAME.name"));
							$details = "$details - $middleName";
						}
						//increase by the number of date duplicates, minus one for the $i++ at the end
						$i = $i+$sameDate-1;
					}
				}
				// for specific locations AND single section split into two
				else if ($locationID != "" AND $middleID != '0')	
				{
					$middle = stripslashes(MYSQL_RESULT($result,$i,"middle_name"));
					
					if ($safeworkingType == 'closed')
					{
						if ($nicename == "Composite Electric Staff")
						{
							$details = $middle.' closed in '.$nicename.' section '.$startLocationName.' - '.$endLocationName;
						}
						else
						{
							$details = $middle.' closed. '.$nicename.' section now '.$startLocationName.' - '.$endLocationName;
						}
					}
					elseif ($locationID == $middleID)
					{
						$details = $middle.' opened. '.$nicename.' section now  '.$startLocationName.' -  '.$middle.' - '.$endLocationName;			
					}
					else
					{
						$startDistance = MYSQL_RESULT($result,$i,"start_distance");
						$endDistance = MYSQL_RESULT($result,$i,"end_distance");
						
						$middleloc = MYSQL_RESULT($result,$i,"safeworking_middle");
						if ($middleloc != 0)
						{
							$middleDistance = MYSQL_RESULT($result,$i,"middle_distance");
						}
						
						if ($nicename == "Composite Electric Staff")
						{
							$details = $middle.' opened in '.$nicename.' section '.$startLocationName.' - '.$endLocationName;
						}
						else if ($locationKm < $middleDistance)
						{
						 	$details = $middle.' opened. '.$nicename.' section now '.$startLocationName.' - '.$middle;
						}
						else
						{	
							$details = $middle.' opened. '.$nicename.' section now '.$middle.' - '.$endLocationName;
						}
					}
				}
				else
				{
					// lineguide page, for middle locations
					if ($middleID != 0)	
					{
						$middleName = stripslashes(MYSQL_RESULT($result, $i,"middle_name"));
						
						if ($safeworkingType == 'closed' OR $safeworkingType == 'opened')
						{
							$details = "$middleName $safeworkingType. $nicename section now $startLocationName - $endLocationName";
						}
					}
					// for yard limits
					else if ($nicename == 'Yard Limits')
					{
						if ($safeworkingType == 'downgrade')
						{
							$details = "Main line $startLocationName - $endLocationName downgraded to siding";
						}
						else
						{
							$details = '';
						}
						
					}
					// plain old default
					else
					{
						$details = "$nicename provided $startLocationName - $endLocationName";
					}
				}
			}
				
			// set data to return to user saved 'description' if defined in database
			if ($description != '')
			{
				$details = $description;
			}
			
			// save detail and data to array
			if ($details != '' AND $pastdetails != $details)
			{
				$eventData[$j]['date'] = formatDate($date, $dateAccuracy);
				$eventData[$j]['details'] = $details;
				$j++;
			}
			
			$i++;
			$pastdate = $date;
			$pastdetails = $details;
			
		}	
		//end while
	} 
	//end if
	
	//swap for opening event is first, it all are the same date
	for ($i = 0; $i < min(sizeof($eventData), 3); $i++)
	{
		$dateA = $eventData[$i]['date'];
		$dateB = $eventData[$i+1]['date'];
		
		// check same date
		if ($dateB == $dateA)
		{
			$detailsA = substr($eventData[$i]['details'], 0, 4);
			$detailsB = substr($eventData[$i+1]['details'], 0, 4);
			
			// switch-a-roo if line details not first
			if ($detailsA != 'Line' AND $detailsB == 'Line')
			{
				$temp = $eventData[$i];
				$eventData[$i] = $eventData[$i+1];
				$eventData[$i+1] = $temp;
			}
		}
	}
	
	return $eventData;	
	//end function
}

function getNumberEventsSameDay($result, $i)
{
	// skipahead is used to check when location result IDs are dupicates, due to DB joins or something
	$extraEvents = 1;
	$skipAhead = 1;
	
	$type = MYSQL_RESULT($result,$i,"safeworking_why");
	$plainDate = MYSQL_RESULT($result,$i,"date");
	$eventId = MYSQL_RESULT($result,$i,"event_id");
	$numberOfRows = MYSQL_NUM_ROWS($result);
	
	while ($skipAhead < ($numberOfRows-$i))
	{
		if ($plainDate == MYSQL_RESULT($result,$i+$skipAhead,"date")
			AND MYSQL_RESULT($result,$i+$skipAhead,"safeworking_why") == $type
			AND $eventId != MYSQL_RESULT($result,$i+$skipAhead,"event_id"))
		{
			$skipAhead++;
			$extraEvents++;
		}
		else if ($plainDate == MYSQL_RESULT($result,$i+$skipAhead,"date")
			AND MYSQL_RESULT($result,$i+$skipAhead,"safeworking_why") == $type)
		{
			$skipAhead++;
		}
		else
		{
			break;
		}
	}
	
	return $extraEvents;
}

/*
 * 
 * Get all events for a given location
 * Gets passed a location
 * Returns 2D array of events
 *
 * Type specific
 *
 * Top level is:
 * Location, 'Line Opened', 'Track Amplified', 'Gauge Conversion', 'Safeworking', 'Line Closed'
 * Next level has the heading as first entry
 * Then following entries are the events themselves
 * broken up into date and details
 *

Array
(
    [0] => Array
    (
    	[0] => Location
        [1] => Array
        (
        	[0] => Array
            (
            	[date] => Thursday, 20 January 1944
                [details] => Opened
			) 
 
 *
 */
function getAllLocationLineEvents($location, $showEverything)
{
	$ownLocationEvents = getLocationEvents($location);
	if (sizeof($ownLocationEvents) > 0)
	{
		$toreturn[] = array('Location', $ownLocationEvents);
	}
	
	// only do extra tpyes of event if warranted
	if ($showEverything)
	{
		$locationEventTypes = array('Line Opened', 'Track Amplified', 'Gauge Conversion', 'Safeworking', 'Line Closed');
			
		foreach ($locationEventTypes AS $eventType)
		{
			$eventResults = getLocationLineEvents($eventType, $location);
			
			if (sizeof($eventResults) != 0)
			{
				$toreturn[] = array($eventType, $eventResults);
			}
		}
	}
	return $toreturn;
}

function drawEventsTable($dataarray)
{	
	$numberOfRows = sizeof($dataarray);
	
	if ($numberOfRows > 0)
	{	
?>
<table class="linedTable">
<?	
	$i=0;
	$j=0;
	while ($i<$numberOfRows)
	{	
		if ($j%2 == '0')
		{
			$style = 'class="x"';
		}
		else
		{
			$style = 'class="y"';
		}
		
		if ($dataarray[$i]['details'] != '')
		{
?>
<tr <? echo $style; ?> valign="top">
	<td class="date"><? echo $dataarray[$i]['date']; ?></td>
	<td><? echo $dataarray[$i]['details'];	?></td>
</tr>
<?
			$j++;
		}
		$i++;
	}	//end while
?>
</table>
<?	} //end if
	//end function
}

function getLocationEventDetails($details)
{
	// for a road crossing, you might need to change events from magic number to text
	if (is_numeric($details))
	{
		switch ($details) 
		{
			//	on the level
			case '8':	
				$details = 'Crossing provided';
				break;
			case '9':
				$details = 'Flashing lights provided';
				break;
			case '10':
				$details = 'Boom barriers provided';
				break;
			case '11':
				$details = 'Boom barriers and pedestrian gates provided';
				break;
			case '12':
				$details = 'Pedestrian gates provided';
				break;
			case '13':
				$details = 'Hand gates provided';
				break;
			case '14':
				$details = 'Crib crossing provided';
				break;
			case '38':
				$details = 'Interlocked gates provided';
				break;
			case '39':
				$details = 'Wicket gates provided';
				break;
			//	road bridges
			case '1':
				$details = 'Road underpass provided';
				break;
			case '2':
				$details = 'Road overbridge provided';
				break;
			case '3':
				$details = 'Pedestrian subway';
				break;
			case '4':
				$details = 'Footbridge provided';
				break;
			default:
				$details = '';
				break;
		}
	}
	
	return $details;
}	// end function

/* 
 * retrieves all the events for a location
 * plus for safeworking
 * duplication
 * opening and closure too
 * depending on what type of location it is
 *
 * returns associtive array "$locationevents"
 */
function getLocationEvents($location)
{
	//$id, $open, $close, $openPlain, $stillOpen
	//extract($location);
	//print_r($location);
	
	$sql = "SELECT DATE_FORMAT(date, '".DATE_FORMAT."') AS fdate, 
		date, dateAccuracy, details, source, sourcedetail 
		FROM location_events 
		WHERE location = '".mysql_real_escape_string($location['id'])."' 
		ORDER BY date ASC";
	$result = MYSQL_QUERY($sql, locationDBconnect());
	$numberOfRows = MYSQL_NUM_ROWS($result);
	$i=0;
	
	if ($numberOfRows>0) 
	{
		// for the opening if none listed under events
		if ($location['openPlain'] < MYSQL_RESULT($result,$i,"date") AND $location['isCrossing'] == false AND $location['openPlain'] != DATE_UNKNOWN_OPEN)
		{	
			$locationevents[0]["date"] = formatDate($location['open'], $location['approxOpen']);
			$locationevents[0]["details"] = 'Opened';
			$i++;
		}
		
		while ($i < $numberOfRows)
		{	
			// initial setup of date and details
			$date = formatDate(MYSQL_RESULT($result,$i,"fdate"), MYSQL_RESULT($result,$i,"dateAccuracy"));
			$details = getLocationEventDetails(stripslashes(MYSQL_RESULT($result,$i,"details")));
			
			// asssign value to be returned
			$locationevents[$i]["date"] = $date;
			$locationevents[$i]["details"] = $details;
			$i++;
		}	
		//end while
		
		// add closing details
		if (!$location['stillOpen'] AND $date != $location['close'] ) 
		{
			$locationevents[$i]["date"] = formatDate($location['close'], $location['approxClose']);
			$locationevents[$i]["details"] = 'Closed';
		}
	} 		
	//	end "$numberOfRows>0" if

	//	if no events are found at all
	else
	{	
		$i = 0;
		if ($location['openPlain'] != DATE_UNKNOWN_OPEN AND !$location['isCrossing'])	
		{
			$locationevents[$i]["date"] = formatDate($location['open'], $location['approxOpen']);
			$locationevents[$i]["details"] = 'Opened';
			$i++;
		}
			
		if (!$location['stillOpen']) 
		{ 
			$locationevents[$i]["date"] = formatDate($location['close'], $location['approxClose']);
			$locationevents[$i]["details"] = 'Closed';
		}
	}
	
	return $locationevents;	//end function
}

/*
function getEventCredits($source)
{
	if ($source != '')
	{
		$sourceResultsSQL = sprintf("SELECT * FROM sources WHERE source_id = '%s'", mysql_real_escape_string($source));
		$sourceResults = MYSQL_QUERY($sourceResultsSQL, locationDBconnect());
		
		if (MYSQL_RESULT($sourceResults) == 1)
		{
			$sourceShort = stripslashes($sourceResults, 0,"short");
			$sourceName = stripslashes($sourceResults, 0,"name");
			return '<a title="Credit: '.$sourceName.'" href="sources.php#id'.$source.'">('.$sourceShort.')</a>';
		}
	}
}	//end function
*/

?>
