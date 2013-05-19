<?php
include_once(dirname(__FILE__) . "/../common/dbConnection.php");

/*
 *	returns an array of data for a location
 */
function getLocation($locationToFind, $boxToFind, $idToFind, $requestedLineLink)
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
	
	//if ($linelink != '')
	{
		// removed because it resulted in branch lines not being described if line is given in query string
		//$toFind .= "r.link = '".mysql_real_escape_string($linelink)."' AND ";
	}
	
	// check for duplicates from DB
	$locationSql = "SELECT lt.*, lr.*, l.* , r.todisplay, r.name, r.link, r.line_id, r.tracksubpage, 
		DATE_FORMAT(open, '".SHORT_DATE_FORMAT."') AS fopen, DATE_FORMAT(close, '".SHORT_DATE_FORMAT."') 
		AS fclose, DATE_FORMAT(l.modified, '".SHORT_DATE_FORMAT."') AS fmodified, '' AS unique_name,
		length(l.description) AS description_length
		FROM locations l
		INNER JOIN locations_raillines lr ON l.location_id = lr.location_id
		INNER JOIN raillines r ON r.line_id = lr.line_id
		INNER JOIN location_types lt  ON lt.type_id = l.type 
		WHERE ".$toFind." display != 'tracks' AND r.todisplay != 'hide' 
		ORDER BY l.location_id ASC, lr.junctiontype, r.order ASC";
	$locationResults = MYSQL_QUERY($locationSql, locationDBconnect());
	$duplicateCount = MYSQL_NUM_ROWS($locationResults);
	$locationIndex = -1;
	
	//print_r(mysql_fetch_assoc($locationResults));
	
	for ($i = 0; $i < $duplicateCount; $i++)
	{
		$id = MYSQL_RESULT($locationResults,$i,'location_id');
		
		if ($id == $pastId)
		{
			$duplicateCount--;
		}
		$pastId = $id;
	}
	
	// no locations found!?
	if ($duplicateCount == 0)
	{
		$location['error'] = 'empty';
		$location['locationToFind'] = $locationToFind;
		return $location;
	}
	else if ($duplicateCount == 1)
	{
		$locationIndex = 0;
	}
	// found two locations by that name, probably signal box and associated station
	elseif ($duplicateCount == 2)
	{
		$typeA = MYSQL_RESULT($locationResults,0,'type');
		$typeB = MYSQL_RESULT($locationResults,1,'type');
		
		$idA = MYSQL_RESULT($locationResults,0,'location_id');
		$idB = MYSQL_RESULT($locationResults,1,'location_id');
		
		if ($typeA == TYPE_SIGNAL_BOX AND $typeB = TYPE_STATION AND $idA != $idB AND $boxToFind != '')
		{
			$locationIndex = 1;
			$duplicateCount = 1;
		}
		else if ($typeA == TYPE_STATION AND $typeB = TYPE_SIGNAL_BOX AND $idA != $idB AND $boxToFind == '')
		{
			$locationIndex = 0;
			$duplicateCount = 1;
		}
		else if ($idA == $idB)
		{
			$locationIndex = 0;
			$duplicateCount = 1;
		}
		else
		{
			$location['error'] = 'duplicates';
			$location['duplicates'] = $locationResults;
			return $location;
		}
			
	}
	else if ($duplicateCount > 1)
	{		
		$location['error'] = 'duplicates';
		$location['duplicates'] = $locationResults;
		return $location;
	}
	
	// if no duplicates - spit it out!
	if ($locationIndex >= 0)
	{
		$location["result"] = $locationResults;// = MYSQL_QUERY($sql, locationDBconnect());
		
		// set output status
		$location["error"] = false;
		
		// page header stuff
		$location["name"] = stripslashes(MYSQL_RESULT($locationResults,0,"name"));
		$location["type"] = MYSQL_RESULT($locationResults,0,"type");
		$location["pageTitle"] = getLocationName($location["name"], $location["type"]);
		
		// collect data into an array
		$location["id"] = MYSQL_RESULT($locationResults,0,"location_id");
		$location["locationLink"] = convertToLink($location["name"]);
		$location["uniqueId"] = $location["id"];
		$location["display"] = MYSQL_RESULT($locationResults,0,"display");
		$location["url"] = MYSQL_RESULT($locationResults,0,"url");
		$location["description"] = stripslashes(MYSQL_RESULT($locationResults,0,"description"));
		$location["credits"] = stripslashes(MYSQL_RESULT($locationResults,0,"l.credits"));
		$location["image"] = MYSQL_RESULT($locationResults,0,"image");
		$location["diagrams"] = MYSQL_RESULT($locationResults,0,"diagrams");
		$location["typeToDisplay"] = MYSQL_RESULT($locationResults,0,"lt.basic");
		$location["approxOpen"] = MYSQL_RESULT($locationResults,0,"openAccuracy");
		$location["approxClose"] = MYSQL_RESULT($locationResults,0,"closeAccuracy");
		$location["stillOpen"] = MYSQL_RESULT($locationResults,0,"close") > date('Y-m-d');
		$location["openPlain"] = MYSQL_RESULT($locationResults,0,"open");
		$location["closePlain"] = MYSQL_RESULT($locationResults,0,"close");
		$location["coords"] = MYSQL_RESULT($locationResults,0,"long");
		$location["photos"] = MYSQL_RESULT($locationResults,0,"photos");
		$location["open"] = formatDate(MYSQL_RESULT($locationResults,0,"fopen"), $approxOpen);
		$location["close"] = formatDate(MYSQL_RESULT($locationResults,0,"fclose"), $approxClose);
		//$location["todisplay"] = MYSQL_RESULT($locationResults,0,"r.todisplay");
		$location["raillineDisplay"] = MYSQL_RESULT($locationResults,0,"r.todisplay");
		$location["showLineEvents"] = substr($location["raillineDisplay"], 2, 1) == 1;
		$location["updated"] = MYSQL_RESULT($locationResults,0,"fmodified");
		
		// get aerial and map image URLs
		$location["showAerial"] = false;
		$location["1945AerialUrl"] = '/images/aerial/1945/'.$location["uniqueId"].'.jpg';
		$location["morgansUrl"] = '/images/aerial/morgans/'.$location["uniqueId"].'.jpg';
		
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$location["1945AerialUrl"]))
		{
			$location["showAerial"] = true;
		}
		else
		{
			$location["1945AerialUrl"] = '';
		}
		
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$location["morgansUrl"]))
		{
			$location["showAerial"] = true;
		}
		else
		{
			$location["morgansUrl"] = '';
		}
		
		// get stations and signal boxes with the same name as this one
		$location["associatedLocations"] = getAssociatedLocations($location["id"], $location["name"], $location["type"]);
		$location["hasAssociatedLocations"] = (bool)$location["associatedLocations"];
		
		// setup distances
		$location["kmAccuracy"] = MYSQL_RESULT($locationResults,'0',"lr.kmAccuracy");
		if ($location["kmAccuracy"] == 'exact')
		{
			$location["exactKm"] =  true;
			$location["hideKm"] = false;
		}
		else if ($location["kmAccuracy"] == 'approx')
		{
			$location["exactKm"] =  false;
			$location["hideKm"] = false;
		}
		else
		{
			$location["exactKm"] =  false;
			$location["hideKm"] = true;
		}
		
		// booleans to work out for a location
		$location["hasSignalBox"] = $hasSignalBox;
		$location["isCrossing"] = typeIsCrossing($location["type"]);
		$location["isStation"] = typeIsStation($location["type"]);
		$location["isSignalBox"] = ($location["type"] == TYPE_SIGNAL_BOX);
		$location["isYard"] = ($location["type"] == TYPE_YARD);
		
		// determine if diagrams are to be shown
		if ($location['diagrams'] != '0' AND $location['diagrams'] != '')
		{
			$location['showDiagrams'] = 'full';
		}
		else if ($location['display'] != 'none' AND $location['display'] != 'map' 
			AND !$location['isCrossing'] AND !$location["isYard"])
		{
			$location['showDiagrams'] = 'all';
		}
		else
		{
			$location['showDiagrams'] = '';
		}
		
		// for locations on multiple lines
		$location["requestedLineLink"] = $requestedLineLink;
		$location["numberoflines"] = $numberoflines = MYSQL_NUM_ROWS($locationResults);
		$location["km"] = MYSQL_RESULT($locationResults,0,"lr.km");
		$location["lineName"] = MYSQL_RESULT($locationResults,0,"r.name");
		$location["lineLink"] = MYSQL_RESULT($locationResults,0,"r.link");
		$location["lineId"] = MYSQL_RESULT($locationResults,0,"r.line_id");
		$location['lineType'] = MYSQL_RESULT($locationResults,0,"lr.junctiontype");
		$location['trackSubpage'] = MYSQL_RESULT($locationResults,0,"r.tracksubpage");
		$location["trackSubpageCount"] = sizeof(split(';', $location["trackSubpage"]));
		
		// next and backward locations
		$location["nextLocation"] = getNeighbourLocation($location, 'next');
		$location["backLocation"] = getNeighbourLocation($location, 'back');
		
		// loop through alll lines a location is on, stored them in 'branchlines' array
		if ($numberoflines > 1)
		{
			for ($i = 0; $i < $numberoflines; $i++)
			{
				// add check for locations like South Geelong, a junction with a signal box by same name
				if ($location["id"] == MYSQL_RESULT($locationResults,$i,"l.location_id"))
				{
					$branchline[$i]['link'] = MYSQL_RESULT($locationResults,$i,"r.link");
					$branchline[$i]['name'] = MYSQL_RESULT($locationResults,$i,"r.name");
					$branchline[$i]['lineId'] = MYSQL_RESULT($locationResults,$i,"r.line_id");
					$branchline[$i]['km'] = MYSQL_RESULT($locationResults,$i,"lr.km");
					$branchline[$i]['type'] = MYSQL_RESULT($locationResults,$i,"lr.junctiontype");
				}
				else
				{
					$location["numberoflines"]--;
				}
			}
			$location['branchlines'] = $branchline;
		}
		
		return 	$location;
	}	// end zero result if
}		// end function


