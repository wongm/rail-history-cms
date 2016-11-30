<?php

function getLocationsTable($lineId, $lineName, $typeSql, $typeName, $sort)
{	
	$sqlSpecific = "";
	
	// depends on what class of diagram we want - line
	if ($lineId != '')
	{
		$sqlSpecific = sprintf(" ( lr.line_id = %s ) AND ", ($lineId));
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
			$sqlorder = ' ORDER BY linename ASC, l.name ASC ';
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
	
	$sql = "SELECT l.location_id, l.name, l.link, r.name AS linename, r.link AS linelink, l.photos, 
		l.events, kmaccuracy, km, l.type, basic, r.line_id, length(l.description) AS description_length
		FROM locations l
		INNER JOIN location_types lt ON l.type = lt.type_id 
		INNER JOIN locations_raillines lr ON lr.location_id = l.location_id
		INNER JOIN raillines r ON r.line_id=lr.line_id
		LEFT OUTER JOIN locations ol ON l.name = ol.name 
		WHERE ".$sqlSpecific." l.name != '' AND l.display != 'tracks' AND r.todisplay != 'hide' 
		GROUP BY location_id ".$sqlorder;
	$result = query_full_array($sql);
	$result = query_full_array($sql);
	
	$numberOfLocations = sizeof($result);
	$j = 0;
	
	if ($numberOfLocations > 0)
	{		
		// the header sort text
		$toreturn['sorttext'] = $sortText;
		// base page URL for sort links
		$toreturn['pageurl'] = $pageUrl;
		// the header cell titles
		$toreturn['headertitle'] = array('Name', $headerTitle, 'Distance', 'Photos', 'Events', 'History');
		// the header cell width
		$toreturn['headerstyle'] = array('', '', ' width="100"', ' width="50"', ' width="50"', ' width="50"');
		// the header cell URLs
		$toreturn['headerurl'] = array('by-name', $headerUrl, 'by-km', 'by-photos', 'by-events', 'by-history');
	}
	
	$pastid = -1;
	for ($i  = 0; $i < $numberOfLocations; $i++)
	{	
		$locationId = stripslashes($result[$i]["location_id"]);
		$locationLink = stripslashes($result[$i]["link"]);
		
		if ($locationId == $pastid)
		{
			$i++;
			if ($i == $numberOfLocations)
			{
				break;
			}
			
			$locationId = stripslashes($result[$i]["location_id"]);
			$locationLink = stripslashes($result[$i]["link"]);
		}
		
		$locationName = stripslashes($result[$i]["name"]);
		$lineName = stripslashes($result[$i]["linename"]);
		$lineLink = stripslashes($result[$i]["linelink"]);
		$thisPhoto = stripslashes($result[$i]["photos"]);
		$thisEvent = stripslashes($result[$i]["events"]);
		$kmAccuracy = stripslashes($result[$i]["kmaccuracy"]);
		$km = stripslashes($result[$i]["km"]);
		$locationType = stripslashes($result[$i]["type"]);
		$locationTypeName = stripslashes($result[$i]["basic"]);
		$thisLength = getLocationDescriptionLengthImage($result[$i]["description_length"]);
		
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
			$lineId = stripslashes($result[$i]["line_id"]);
		}
		else
		{
			$lineId = '';
		}
		
		$base = getLocationUrlBase($locationId, $locationName, $locationLink);
		
		//grabs the URL for locations
		switch ($locationType) 
		{
			case TYPE_JUNCTION:	
				$locationUrl = "/location/$base/$lineLink/";
				break;
			case TYPE_STATION:
			case TYPE_RMSP:
			case TYPE_SIGNAL_BOX:
			case TYPE_INDUSTRY:
			case TYPE_YARD:
			case TYPE_CROSSING_LOOP:
				$locationUrl = "/location/$base/";
				break;
			default:
				$locationUrl = '';
		}
		
		// display locations with big description, or photos, or events
		// fallback setting of URL
		if (($thisPhoto != '0' OR $thisEvent == 1 OR $thisLength != '') AND $locationUrl == '')
		{
			$locationUrl = '/location/'.$base.'/';
		}
		
		// only show ones with URL set
		if ($locationUrl != '')
		{
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
			
			$toreturn[] = array($thisCommon, $thisKm, $thisPhoto, $thisEvent, $thisLength, $locationName, $locationUrl);
			$j++;
			$pastid = $locationId;
			
		}	// end $thisUrl if
	}		// end while

	return $toreturn;	
}	//end function

function getLineguideDistanceURL($trackSubpageDistances, $currentKm)
{
	$diagramPageBounds = explode(';',$trackSubpageDistances);
	$i = 1;
	
	foreach ($diagramPageBounds as $pageBound)
	{
		$pageBound = explode('-',$pageBound);
		
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
?>