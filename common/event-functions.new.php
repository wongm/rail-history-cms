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
?>		
<h4><?=$type?></h4>
<?
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
			FROM locations l, location_types lt, locations_raillines lr 
			WHERE display != 'tracks' AND lr.location_id = l.location_id
			AND type = type_id AND (".isImportantLocation().") 
			AND line_id = $lineId AND name != '' AND l.open != '".DATE_UNKNOWN_OPEN."' 
			ORDER BY l.open";
	}
	elseif ($type == 'Location Closed')
	{
		$desc = 'closed';
		$sql = "SELECT DATE_FORMAT(close, '".DATE_FORMAT."') AS fdate, closeAccuracy AS dateaccuracy, 
			l.location_id, l.name, basic, type_id, '".$desc."' AS constantdesc 
			FROM locations l, location_types lt, locations_raillines lr 
			WHERE display != 'tracks' AND l.location_id = lr.location_id
			AND type = type_id AND (".isImportantLocation().") AND line_id = $lineId 
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
	
	// array format - DATE - EVENT DSECRIPTION
	$eventData = array();
	
	if ($locationID != "")
	{
		$sqlBetween = " AND ( start_location = '".$locationID."' OR end_location = '".$locationID."' OR ( STARTKM.km <= '".$locationKm."' AND ENDKM.km >= '".$locationKm."'  ) ) ";
		$sqlOn = "AND ( start_location = '".$locationID."' OR end_location = '".$locationID."' OR safeworking_middle = '".$locationID."')";
	}
	
	// sorts out what events we want
	switch ($type)
	{
		case 'Line Opened':
			$sqlBit = $sqlBetween." AND safeworking_why != 'singled' AND (safeworking_why = 'opened')  ";
			//$sqlBit = $sqlBetween." AND tracks = '1' ";
			break;
		case 'Track Amplified':
			$sqlBit = $sqlBetween." AND ((RE.tracks > 1 AND safeworking_why != 'opened') OR safeworking_why = 'singled' ) ";
			//$sqlBit = $sqlBetween." AND tracks > '1' ";
			break;
		case 'Safeworking':
			$sqlBit = $sqlOn." AND safeworking != '' ";
			break;
		case 'Line Closed':
			$sqlBit = $sqlBetween." AND RE.tracks = '0' ";
			break;
		case 'Gauge Conversion':
			$sqlBit = $sqlBetween." AND gauge != 'BG' ";
			break;
		case 'By Date':
			$sqlBit = " AND (( safeworking_why != 'singled' AND (safeworking_why = 'opened') ) 
				OR ( ((tracks > 1 AND safeworking_why != 'opened') OR safeworking_why = 'singled' ) ) 
				OR ( safeworking != '' ) OR ( tracks = '0' ) OR ( gauge != 'BG' ) 
				OR ( RE.description != '' AND start_location = '' AND end_location = '' )) ";
			break;
		default:
			$sqlBit = " AND RE.description != '' AND start_location = '' AND end_location = '' ";	
			break;			
	}
	
	// super convoluted to make sure all events get in - union all
	if ($type != 'By Date')
	{
		$sql = "SELECT DATE_FORMAT(date, '".DATE_FORMAT."') AS fdate, RE.tracks, safeworking, 
		gauge, start_location, end_location, date, line, RE.description, 
		safeworking_middle, safeworking_why, source, sourcedetail, dateAccuracy,
		STARTKM.km AS start_distance, ENDKM.km AS end_distance,
		STARTLOCATION.name AS start_name, ENDLOCATION.name AS end_name 
		FROM railline_events RE, locations_raillines STARTKM, locations_raillines ENDKM, 
		locations STARTLOCATION, locations ENDLOCATION 
		WHERE $lineSQL $sqlBit AND RE.display != 'hide' 
		AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
		AND start_location = STARTLOCATION.location_id AND end_location = ENDLOCATION.location_id 
		ORDER BY date ASC";
	}
	else
	{
		$sql = "SELECT DATE_FORMAT(date, '".DATE_FORMAT."') AS fdate, date AS plaindate, RE.tracks, safeworking, 
		gauge, start_location, STARTKM.km AS start_distance, end_location, 
		ENDKM.km AS end_distance, date, line, RE.description, 
		safeworking_middle, '-', safeworking_why, source, sourcedetail, dateAccuracy,
		STARTLOCATION.name AS start_name, ENDLOCATION.name AS end_name 
		FROM railline_events RE, locations_raillines STARTKM, locations_raillines ENDKM, 
		locations STARTLOCATION, locations ENDLOCATION 
		WHERE $lineSQL AND (( safeworking_why != 'singled' 
		AND (safeworking_why = 'opened') ) OR ( ((RE.tracks > '1' AND safeworking_why != 'opened') 
		OR safeworking_why = 'singled' ) ) OR ( safeworking != '' ) OR ( RE.tracks = '0' ) 
		OR ( gauge != 'BG' ) OR ( RE.description != '' AND start_location = '' AND end_location = '' ))  
		AND RE.display != 'hide'
		AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
		AND start_location = STARTLOCATION.location_id AND end_location = ENDLOCATION.location_id 
		UNION
		SELECT DATE_FORMAT(l.open, '".DATE_FORMAT."') AS fdate, l.open AS plaindate, 'opened', '-', l.name AS gauge, 
		'-', '-', '-', '-', l.open, line_id AS line, '', 
		'-', '-', basic, '-', '-', l.openAccuracy AS dateAccuracy, '-', '-' 
		FROM locations l, locations_raillines lr, location_types lt 
		WHERE display != 'tracks' AND lr.location_id = l.location_id 
		AND type = type_id AND (".isImportantLocation().") AND line_id = '$lineId' AND name != '' 
		AND open != '".DATE_UNKNOWN_OPEN."' AND open != '".DATE_UNKNOWN_CLOSE."' 
		UNION
		SELECT DATE_FORMAT(l.close, '".DATE_FORMAT."')  AS fdate, l.close AS plaindate, 'closed', '-', l.name AS gauge, 
		'-', '-', '-', '-', l.close, line_id AS line, '', 
		'-', '-', basic, '-', '-', l.closeAccuracy AS dateAccuracy, '-', '-' 
		FROM locations l, locations_raillines lr, location_types lt 
		WHERE display != 'tracks' AND lr.location_id = l.location_id 
		AND type = type_id AND (".isImportantLocation().") AND line_id = '$lineId' AND name != '' 
		AND close != '".DATE_NULL."' AND close != '".DATE_UNKNOWN_CLOSE."' 
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
			$tracks = MYSQL_RESULT($result,$i,"tracks");
			$safeworking = stripslashes(MYSQL_RESULT($result,$i,"safeworking"));
			$safeworkingType = MYSQL_RESULT($result,$i,"safeworking_why");
			$gauge = stripslashes(MYSQL_RESULT($result,$i,"gauge"));
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
			
			/*
			if ($startID != 0 AND $endID != 0)
			{
				$start = stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$startID."'", locationDBconnect()), 0,"name"));
				$end = stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$endID."'", locationDBconnect()), 0,"name"));
			}
			*/
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
					$details = $gauge.' '.strtolower($safeworkingType).' opened';
					$details = eregi_replace('industry', '(industry)', $details);
					break;
				case 'closed':
					$details = $gauge.' '.strtolower($safeworkingType).' closed';
					$details = eregi_replace('industry', '(industry)', $details);
					break;
			}
			
			switch ($safeworkingType)
			{
				case 'singled':
					$details = 'Line singled '.$startLocationName.' to '.$endLocationName;	
					break;
				case 'opened':
					$details = 'Line opened '.$startLocationName.' to '.$endLocationName;	
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
				$sqlsameDate = "SELECT *  
					FROM railline_events, locations_raillines STARTKM, locations_raillines ENDKM 
					WHERE (STARTKM.line_id = '".$lineId."' AND ENDKM.line_id = '".$lineId."') 
					AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
					AND date = '".$plainDate."' AND safeworking_why = 'opened'";
				$sameDate = MYSQL_NUM_ROWS(MYSQL_QUERY($sqlsameDate, locationDBconnect()));
				
				if ($sameDate > '1' AND !$tram AND $locationKm != '')	// chains together events of the same date
				{
					$details = 'Line opened '.$startLocationName;
					
					$sqlmultilocations = "SELECT * , STARTKM.km AS start_distance, ENDKM.km AS end_distance 
							FROM railline_events, locations_raillines STARTKM, locations_raillines ENDKM 
							WHERE (STARTKM.km <= '".$locationKm."' AND ENDKM.km >= '".$locationKm."') 
							AND (STARTKM.line_id = '".$lineId."' AND ENDKM.line_id = '".$lineId."') 
							AND date = '".$plainDate."' AND safeworking_why = 'opened'  
							AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
							ORDER BY STARTKM.km ASC";
					$multiLocations = MYSQL_QUERY($sqlmultilocations, locationDBconnect());
					$sameDate = MYSQL_NUM_ROWS($multiLocations);
					
					for ($x = 0; $x < $sameDate; $x++)
					{
						$middleID = MYSQL_RESULT($multiLocations,$x,"end_location");
						$details = $details.' - '.stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$middleID."'", locationDBconnect()),0,"name"));
					}
					$i = $i + ($sameDate - 1);
					$pastdate = $date;
				}
			}
			
			if ($type == 'Track Amplified')
			{
				$sameDate = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT * FROM railline_events WHERE line = '".$lineId."' AND date = '".$plainDate."' ", locationDBconnect()));
				
				if ($sameDate > '1' AND !$tram)	// chains together events of the same date
				{
					// for specific location
					if ($locationID != "")
					{
						$sqlmultilocations = "SELECT * , STARTKM.km AS start_distance, ENDKM.km AS end_distance 
							FROM railline_events, locations_raillines STARTKM, locations_raillines ENDKM 
							WHERE (STARTKM.km <= '".$locationKm."' AND ENDKM.km >= '".$locationKm."') 
							AND line = '".$lineId."' AND date = '".$plainDate."' 
							AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
							ORDER BY STARTKM.km ASC";
						$multiLocations = MYSQL_QUERY($sqlmultilocations, locationDBconnect());
						$sameDate = MYSQL_NUM_ROWS($multiLocations);
					}
					// for lineguide
					else
					{
						$sqlmultilocations = "SELECT * , safeworking, STARTKM.km AS start_distance, ENDKM.km AS end_distance 
							FROM railline_events, locations_raillines STARTKM, locations_raillines ENDKM  
							WHERE line = '".$lineId."' AND date = '".$plainDate."' 
							AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
							ORDER BY STARTKM.km ASC";
						$multiLocations = MYSQL_QUERY($sqlmultilocations, locationDBconnect());
					}
					
					if ($sameDate > 1 AND !$tram)
					{
						$startID = MYSQL_RESULT($multiLocations,'0',"start_location");
						$starttracks = MYSQL_RESULT($multiLocations,'0',"tracks");
						$startsafe = MYSQL_RESULT($multiLocations,'0',"safeworking");
						$subdetails = split(' ', $details);
						//echo $startsafe.'-';
						$details = 'Line '.$subdetails[1].' '.stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$startID."'", locationDBconnect()), 0,"name"));
						
						for ($x = 0; $x < $sameDate; $x++)
						{
							$middleID = MYSQL_RESULT($multiLocations,$x,"end_location");
							$middletracks = MYSQL_RESULT($multiLocations,$x,"tracks");
							$middlesafe = MYSQL_RESULT($multiLocations,$x,"safeworking");
							//echo $middlesafe.'<br>';
							
							if($middletracks == $starttracks AND $middlesafe == $startsafe)
							{
								$details = $details.' - '.stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$middleID."'", locationDBconnect()),0,"name"));
							}
							else
							{
								$sameDate = 1;
							}
							
						}
					}
					else
					{
						$sameDate = 1;
					}
					$i = $i + ($sameDate - 1);
					$pastdate = $date;
				}
			}
			
			// nice names for safeworking events
			elseif ($type == 'Safeworking' OR ($type == 'By Date' AND $safeworking != '' AND $safeworking != '-' AND $date != $pastdate) )
			{
				$sameDate = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT * FROM railline_events WHERE line = '".$lineId."' AND date = '".$plainDate."' AND (safeworking_why = 'replaced' OR safeworking_why = 'opened') AND safeworking != ''", locationDBconnect()));
				$nicename = stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM safeworking_types WHERE link = '".$safeworking."'", locationDBconnect()), 0,"name"));
				
				if ($locationID != "" AND $middleID != '0')	// for specific locations AND single section split into two
				{
					$middle = stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$middleID."'", locationDBconnect()), 0,"name"));
					
					if ($safeworkingType == 'closed')
					{
						$details = $middle.' closed. '.$nicename.' section now '.$startLocationName.' - '.$endLocationName;$status = ' closed. ';
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
							$middleDistance = MYSQL_RESULT(MYSQL_QUERY("SELECT km FROM locations_raillines WHERE location_id = '".$middleloc."'", locationDBconnect()),"km");
						}
	
						if ($locationKm < $middleDistance)
						{
							$details = $middle.' opened. '.$nicename.' section now '.$startLocationName.' - '.$middle;
						}
						else
						{
							$details = $middle.' opened. '.$nicename.' section now '.$middle.' - '.$endLocationName;
						}
					}
				}
				elseif ($sameDate > '1')	// chains together events of the same date
				{
					// for specific location
					if ($locationID != "")
					{
						$sqlmultilocations = "SELECT * , STARTKM.km AS start_distance, ENDKM.km AS end_distance 
							FROM railline_events, locations_raillines STARTKM, locations_raillines ENDKM 
							WHERE (STARTKM.km <= '".$locationKm."' AND ENDKM.km >= '".$locationKm."') 
							AND line = '".$lineId."' AND date = '".$plainDate."' 
							AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
							ORDER BY STARTKM.km ASC";
						$multiLocations = MYSQL_QUERY($sqlmultilocations, locationDBconnect());
						$sameDate = MYSQL_NUM_ROWS($multiLocations);
					}
					// for lineguide
					else
					{
						$sqlmultilocations = "SELECT * , STARTKM.km AS start_distance, ENDKM.km AS end_distance 
							FROM railline_events, locations_raillines STARTKM, locations_raillines ENDKM  
							WHERE line = '".$lineId."' AND date = '".$plainDate."' 
							AND start_location = STARTKM.location_id AND end_location = ENDKM.location_id 
							ORDER BY STARTKM.km ASC";
						$multiLocations = MYSQL_QUERY($sqlmultilocations, locationDBconnect());
					}
					$startID = MYSQL_RESULT($multiLocations,'0',"start_location");
					$details = $nicename.' provided '.stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$startID."'", locationDBconnect()), 0,"name"));
					
					for ($x = 0; $x < $sameDate; $x++)
					{
						$middleID = MYSQL_RESULT($multiLocations,$x,"end_location");
						$details = $details.' - '.stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$middleID."'", locationDBconnect()),0,"name"));
					}
					$i = $i + ($sameDate - 1);
				}
				
				else
				{
					// lineguide page, for middle locations
					if ($middleID != '0')	
					{
						$middle = stripslashes(MYSQL_RESULT(MYSQL_QUERY("SELECT * FROM locations WHERE location_id = '".$middleID."'", locationDBconnect()), 0,"name"));
						
						if ($safeworkingType == 'closed')
						{
							$details = "$middle closed. $nicename section now $startLocationName - $endLocationName";
							$status = ' closed. ';
						}
						else
						{
							$details = "$middle opened in $nicename section $startLocationName - $endLocationName";
						}
					}
					// for yard limits
					else if ($nicename == 'Yard Limits')
					{
						if ($safeworkingType == 'downgrade')
						{
							$details = 'Main line '.$startLocationName.' - '.$endLocationName.' downgraded to siding';
						}
						else
						{
							$details = '';
						}
						
					}
					// plain old default
					else
					{
						$details = $nicename.' provided '.$startLocationName.' - '.$endLocationName;
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


/*
 * 
 * Get all events for a given location
 * Gets passed a location
 * Returns 2D array of events
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
function getAllLocationLineEvents($location)
{
	$locationEventTypes = array('Line Opened', 'Track Amplified', 'Gauge Conversion', 'Safeworking', 'Line Closed');
	
	$ownLocationEvents = getLocationEvents($location);
	if (sizeof($ownLocationEvents) > 0)
	{
		$toreturn[] = array('Location', $ownLocationEvents);
	}
	
	foreach ($locationEventTypes AS $eventType)
	{
		$eventResults = getLocationLineEvents($eventType, $location);
		if (sizeof($eventResults) != 0)
		{
			$toreturn[] = array($eventType, $eventResults);
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
			$style = 'odd';
		}
		else
		{
			$style = 'even';
		}
		
		if ($dataarray[$i]['details'] != '')
		{
?>
<tr class="<? echo $style; ?>">
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
?>