/*
 * gets the next location on a line
 * forwards or backwards
 */
function getNeighbourLocation($location, $way)
{
	if ($location["requestedLineLink"] != '')
	{		
		$lineLink = str_replace('lines', 'foolines', $location["requestedLineLink"]);
		$lineLink = str_replace('-line', '', $lineLink);
		$lineLink = str_replace('foolines', 'lines', $lineLink);
	}
	else
	{
		$lineLink = $location["lineLink"];
	}
	
	if ($way == 'back')
	{
		$neighbourSqlLimit = "lr.km < '".mysql_real_escape_string($location["km"])."' 
		GROUP BY location_id
		ORDER BY lr.km DESC
		LIMIT 0, 1";
	}
	else
	{
		$neighbourSqlLimit = "lr.km > '".mysql_real_escape_string($location["km"])."' 
		GROUP BY location_id
		ORDER BY lr.km ASC
		LIMIT 0, 1";
	}
	
	$neighbourSql = "SELECT count(l.location_id) AS unique_name, l.name, l.type, l.location_id FROM locations l
		INNER JOIN locations_raillines lr ON l.location_id = lr.location_id 
		INNER JOIN raillines r ON lr.line_id = r.line_id 
		LEFT OUTER JOIN locations ol ON l.name = ol.name 
		WHERE (r.link = '".mysql_real_escape_string($lineLink)."') 
		AND ".SQL_NEXTABLE." AND ".$neighbourSqlLimit;
		// SLOOOOOOOOW query right down, so removed, 0.2 seconds vs 2 seconds
		///AND ol.type != '".TYPE_SIGNAL_BOX."' 
	
	$neighbourResult = MYSQL_QUERY($neighbourSql, locationDBconnect());
	$neighbourLocationCount = MYSQL_NUM_ROWS($neighbourResult);
	
	if ($neighbourLocationCount == 1)	
	{
		$name = stripslashes(MYSQL_RESULT($neighbourResult,0,"name"));
		$locationId = stripslashes(MYSQL_RESULT($neighbourResult,0,"location_id"));
		$type = stripslashes(MYSQL_RESULT($neighbourResult,0,"type"));
		$uniqueName = (MYSQL_RESULT($neighbourResult,0,"unique_name") == 1);
		$urlBase = getLocationUrlBase($locationId, $name, $uniqueName);
		$name = getLocationName($name, $type);
		
		// for junctions
		if ($type == TYPE_JUNCTION OR !$uniqueName)
		{
			$urlBase .= '/'.$lineLink;
		}
		// for signal boxes
		else if ($type == TYPE_SIGNAL_BOX)
		{
			$urlBase .= '/box';
		}
		
		if ($way == 'back')
		{
			return '<a class="prev" href="/location/'.$urlBase.'" alt="Previous Location" title="Previous Location" >&laquo; '.$name.'</a>'; 
		}
		else
		{
			return '<a class="next" href="/location/'.$urlBase.'" alt="Next Location" title="Next Location">'.$name.' &raquo;</a>'; 
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
		$image = convertToLink($image);
	}
	
	// only if more than one diagram
	if ($numberOfYears >= 1)
	{
		// check initial image exists - if so then add it
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/t/$image-$added"."open.gif"))
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
			
			$diagramData[] = array($image.'-'.$added.$year,$name.' '.$year,$year);
		}
	}
	// if there is only ever one image
	elseif ($numberOfYears == 1 AND file_exists($_SERVER['DOCUMENT_ROOT'].'/t/'.$image.'-'.$added.$diagrams[0].'.gif')) 
	{
		$diagramData[] = array($image.'-'.$added.$diagrams[0], $name.' '.$diagrams[0], $diagrams[0]);
	}
	// if there is only ever one image for a 'full' version
	elseif (file_exists($_SERVER['DOCUMENT_ROOT']."/t/$image.gif"))
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
		
		$highlightName = $name = stripslashes(MYSQL_RESULT($resultLocations,$i,"l.name")); 
		$type = stripslashes(MYSQL_RESULT($resultLocations,$i,"l.type"));
		$uniqueName = (MYSQL_RESULT($resultLocations,$i,"unique_name") == 1);
		$urlBase = getLocationUrlBase($id, $name, $uniqueName);
		
		if ($keyword != '')
		{
			$highlightName = highlight($keyword, $name);
		}
		
		if ($type == TYPE_SIGNAL_BOX)
		{
			$urlBase .= '/box';
		}
		
		// image depending on length of description
		$contentLengthImage = getLocationDescriptionLengthImage(MYSQL_RESULT($resultLocations,$i,"description_length"));
		$contentLengthImage = "<a href=\"/location/$urlBase\">$contentLengthImage</a>";
			
		// image if photos
		if (showPhotos(MYSQL_RESULT($resultLocations,$i,"l.photos")))
		{
			$galleryLinkImage = '<a href="/location/'.$urlBase.'/#photos"><img src="/images/photos.gif" alt="Photos" title="Photos" /></a>';
		}
		else
		{
			$galleryLinkImage = '';
		}
		
		// image if events
		if (MYSQL_RESULT($resultLocations,$i,"l.events") == '1')
		{
			$eventLinkImage = '<a href="/location/'.$urlBase.'/#events"><img src="/images/events.gif" alt="Events" title="Events" /></a>';
		}
		else
		{
			$eventLinkImage = '';
		}
		
		$lineid = MYSQL_RESULT($resultLocations,$i,"lr.line_id"); 
		$line = stripslashes(MYSQL_RESULT($resultLocations,$i,"r.name")); 
		$locationTypeid = MYSQL_RESULT($resultLocations,$i,"l.type"); 
		$locationTypeName = stripslashes(MYSQL_RESULT($resultLocations,$i,"lt.basic"));
			
		if ($displaytype == 'search' OR $displaytype == 'line')
		{
			$toreturn[] = array($line, $locationTypeName, $galleryLinkImage, $eventLinkImage, $contentLengthImage, $highlightName, "/location/$urlBase");
		}
		else if ($displaytype == 'updated')
		{
			$updated = MYSQL_RESULT($resultLocations,$j,"l.fdate"); 
			$toreturn[] = array($updated, $line, $locationTypeName, $galleryLinkImage, $eventLinkImage, $contentLengthImage, $highlightName, "/location/$urlBase");
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
		// check for various other types of interesting location
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
	$associatedLocationsSQL = "SELECT count(l.location_id) AS unique_name, l.location_id AS id, l.name, l.type 
		FROM locations l
		LEFT OUTER JOIN locations ol ON l.name = ol.name 
		WHERE l.name LIKE ('".mysql_real_escape_string($name)."%') 
		AND l.location_id != '".$id."' AND ".SQL_NEXTABLE." 
		GROUP BY l.location_id
		ORDER BY l.km_old DESC";
	$associatedLocationsResults = MYSQL_QUERY($associatedLocationsSQL, locationDBconnect());
	$associatedLocationCount = MYSQL_NUM_ROWS($associatedLocationsResults);
	
	if ($associatedLocationCount < 1)
	{
		return false;
	}
	else
	{
		for ($i = 0; $i < $associatedLocationCount; $i++)
		{
			$location = mysql_fetch_assoc($associatedLocationsResults);
			$bit = '';
			
			// only want to show stations and signal boxes
			switch ($location['type'])
			{
				case TYPE_SIGNAL_BOX:
					$bit = "/box";
				case TYPE_STATION:
				case TYPE_RMSP:
				case TYPE_YARD:
				case TYPE_JUNCTION:
				case TYPE_INDUSTRY:
				case TYPE_CROSSING_LOOP:
				case TYPE_BLOCK_POINT:
					$title = getLocationName($location['name'], $location['type']);
					break;
				default:
					$title = '';
			}
			
			if ($title != '')
			{
				if ($location['unique_name'] == 1)
				{
					$toreturn[] = array(convertToLink($location['name']).$bit, $title);
				}
				else
				{
					$toreturn[] = array($location['id'], $title);
				}
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