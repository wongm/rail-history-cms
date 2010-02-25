<?php

function getLocationsTable($lineId, $lineName, $typeSql, $typeName, $sort)
{
	// depends on what class of diagram we want - line
	if ($lineId != '')
	{
		$sqlSpecific = sprintf(" ( lr.line_id = %s ) AND ", mysql_real_escape_string($lineId));
		$pageUrl = "/lineguide/$lineName/locations";
		$headerCell = '<th><a href="'.$pageUrl.'/by-type">Type</a></th>';
		$headerWidth = '';
		$headerTitle = 'Type';
		$headerUrl = 'by-type';
		$sqlorder = ' ORDER BY lr.km ASC';
		$sortorder = 'distance from Melbourne. Click table headers to reorder';
	}
	//  type
	if ($typeSql != '')
	{
		$sqlSpecific = $sqlSpecific.$typeSql." AND ";
		$pageUrl = "/locations/$typeName";
		$headerCell = '<th width="150"><a href="'.$pageUrl.'/by-line">Line</a></th>';
		$headerWidth = 150;
		$headerTitle = 'Line';
		$headerUrl = 'by-line';
		$sqlorder = ' ORDER BY l.name ASC';
		$sortorder = 'name. Click table headers to reorder';
	}
	
	// for sorting
	switch ($sort) 
	{
		case 'name':
			$sqlorder = ' ORDER BY l.name ASC ';
			$sortorder = 'name';
			break;
		case 'history':
			$sqlorder = ' ORDER BY length(l.description) DESC';
			$sortorder = 'location history detail';
			break;
		case 'events':
			$sqlorder = ' ORDER BY l.events DESC, length(l.description) DESC, l.name ASC ';
			$sortorder = 'locations with events listed';
			break;
		case 'photos':
			$sqlorder = ' ORDER BY l.photos DESC, l.name ASC  ';
			$sortorder = 'locations with photos';
			break;
		case 'type':
			$sqlorder = ' ORDER BY basic ASC, l.name ASC ';
			$sortorder = 'location type';
			break;
		case 'line':
			$sqlorder = ' ORDER BY r.name ASC, l.name ASC ';
			$sortorder = 'location line';
			break;
		case 'km':
			$sqlorder = ' ORDER BY lr.km ASC';
			$sortorder = 'distance from Melbourne';
			break;
	}
	
	if ($sortorder!= '')
	{
		$sortText = '<p>Ordered by '.$sortorder.'.</p>';
		//echo $sortText ;
	}
	
	$sql = "SELECT count(l.location_id) AS unique_name, l.location_id, l.name, r.name, r.link, l.photos, 
		l.events, kmaccuracy, km, l.type, basic, r.line_id, length(l.description) AS description_length
		FROM locations l
		INNER JOIN location_types lt ON l.type = lt.type_id 
		INNER JOIN locations_raillines lr ON lr.location_id = l.location_id
		INNER JOIN raillines r ON r.line_id=lr.line_id
		LEFT OUTER JOIN locations ol ON l.name = ol.name 
		WHERE ".$sqlSpecific." l.name != '' AND l.display != 'tracks' AND r.todisplay != 'hide' 
		GROUP BY location_id ".$sqlorder;
	$result = MYSQL_QUERY($sql, locationDBconnect());
	$numberOfLocations = MYSQL_NUM_ROWS($result);
	$j = 0;
	
	if ($numberOfLocations > 0)
	{		
		// the header sort text
		$toreturn['sorttext'] = $sortText;
		// base page URL for sort links
		$toreturn['pageurl'] = $pageUrl;
		// the header cell titles
		$toreturn['headertitle'] = array($headerTitle, 'Photos', 'Events', 'History', 'Distance', 'Name');
		// the header cell width
		$toreturn['headerstyle'] = array(' width="'.$headerWidth.'"', ' width="50"', ' width="50"', ' width="50"', ' width="100"', '');
		// the header cell URLs
		$toreturn['headerurl'] = array($headerUrl, 'by-photos', 'by-events', 'by-history', 'by-km', 'by-name');
	}
	
	for ($i  = 0; $i < $numberOfLocations; $i++)
	{
		$id = stripslashes(MYSQL_RESULT($result,$i,"l.location_id"));
		$uniqueName = (MYSQL_RESULT($result,$i,"unique_name") == 1);
		
		if ($id == $pastid)
		{
			$i++;
			if ($i == $numberOfLocations)
			{
				break;
			}
			$id = stripslashes(MYSQL_RESULT($result,$i,"l.location_id"));
			$uniqueName = (MYSQL_RESULT($result,$i,"unique_name") == 1);
		}
		
		$locationName = stripslashes(MYSQL_RESULT($result,$i,"l.name"));
		$lineName = stripslashes(MYSQL_RESULT($result,$i,"r.name"));
		$lineLink = stripslashes(MYSQL_RESULT($result,$i,"r.link"));
		$thisPhoto = stripslashes(MYSQL_RESULT($result,$i,"photos"));
		$thisEvent = stripslashes(MYSQL_RESULT($result,$i,"events"));
		$kmAccuracy = stripslashes(MYSQL_RESULT($result,$i,"kmaccuracy"));
		$km = stripslashes(MYSQL_RESULT($result,$i,"km"));
		$locationType = stripslashes(MYSQL_RESULT($result,$i,"type"));
		$locationTypeName = stripslashes(MYSQL_RESULT($result,$i,"basic"));
		$thisLength = getLocationDescriptionLengthImage(MYSQL_RESULT($result,$i,"description_length"));
		
		if ($locationType != TYPE_MISC)
		{
			$thisKm = formatDistance($km, $kmAccuracy);
		}
		else
		{
			$thisKm = '';
		}
		
		// fix for the link to locations on more than one line
		if($numberOfLocations > 1)
		{
			$lineId = stripslashes(MYSQL_RESULT($result,$i,"r.line_id"));
		}
		else
		{
			$lineId = '';
		}
		
		$base = getLocationUrlBase($id, $locationName, $uniqueName);
		
		//grabs the URL for locations
		switch ($locationType) 
		{
			case TYPE_JUNCTION:	
				$locationUrl = "/location/$base/$lineLink";
				break;
			case TYPE_STATION:
			case TYPE_RMSP:
			case TYPE_SIGNAL_BOX:
			case TYPE_INDUSTRY:
			case TYPE_YARD:
			case TYPE_CROSSING_LOOP:
				$locationUrl = "/location/$base";
				break;
			default:
				$locationUrl = '';
		}
		
		// display locations with big description, or photos, or events
		// fallback setting of URL
		if ($thisPhoto != '0' OR $thisEvent == 1 OR $thisLength != '' AND $locationUrl == '')
		{
			$locationUrl = '/location/'.$base;
		}
		
		// only show ones with URL set
		if ($locationUrl != '')
		{
			if ($j%2 == '0')
			{
				$style = 'class="x"';
			}
			else
			{
				$style = 'class="y"';
			}
			// image if events
			if ($thisEvent == 1)
			{
				$thisEvent = '<a href="'.$locationUrl.'#events"><img src="/images/events.gif" alt="Event Listings" title="Event Listings" /></a>';
				if ($thisLength == '')
				{
					// adds a rank if there are just events
					$thisLength = '<img src="/images/rank1.gif" alt="Basic" title="Basic" /></a>';
				}
			}
			else
			{
				$thisEvent = '';
			}
			
			// image depending on length of description
			$thisLength = '<a href="'.$locationUrl.'">'.$thisLength.'</a>';
			// image if photos
			if ($thisPhoto != '0' AND $thisPhoto != '')
			{
				$thisPhoto = '<a href="'.$locationUrl.'#photos"><img src="/images/photos.gif" alt="Photos" title="Photos" /></a>';
			}
			else
			{
				$thisPhoto = '';
			}
			// depends on what type of diagram being drawn - type or line
			if ($typeSql != '')
			{
				$thisCommon = $lineName;
			}
			else
			{
				$thisCommon = $locationTypeName;
			}
			
			$toreturn[] = array($thisCommon, $thisPhoto, $thisEvent, $thisLength, $thisKm, $locationName, $locationUrl);
			$j++;
			$pastid = $id;
			
		}	// end $thisUrl if
	}	/*	end while	*/ 

	return $toreturn;	
}	//end function

