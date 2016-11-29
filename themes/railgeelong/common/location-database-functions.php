<?php
require_once("dbConnection.php");

/*
 *	returns an array of data for a location
 */
function getLocation($locationToFind, $boxToFind, $idToFind, $requestedLineLink)
{
	$toFind = '';
	$pastId = -1;
	
	// setup the SQL to search by
	if ($boxToFind != '')
	{
		$locationToFind = $boxToFind;
		$toFind = "l.name = ".db_quote($boxToFind)." AND type = '".TYPE_SIGNAL_BOX."' AND ";
	}
	else
	{
		if ($idToFind != '')
		{
			if (is_numeric($idToFind))
			{
				$toFind = "l.location_id = ".$idToFind." AND ";
			}
			else
			{
				$toFind .= "l.link = ".db_quote($idToFind)." AND ";
			}
		}
		else if ($locationToFind != '')
		{
			$toFind .= "l.link = ".db_quote($locationToFind)." AND ";
		}
	}
	
	// check for duplicates from DB
	$locationSql = "SELECT lt.*, lr.*, l.* , lr.kmAccuracy, r.todisplay, r.name AS linename, r.link, r.line_id, r.tracksubpage, 
		DATE_FORMAT(open, '".SHORT_DATE_FORMAT."') AS fopen, DATE_FORMAT(close, '".SHORT_DATE_FORMAT."') 
		AS fclose, DATE_FORMAT(l.modified, '".SHORT_DATE_FORMAT."') AS fmodified,
		length(l.description) AS description_length
		FROM locations l
		INNER JOIN locations_raillines lr ON l.location_id = lr.location_id
		INNER JOIN raillines r ON r.line_id = lr.line_id
		INNER JOIN location_types lt  ON lt.type_id = l.type 
		WHERE ".$toFind." display != 'tracks' AND r.todisplay != 'hide' 
		ORDER BY l.location_id ASC, lr.junctiontype, r.order ASC";
	$locationResults = query_full_array($locationSql);
	$duplicateCount = sizeof($locationResults);
	$locationIndex = -1;
	
	// validate that we don't have the same location twice
	for ($i = 0; $i < $duplicateCount; $i++)
	{
		$id = $locationResults[$i]['location_id'];
		
		if ($id == $pastId)
		{
			$duplicateCount--;
		}
		$pastId = $id;
	}
	
	// no locations found!?
	if ($duplicateCount != 1)
	{
		$location['error'] = 'empty';
		$location['locationToFind'] = $locationToFind;
		return $location;
	}
	else
	{
		$location["result"] = $locationResults;
		
		// set output status
		$location["error"] = false;
		
		// page header stuff
		$location["name"] = stripslashes($locationResults[0]["name"]);
		$location["type"] = $locationResults[0]["type"];
		$location["pageTitle"] = getLocationName($location["name"], $location["type"]);
		
		// collect data into an array
		$location["id"] = $locationResults[0]["location_id"];
		$location["locationLink"] = $locationResults[0]["link"];
		$location["uniqueId"] = $location["id"];
		$location["display"] = $locationResults[0]["display"];
		$location["url"] = $locationResults[0]["url"];
		$location["description"] = stripslashes($locationResults[0]["description"]);
		$location["credits"] = stripslashes($locationResults[0]["credits"]);
		$location["image"] = $locationResults[0]["image"];
		$location["diagrams"] = $locationResults[0]["diagrams"];
		$location["typeToDisplay"] = $locationResults[0]["basic"];
		$location["approxOpen"] = $locationResults[0]["openAccuracy"];
		$location["approxClose"] = $locationResults[0]["closeAccuracy"];
		$location["stillOpen"] = $locationResults[0]["close"] > date('Y-m-d');
		$location["openPlain"] = $locationResults[0]["open"];
		$location["closePlain"] = $locationResults[0]["close"];
		$location["coords"] = $locationResults[0]["long"];
		$location["photos"] = $locationResults[0]["photos"];
		$location["open"] = formatDate($locationResults[0]["fopen"], $location["approxOpen"]);
		$location["close"] = formatDate($locationResults[0]["fclose"], $location["approxClose"]);
		//$location["todisplay"] = $locationResults[0]["r.todisplay");
		$location["raillineDisplay"] = $locationResults[0]["todisplay"];
		$location["showLineEvents"] = substr($location["raillineDisplay"], 2, 1) == 1;
		$location["updated"] = $locationResults[0]["fmodified"];
		
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
		$location["associatedLocations"] = getAssociatedLocations($location["id"], $location["name"], $location["type"], $location["locationLink"]);
		$location["hasAssociatedLocations"] = (bool)$location["associatedLocations"];
		
		// setup distances
		$location["kmAccuracy"] = $locationResults[0]["kmAccuracy"];
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
		$location["numberoflines"] = $numberoflines = sizeof($locationResults);
		$location["km"] = $locationResults[0]["km"];
		$location["lineName"] = $locationResults[0]["linename"];
		$location["lineLink"] = $locationResults[0]["link"];
		$location["lineId"] = $locationResults[0]["line_id"];
		$location['lineType'] = $locationResults[0]["junctiontype"];
		$location['trackSubpage'] = $locationResults[0]["tracksubpage"];
		$location["trackSubpageCount"] = sizeof(explode(';', $location["trackSubpage"]));
		
		// next and backward locations
		$location["nextLocation"] = getNeighbourLocation($location, 'next');
		$location["backLocation"] = getNeighbourLocation($location, 'back');
		
		// loop through alll lines a location is on, stored them in 'branchlines' array
		if ($numberoflines > 1)
		{
			for ($i = 0; $i < $numberoflines; $i++)
			{
				// add check for locations like South Geelong, a junction with a signal box by same name
				if ($location["id"] == $locationResults[$i]["location_id"])
				{
					$branchline[$i]['link'] = $locationResults[$i]["link"];
					$branchline[$i]['name'] = $locationResults[$i]["linename"];
					$branchline[$i]['lineId'] = $locationResults[$i]["line_id"];
					$branchline[$i]['km'] = $locationResults[$i]["km"];
					$branchline[$i]['type'] = $locationResults[$i]["junctiontype"];
				}
				else
				{
					$location["numberoflines"]--;
				}
			}
			$location['branchlines'] = $branchline;
		}
		
		return $location;
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
		$neighbourSqlLimit = "lr.km < ".db_quote($location["km"])." 
		GROUP BY location_id
		ORDER BY lr.km DESC
		LIMIT 0, 1";
	}
	else
	{
		$neighbourSqlLimit = "lr.km > ".db_quote($location["km"])." 
		GROUP BY location_id
		ORDER BY lr.km ASC
		LIMIT 0, 1";
	}
	
	$neighbourSql = "SELECT l.name, l.link, l.type, l.location_id 
		FROM locations l
		INNER JOIN locations_raillines lr ON l.location_id = lr.location_id 
		INNER JOIN raillines r ON lr.line_id = r.line_id 
		LEFT OUTER JOIN locations ol ON l.name = ol.name 
		WHERE (r.link = ".db_quote($lineLink).") 
		AND ".SQL_NEXTABLE." AND ".$neighbourSqlLimit;
	
	$neighbourResult = query_full_array($neighbourSql);
	$neighbourLocationCount = sizeof($neighbourResult);
	
	if ($neighbourLocationCount == 1)	
	{
		$name = stripslashes($neighbourResult[0]["name"]);
		$locationId = stripslashes($neighbourResult[0]["location_id"]);
		$type = stripslashes($neighbourResult[0]["type"]);
		$link = $neighbourResult[0]["link"];
		$urlBase = getLocationUrlBase($locationId, $name, $link);
		$name = getLocationName($name, $type);
		
		// for junctions
		if ($type == TYPE_JUNCTION)
		{
			$urlBase .= '/'.$lineLink;
		}
		
		if ($way == 'back')
		{
			return '<a class="prev" href="/location/'.$urlBase.'/" alt="Previous Location" title="Previous Location" >&laquo; '.$name.'</a>'; 
		}
		else
		{
			return '<a class="next" href="/location/'.$urlBase.'/" alt="Next Location" title="Next Location">'.$name.' &raquo;</a>'; 
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
		$diagrams = explode(';', $diagrams);
		$numberOfYears = sizeof($diagrams);
		$added = 'full-';
	}
	else
	{
		$sql = "SELECT * FROM location_years WHERE `location` = ".db_quote($id)." ORDER BY year ASC";
		$result = query_full_array($sql);
		$numberOfYears = sizeof($result);
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
				$year = $result[$i]["year"];
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
	$numberOfLocations = sizeof($resultLocations);
	$pastid = -1;
	
	$toreturn['sorttext'] 	 = '';
	$toreturn['headerurl'] 	 = '';
	$toreturn['headerwidth'] = '';
	$toreturn['pageurl'] 	 = '';
	
	if ($displaytype == 'updated')
	{
		$toreturn['headertitle'] = array('Updated', 'Line', 'Type', 'Photos', 'Events', 'History', 'Location');
	}
	else if ($displaytype == 'search')
	{
		$toreturn['headertitle'] = array('Location', 'Type', 'Photos', 'Events', 'History', 'Line');
	}
	
	for ($i = 0; $i < $numberOfLocations; $i++)
	{
		$id = stripslashes($resultLocations[$i]["location_id"]);
		if ($id == $pastid)
		{
			$i++;
			if ($i == $numberOfLocations)
			{
				break;
			}
			$id = stripslashes($resultLocations[$i]["location_id"]);
		}
		
		$highlightedLocationName = $name = stripslashes($resultLocations[$i]["name"]); 		
		$type = stripslashes($resultLocations[$i]["type"]);
		$link = $resultLocations[$i]["link"];
		$urlBase = getLocationUrlBase($id, $name, $link);
		
		if ($keyword != '')
		{
			$highlightedLocationName = highlight($keyword, $name);
		}
		
		// image depending on length of description
		//$contentLengthImage = getLocationDescriptionLengthImage($resultLocations[$i]["description_length"]);
		$contentLengthImage = "<a href=\"/location/$urlBase/\">$contentLengthImage</a>";
			
		// image if photos
		if (showPhotos($resultLocations[$i]["photos"]))
		{
			$galleryLinkImage = '<a href="/location/'.$urlBase.'/#photos"><img src="/images/photos.gif" alt="Photos" title="Photos" /></a>';
		}
		else
		{
			$galleryLinkImage = '';
		}
		
		// image if events
		if ($resultLocations[$i]["events"] == '1')
		{
			$eventLinkImage = '<a href="/location/'.$urlBase.'/#events"><img src="/images/events.gif" alt="Events" title="Events" /></a>';
		}
		else
		{
			$eventLinkImage = '';
		}
		
		$lineid = $resultLocations[$i]["line_id"]; 
		$linename = stripslashes($resultLocations[$i]["linename"]); 
		$locationTypeid = $resultLocations[$i]["type"]; 
		$locationTypeName = stripslashes($resultLocations[$i]["basic"]);
			
		if ($displaytype == 'search')
		{
			$toreturn[] = array($locationTypeName, $galleryLinkImage, $eventLinkImage, $contentLengthImage, $linename, $highlightedLocationName, "/location/$urlBase/");
		}
		else if ($displaytype == 'updated')
		{
			$updated = $resultLocations[$j]["fdate"]; 
			$toreturn[] = array($updated, $linename, $locationTypeName, $galleryLinkImage, $eventLinkImage, $contentLengthImage, $highlightedLocationName, "/location/$urlBase/");
		}
		
		$j++;
		$pastid = $id;
	}
	
	return $toreturn;
}	// end function


function getAssociatedLocations($id, $name, $type, $link)
{
	$splitName = explode(' ', $name);
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
	$associatedLocationsSQL = "SELECT l.location_id AS id, l.name, l.link AS link, l.type 
		FROM locations l
		LEFT OUTER JOIN locations ol ON l.name = ol.name 
		WHERE l.name LIKE (".db_quote($name . '%').") 
		AND l.location_id != '".$id."' AND ".SQL_NEXTABLE." 
		GROUP BY l.location_id
		ORDER BY l.km_old DESC";
	$associatedLocationsResults = query_full_array($associatedLocationsSQL);
	$associatedLocationCount = sizeof($associatedLocationsResults);
	
	if ($associatedLocationCount < 1)
	{
		return false;
	}
	else
	{
		$toreturn = array();
		
		for ($i = 0; $i < $associatedLocationCount; $i++)
		{
			$bit = '';
			
			// only want to show stations and signal boxes
			switch ($type)
			{
				case TYPE_SIGNAL_BOX:
				case TYPE_STATION:
				case TYPE_RMSP:
				case TYPE_YARD:
				case TYPE_JUNCTION:
				case TYPE_INDUSTRY:
				case TYPE_CROSSING_LOOP:
				case TYPE_BLOCK_POINT:
					$title = getLocationName($associatedLocationsResults[$i]['name'], $associatedLocationsResults[$i]['type']);
					break;
				default:
					$title = '';
			}
			
			if ($title != '')
			{
				if (strlen($link))
				{
					$toreturn[] = array($associatedLocationsResults[$i]['link'], $title);
				}
				else
				{
					$toreturn[] = array($associatedLocationsResults[$i]['id'], $title);
				}
			}
		}
		
		return $toreturn;
	}
	
	return false;
	
	/*
	echo '<br><br>';
	print_r($toreturn);
	*/
}	// end function
?>