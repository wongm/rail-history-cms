<?php
include_once("common/dbConnection.php");

/*
 *	returns an array of data for a location
 */
function getLocation($locationToFind, $boxToFind, $idToFind, $linelink)
{
	// setup the SQL to search by
	if ($boxToFind != '')
	{
		$locationToFind = $boxToFind;
		$toFind = "l.name = '".mysql_real_escape_string($boxToFind)."' AND type = '".TYPE_SIGNAL_BOX."' AND ";
	}
	else
	{
		if ($idToFind != '')
		{
			if (is_numeric($idToFind))
			{
				$toFind = "l.location_id = '".mysql_real_escape_string($idToFind)."' AND ";
			}
			else
			{
				$toFind .= "l.name = '".mysql_real_escape_string($idToFind)."' AND ";
			}
		}
		if ($locationToFind != '')
		{
			$toFind .= "l.name = '".mysql_real_escape_string($locationToFind)."' AND ";
		}
		
	}
	
	// check for duplicates from DB
	$sqlForDuplicates = "SELECT * FROM locations l WHERE ".$toFind." ".SQL_NEXTABLE." ORDER BY l.location_id ASC";
	$resultsForDuplicates = MYSQL_QUERY($sqlForDuplicates, locationDBconnect());
	$duplicates = MYSQL_NUM_ROWS($resultsForDuplicates);
	
	// no locations found!?
	if ($duplicates == 0)
	{
		$location['error'] = 'empty';
		$location['locationToFind'] = $locationToFind;
		return $location;
		
	}
	// found duplicates by that name
	elseif ($duplicates > 1)
	{
		if ($linelink != '')
		{
			$lineresult = MYSQL_QUERY("SELECT line_id FROM raillines 
				WHERE link = '".mysql_real_escape_string($linelink)."' LIMIT 1", locationDBconnect());
			if (MYSQL_NUM_ROWS($lineresult) == 1)
			{
				$lineid = MYSQL_RESULT($lineresult,0,'line_id');
				$toFind .= " lr.line_id = '".mysql_real_escape_string($lineid)."' AND ";
			}
			// error - no locations found
			else
			{
				$location['error'] = 'empty';
				$location['locationToFind'] = $locationToFind;
				return $location;
			}
		}
		
		// recheck to exclude signal boxes AND ones on other lines
		$recheckSQL = "SELECT * FROM locations l, locations_raillines lr, location_types lt, raillines r 
			WHERE l.location_id = lr.location_id AND l.type != '".TYPE_SIGNAL_BOX."' 
			AND ".$toFind." ".SQL_NEXTABLE."   AND lt.type_id = l.type 
			AND lr.line_id = r.line_id 
			GROUP BY l.location_id ORDER BY l.location_id ASC";
		$recheckresult = MYSQL_QUERY($recheckSQL, locationDBconnect());
		$duplicates = MYSQL_NUM_ROWS($recheckresult);
		$hasSignalBox = false;
		
		// failed - same name
		if ($duplicates > 1)
		{
			$location['error'] = 'duplicates';
			$location['duplicates'] = $recheckresult;
			return $location;
		}
		else
		{
			$hasSignalBox = true;
			$duplicates = 1;
			$idToFind = MYSQL_RESULT($recheckresult,0,'location_id');
			$toFind = "l.location_id = '".mysql_real_escape_string($idToFind)."' AND ";
		}
	}
	// if no duplicates - spit it out!
	if ($duplicates == 1)
	{
		$sql = "SELECT * , DATE_FORMAT(open, '".DATE_FORMAT."') AS fopen, DATE_FORMAT(close, '".DATE_FORMAT."') 
			AS fclose, DATE_FORMAT(l.modified, '".DATE_FORMAT."') AS fmodified
			FROM locations l, locations_raillines lr, raillines r, location_types lt 
			WHERE ".$toFind." r.line_id = lr.line_id AND l.location_id = lr.location_id AND lt.type_id = l.type 
			AND display != 'tracks' AND r.todisplay != 'hide' ORDER BY l.location_id ASC, lr.junctiontype, r.order ASC";
		$location["result"] = $result = MYSQL_QUERY($sql, locationDBconnect());
		
		// set output status
		$location["error"] = false;
		
		// page header stuff
		$location["name"] = stripslashes(MYSQL_RESULT($result,'0',"name"));
		$location["type"] = MYSQL_RESULT($result,'0',"type");
		$location["pageTitle"] = getLocationName($location["name"], $location["type"]);
		
		// collect data into an array
		$location["id"] = MYSQL_RESULT($result,'0',"location_id");
		$location["locationLink"] = str_replace(' ', '-', strtolower($location["name"]));
		$location["uniqueId"] = $location["id"];
		$location["display"] = MYSQL_RESULT($result,'0',"display");
		$location["url"] = MYSQL_RESULT($result,'0',"url");
		$location["description"] = stripslashes(MYSQL_RESULT($result,'0',"description"));
		$location["credits"] = stripslashes(MYSQL_RESULT($result,'0',"l.credits"));
		$location["image"] = MYSQL_RESULT($result,'0',"image");
		$location["diagrams"] = MYSQL_RESULT($result,'0',"diagrams");
		$location["typeToDisplay"] = MYSQL_RESULT($result,'0',"lt.basic");
		$location["approxOpen"] = MYSQL_RESULT($result,'0',"openAccuracy");
		$location["approxClose"] = MYSQL_RESULT($result,'0',"closeAccuracy");
		$location["stillOpen"] = MYSQL_RESULT($result,'0',"close") > date('Y-m-d');
		$location["openPlain"] = MYSQL_RESULT($result,'0',"open");
		$location["closePlain"] = MYSQL_RESULT($result,'0',"close");
		$location["coords"] = MYSQL_RESULT($result,'0',"long");
		$location["photos"] = MYSQL_RESULT($result,'0',"photos");
		$location["open"] = formatDate(MYSQL_RESULT($result,'0',"fopen"), $approxOpen);
		$location["close"] = formatDate(MYSQL_RESULT($result,'0',"fclose"), $approxClose);
		$location["todisplay"] = MYSQL_RESULT($result,'0',"r.todisplay");
		$location["updated"] = MYSQL_RESULT($result,'0',"fmodified");
		
		// get stations and signal boxes with the same name as this one
		$location["associatedLocations"] = getAssociatedLocations($location["id"], $location["name"], $location["type"]);
		$location["hasAssociatedLocations"] = (bool)$location["associatedLocations"];
		
		// setup distances
		$location["kmAccuracy"] = MYSQL_RESULT($result,'0',"lr.kmAccuracy");
		if ($location["kmAccuracy"] == 'exact')
		{
			$location["exactKm"] =  true;
			$location["hideKm"] = false;
		}
		else
		{
			$location["hideKm"] = true;
			$location["exactKm"] =  false;
		}
		
		// for locations on multiple lines
		$location["numberoflines"] = $numberoflines = MYSQL_NUM_ROWS($result);
		$location["km"] = MYSQL_RESULT($result,0,"lr.km");
		$location["lineName"] = MYSQL_RESULT($result,0,"r.name");
		$location["lineLink"] = MYSQL_RESULT($result,0,"r.link");
		$location["lineId"] = MYSQL_RESULT($result,0,"r.line_id");
		$location['lineType'] = MYSQL_RESULT($result,0,"lr.junctiontype");
		
		if ($numberoflines > 1)
		{
			for ($i = 0; $i < $numberoflines; $i++)
			{
				$branchline[$i]['link'] = MYSQL_RESULT($result,$i,"r.link");
				$branchline[$i]['name'] = MYSQL_RESULT($result,$i,"r.name");
				$branchline[$i]['lineId'] = MYSQL_RESULT($result,$i,"r.line_id");
				$branchline[$i]['km'] = MYSQL_RESULT($result,$i,"lr.km");
				$branchline[$i]['type'] = MYSQL_RESULT($result,$i,"lr.junctiontype");
			}
			$location['branchlines'] = $branchline;
		}
		
		// booleans to work out for a location
		$location["hasSignalBox"] = $hasSignalBox;
		$location["isCrossing"] = typeIsCrossing($location["type"]);
		$location["isStation"] = typeIsStation($location["type"]);
		$location["isSignalBox"] = ($location["type"] == TYPE_SIGNAL_BOX);
		
		// next and backward locations
		$location["nextLocation"] = getNeighbourLocation($location["id"], $location["km"], $location["lineLink"], $location["lineId"], 'next');
		$location["backLocation"] = getNeighbourLocation($location["id"], $location["km"], $location["lineLink"], $location["lineId"], 'back');
		
		
		global $dbHits;
		echo $dbHits;
		
		return 	$location;
	}	// end zero result if
}		// end function


/*
 * gets the next location on a line
 * forwards or backwards
 */
function getNeighbourLocation($id, $km, $linelink, $lineId, $way)
{
	$bit = '';
	
	if ($_REQUEST['line'] != '')
	{		
		$linelink = str_replace('lines', 'foolines', $_REQUEST['line']);
		$linelink = str_replace('-line', '', $linelink);
		$linelink = str_replace('foolines', 'lines', $linelink);
	}
	
	if ($way == 'back')
	{
		$sqlNext = "SELECT * FROM locations l, locations_raillines lr 
		WHERE l.location_id = lr.location_id AND lr.km < '".mysql_real_escape_string($km)."' 
		AND (lr.line_id = '".mysql_real_escape_string($lineId)."') 
		AND ".SQL_NEXTABLE."  ORDER BY lr.km DESC";
	}
	else
	{
		$sqlNext = "SELECT * FROM locations l, locations_raillines lr 
		WHERE l.location_id = lr.location_id AND lr.km > '".mysql_real_escape_string($km)."' 
		AND (lr.line_id = '".mysql_real_escape_string($lineId)."')  
		AND ".SQL_NEXTABLE." ORDER BY lr.km ASC";
	}
	$resultNext = MYSQL_QUERY($sqlNext, locationDBconnect());
	
	if (MYSQL_NUM_ROWS($resultNext) > '0')	
	{
		$name = stripslashes(MYSQL_RESULT($resultNext,0,"name"));
		$id = stripslashes(MYSQL_RESULT($resultNext,0,"location_id"));
		$type = stripslashes(MYSQL_RESULT($resultNext,0,"type"));
		$base = str_replace(' ', '-', strtolower($name));
		
		$name = getLocationName($name, $type);
		
		// for junctions
		if ($type == TYPE_JUNCTION)
		{
			$bit = '/'.$linelink;
		}
		// for signal boxes
		else if ($type == TYPE_SIGNAL_BOX)
		{
			$base = $base.'/box';
		}
				
		// check if unique name
		$sharedLocationNameSQL = sprintf("SELECT * FROM locations 
			WHERE `name` = '%s' AND display != 'tracks' AND type != 'TYPE_SIGNAL_BOX'", 
			mysql_real_escape_string($name));
		$sharedLocationName = MYSQL_NUM_ROWS(MYSQL_QUERY($sharedLocationNameSQL, locationDBconnect())) > 1;
		
		if ($sharedLocationName)
		{
			$base = $id;
		}
		
		if ($way == 'back')
		{
			return '<a class="prev" href="/location/'.$base.$bit.'" alt="Previous Location" title="Previous Location" >&laquo; '.$name.'</a>'; 
		}
		else
		{
			return '<a class="next" href="/location/'.$base.$bit.'" alt="Next Location" title="Next Location">'.$name.' &raquo;</a>'; 
		}
	}
}



/* 
 * gets all track diagrams for a location in an array
 * REWRITTEN 16 JULY 2007 - to check for existance of 'OPENING' images
 * and to use an array as well - much cleaner code
 * FFS - javascript is a bitch!
 */
function getLocationDiagrams($location)
{
	//$id, $image, $open, $diagrams, $name
	extract($location);
	
	// for when a CSV string is given
	if ($diagrams != '' AND $diagrams != '0')
	{
		$diagrams = split(';', $diagrams);
		$numberOfYears = sizeof($diagrams);
		$added = 'full-';
	}
	else
	{
		$sql = "SELECT * FROM location_years WHERE `location` = '".mysql_real_escape_string($id)."' ORDER BY year ASC";
		$result = MYSQL_QUERY($sql, locationDBconnect());
		$numberOfYears = MYSQL_NUM_ROWS($result);
	}
	
	// array format - URL, ALT-TEXT, TAB-TITLE
	$diagramData = array();
	
	// test if image URL is set, otherwise use name
	if ($image == '')
	{
		$image = strtolower($name);
		$image = str_replace(' ', '-', $image);
	}
	
	// only if more than one diagram
	if ($numberOfYears >= 1)
	{
		// check initial image exists - if so then add it
		if (file_exists('./t/'.$image.'-'.$added.'open.gif'))
		{
			$diagramData[] = array($image.'-'.$added.'open', $name.' on opening', 'Opening');
		}
		
		// create an array full of all other diagrams
		for ($i = 0; $i < $numberOfYears; $i++)
		{
			if ($diagrams != '' AND $diagrams != '0')
			{
				$year = $diagrams[$i];
			}
			else
			{
				$year = MYSQL_RESULT($result,$i,"year");
			}
			
			//echo $year.'-';
			$diagramData[] = array($image.'-'.$added.$year,$name.' '.$year,$year);
		}
	}
	// if there is only ever one image
	elseif ($numberOfYears == 1 AND file_exists('./t/'.$image.'-'.$added.$diagrams[0].'.gif')) 
	{
		$diagramData[] = array($image.'-'.$added.$diagrams[0], $name.' '.$diagrams[0], $diagrams[0]);
	}
	// if there is only ever one image for a 'full' version
	elseif (file_exists('./t/'.$image.'.gif'))
	{
		$diagramData[] = array($image, "$name on opening", 'Opening');
	}
	else
	{
		$diagramData = '';
	}
	
	return $diagramData;
}



/* 	
 *	collects table infomation
 *	you need to give it an SQL result
 *  that contains Locations
 *
 * "$DISPLAYTYPE"
 * 'blank' for PHOTOS - EVENTS - HISTORY - LOCATION
 * 'line' for locations shown by type - added LINE and TYPE
 * 'search' for only some locations, inclused highlight of search term - added LINE and TYPE
 * 'updated' to show updated locations - added LINE and TYPE and UPDATED
 *
 * "$KEYWORD"
 * for a location source
 * 
 */
function getLocationsOnlyTable($resultLocations, $displaytype, $keyword='')
{
	$numberOfLocations = MYSQL_NUM_ROWS($resultLocations);	
	
	$toreturn['sorttext'] 	 = '';
	$toreturn['headerurl'] 	 = '';
	$toreturn['headerwidth'] = '';
	$toreturn['pageurl'] 	 = '';
	
	if ($displaytype == 'updated')
	{
		$toreturn['headertitle'] = array('Updated', 'Line', 'Type', 'Photos', 'Events', 'History', 'Location');
	}
	else if ($displaytype == 'search' OR $displaytype == 'line')
	{
		$toreturn['headertitle'] = array('Line', 'Type', 'Photos', 'Events', 'History', 'Location');
	}
	
	for ($i = 0; $i < $numberOfLocations; $i++)
	{
		$id = stripslashes(MYSQL_RESULT($resultLocations,$i,"l.location_id"));
		
		if ($id == $pastid)
		{
			$i++;
			if ($i == $numberOfLocations)
			{
				break;
			}
			$id = stripslashes(MYSQL_RESULT($resultLocations,$i,"l.location_id"));
		}
		
		$highlightName = $name = stripslashes(MYSQL_RESULT($resultLocations,$i,"name")); 
		$type = stripslashes(MYSQL_RESULT($resultLocations,$i,"type"));
		
		if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT * FROM locations WHERE `name` = '".mysql_real_escape_string($name)."' 
			AND display != 'tracks' AND type != 'TYPE_SIGNAL_BOX'", locationDBconnect())) > '1')
		{
			$base = $id;
		}
		else
		{
			$base = str_replace(' ', '-', strtolower($name));
		}
		
		if ($keyword != '')
		{
			$highlightName = highlight($keyword, $name);
		}
		
		if ($type == 'TYPE_SIGNAL_BOX')
		{
			$base .= '/box';
		}
		else
		{
			$base .= '';
		}
		
		// image depending on length of description
		$thisLength = '<a href="/location/'.$base.'">'.getLocationDescriptionLengthImage(MYSQL_RESULT($resultLocations,$i,"description")).'</a>';
			
		// image if photos
		if (MYSQL_RESULT($resultLocations,$i,"photos") != '0')
		{
			$thisPhoto = '<a href="/location/'.$base.'/#photos"><img src="/images/photos.gif" alt="Photos" title="Photos" /></a>';
		}
		else
		{
			$thisPhoto = '';
		}
		
		// image if events
		if (MYSQL_RESULT($resultLocations,$i,"events") == '1')
		{
			$thisEvent = '<a href="/location/'.$base.'/#events"><img src="/images/events.gif" alt="Events" title="Events" /></a>';
		}
		else
		{
			$thisEvent = '';
		}
		
		$lineid = MYSQL_RESULT($resultLocations,$i,"lr.line_id"); 
		$line = stripslashes(MYSQL_RESULT($resultLocations,$i,"r.name")); 
		$locationTypeid = MYSQL_RESULT($resultLocations,$i,"type"); 
		$locationTypeName = stripslashes(MYSQL_RESULT($resultLocations,$i,"lt.basic"));
			
		if ($displaytype == 'search' OR $displaytype == 'line')
		{
			$toreturn[] = array($line, $locationTypeName, $thisPhoto, $thisEvent, $thisLength, $highlightName, "/location/$base");
		}
		else if ($displaytype == 'updated')
		{
			$updated = MYSQL_RESULT($resultLocations,$j,"fdate"); 
			$toreturn[] = array($updated, $line, $locationTypeName, $thisPhoto, $thisEvent, $thisLength, $highlightName, "/location/$base");
		}
		
		$j++;
		$pastid = $id;
	}
	
	return $toreturn;
}	// end function


function getAssociatedLocations($id, $name, $type)
{
	$splitName = split(' ', $name);
	$splitNameLength = sizeof($splitName);
	$nameLength = strlen($name);
	
	// ignore if the name is empty
	if ($name == '')
	{
		return false;
	}
	// names where there are multiple words
	if ($splitNameLength > 1)
	{
		// check for locations where a single lettter is at the end
		if (substr($name, $nameLength-2, 1) == ' ')
		{
			$name = substr($name, 0, $nameLength-2);
		}
		// check for A, B, C, etc
		else if (strlen($splitName[$splitNameLength-1]) == 1)
		{
			$name = $splitName[0];
		}
		// check for various other tpyes of interesting location
		else
		{
			$interestingNames = array('block point', 'junction', 'loop');
			
			foreach ($interestingNames as $interestingName)
			{
				$newName = str_ireplace(' '.$interestingName, '', strtolower($name));
				
				// if the location name has matched an interesting one
				if ($newName != strtolower($name))
				{
					$name = $newName;
					break;
				}
			}
		}
	}
	// if there is only a single word
	else if (sizeof($splitName[0]) == 1)
	{
		$name = $splitName[0];
	}
	else
	{
		return false;
	}
	
	// find possible interesting locations
	$associatedLocationsSQL = "SELECT location_id AS id, name, type FROM locations l
		WHERE name LIKE ('".$name."%') AND location_id != '".$id."' AND ".SQL_NEXTABLE." ORDER BY km_old DESC";
	$associatedLocationsResults = MYSQL_QUERY($associatedLocationsSQL, locationDBconnect());
	$associateLocationCount = MYSQL_NUM_ROWS($associatedLocationsResults);
	
	if ($associateLocationCount < 1)
	{
		return false;
	}
	else
	{
		for ($i = 0; $i < $associateLocationCount; $i++)
		{
			$location = mysql_fetch_assoc($associatedLocationsResults);
			// only want to show stations and signal boxes
			switch ($location['type'])
			{
				case TYPE_SIGNAL_BOX:
				case TYPE_STATION:
				case TYPE_RMSP:
				case TYPE_YARD:
				case TYPE_JUNCTION:
				case TYPE_CROSSING_LOOP:
				case TYPE_BLOCK_POINT:
					$title = getLocationName($location['name'], $location['type']);
					break;
				default:
					$title = '';
			}
			
			if ($title != '')
			{
				$toreturn[] = array($location['id'], $title);
			}
		}
	}
	
	return $toreturn;
	
	/*
	echo '<br><br>';
	print_r($toreturn);
	*/
}	// end function
?>