function getLineguideDistanceURL($trackSubpageDistances, $currentKm)
{
	$diagramPageBounds = split(';',$trackSubpageDistances);
	$i = 1;
	
	foreach ($diagramPageBounds as $pageBound)
	{
		$pageBound = split('-',$pageBound);
		
		if ($currentKm < $pageBound[1])
		{
			$extraPageBounds = "/page-$i";
			break;
		}
		else
		{
			$i++;
		}
	}
				
	return $extraPageBounds;
}

/*
function getLocationUrlForLineguide($id, $thisKm, $thisName, $thisLine, $thisUrl, $thisType, $thisEvent, $thisPhoto, $thisDescription, $extra)
{
	$base = str_replace(' ', '-', strtolower($thisName));
	
	echo "SELECT * FROM locations l, locations_raillines lr 
		WHERE `name` = '".mysql_real_escape_string($thisName)."' AND `display` != 'tracks' 
		AND l.location_id = lr.location_id AND lr.km != '".$thisKm."' 
		AND (`type` != '".TYPE_YARD."' AND `type` != '".TYPE_SIGNAL_BOX."')<br><br>";
	
	echo "SELECT * FROM locations l, locations_raillines lr 
		WHERE `name` = '".mysql_real_escape_string($thisName)."' AND `display` != 'tracks' 
		AND l.location_id = lr.location_id AND lr.km != '".$thisKm."' 
		AND (`type` != '".TYPE_YARD."' AND `type` != '".TYPE_SIGNAL_BOX."')<hr>";
	
	if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT * FROM locations l, locations_raillines lr 
		WHERE `name` = '".mysql_real_escape_string($thisName)."' AND `display` != 'tracks' 
		AND l.location_id = lr.location_id AND lr.km != '".$thisKm."' 
		AND (`type` != '".TYPE_YARD."' AND `type` != '".TYPE_SIGNAL_BOX."')", locationDBconnect())) > '1')
	{
		$base = $id;
	}
	if ($thisType == TYPE_JUNCTION)
	{
		$base .= '/'.$thisLine;
	}
	else if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT * FROM locations 
		WHERE `name` = '".mysql_real_escape_string($thisName)."' AND `display` != 'tracks' 
		AND (`type` != '".TYPE_YARD."' AND `type` != '".TYPE_SIGNAL_BOX."')", locationDBconnect())) > '1')
	{
		$base .= '/'.$thisLine;
	}
	
	// special setup for junctions
	if ($thisType == TYPE_JUNCTION AND $extra != '')
	{
		$linkResultSQL = sprintf("SELECT * FROM raillines WHERE line_id = '%s'", mysql_real_escape_string($thisUrl));
		$linkResult = MYSQL_QUERY($linkResultSQL, locationDBconnect());
		// for the next / forward links
		if ($extra == 'plain')
		{
			$thisUrl = '/location/'.$thisName.'/'.$thisLine;
		}
		// for the normal lineguide links
		elseif (MYSQL_NUM_ROWS($linkResult) != '0')
		{
			$ourLine = MYSQL_RESULT($linkResult,0,"link");
			$otherLineSQL = sprintf("SELECT * FROM raillines WHERE line_id = '%s'", mysql_real_escape_string($thisLine));
			$otherLine = MYSQL_RESULT(MYSQL_QUERY($otherLineSQL, locationDBconnect()),0,"link");
			$thisJunction = $ourLine;	// fixes up the images later down the track
			
			if ($thisUrl != $thisLine)
			{
				$thisUrl = '/lineguide/'.$ourLine.'/diagram#km'.$thisKm.'"><i>[Change Lines]</i></a><br/><a href="/location/'.$thisName.'/'.$thisLine;
			}
			else
			{
				$thisUrl = '/lineguide/'.$otherLine.'/diagram#km'.$thisKm.'"><i>[Change Lines]</i></a><br/><a href="/location/'.$thisName.'/'.$thisUrl;
			}
		}
		else
		{
			$thisJunction = '';
			$thisUrl = '/lineguide/'.$thisUrl.'/diagram"><i>[Change Lines]</i></a><br/><a href="/location/'.$base;
		}
	}
	else
	{
		$thisJunction = '';
		if ($thisEvent == '1' OR $thisPhoto == '1' OR $thisDescription != '')
		{
			$thisUrl = '/location/'.$base;
		}
		
		//specific actions for certain types of location
		switch ($thisType) 
		{
			case TYPE_SIGNAL_BOX:	//signal box
				$thisUrl = '/location/'.$base.'/box';
				break;
			case TYPE_STATION:
			case TYPE_RMSP:
			case TYPE_JUNCTION:
			case TYPE_INDUSTRY:
			case TYPE_YARD:
			case TYPE_CROSSING_LOOP:
				$thisUrl = '/location/'.$base;
				break;
			case TYPE_TIMING_LOOP: 	//timing loop
				$thisUrl = '/articles/timingloops';
				break;
			default:
				break;
		}
	}
	return $thisUrl;
}
*/



/*
function isUniqueLocationName($name)
{
	$uniqueLocationNameSQL = sprintf("SELECT * FROM locations WHERE `name` = '%s' AND `display` != 'tracks' 
		AND `type` != '".TYPE_YARD."' AND `type` != '".TYPE_SIGNAL_BOX."'", 
		mysql_real_escape_string($name));
	return MYSQL_NUM_ROWS(MYSQL_QUERY($uniqueLocationNameSQL, locationDBconnect())) == 1;
}
*/
?>