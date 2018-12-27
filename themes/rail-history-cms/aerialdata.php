<?php 

require_once("common/definitions.php");

header('Content-Type: application/json');

$lineId = 	$_REQUEST["line"];

if (is_numeric($lineId))
{
	$bit = " AND line_id = $lineId";
}
else
{
	$bit = '';
}

$sqlQuery = "SELECT l.name, l.location_id, basic, l.long, l.photos, l.events, l.description, l.type, line_id
FROM locations l, location_types lt, locations_raillines lr 
WHERE lt.type_id = l.type AND l.location_id = lr.location_id
AND name != '' AND `long` != '' AND `long` != '0' AND display != 'tracks' ".$bit;
$result = query_full_array($sqlQuery);
$numberOfRows = sizeof($result);

if ($numberOfRows > 0) 
{
	for($i = 0; $i < $numberOfRows; $i++)
	{
		$name = stripslashes($result[$i]["name"]);
		$id = stripslashes($result[$i]["location_id"]); 
		$typeName = stripslashes($result[$i]["basic"]);
		$coords = explode (', ', stripslashes($result[$i]["long"])); 
		$photos = stripslashes($result[$i]["photos"]); 
		$events = stripslashes($result[$i]["events"]); 
		$length = strlen(($result[$i]["description"])); 
		$type = stripslashes($result[$i]["type"]); 
		$line = stripslashes($result[$i]["line_id"]); 
		
		if (sizeOf($coords) == 2)
		{
			$infoBox = '<a href="/location/'.$id.'" onclick="o(this.href); return false;" class="infobox" target="_blank" ><h5>'.$name .'</h5><br/>'.$typeName.'<br/>';
			
			if ($photos == 1)
			{
				$infoBox = $infoBox.'<img src="./images/photos.gif" alt="Photos" title="Photos" />';
			}
			if ($events == 1)
			{
				$infoBox = $infoBox.'<img src="./images/events.gif" alt="Events" title="Events" />';
			}
			if ($length > 100)
			{
				$infoBox = $infoBox.'<img src="/images/details.gif" alt="Detailed History" title="Detailed History" />';
			}
			$infoBox = $infoBox.'</a>';
			
			$location = new stdClass;
			$location->name = $name;
			$location->id = intval($id);
			$location->lat = $coords[0];
			$location->lng  = $coords[1];
			$location->type = intval($type);
			$location->line = intval($line);
			$location->infoBox = $infoBox;
			$location->icon = getIcon($type);
			$locations[] = $location;
		}
	}
	
	$data['locations'] = $locations;
	echo json_encode($data);
}	// end if

die();


function getIcon($typeId) {
	$rIcon = '/images/maps/brown_MarkerR.png';
	$sIcon = '/images/maps/orange_MarkerS.png';
	$iIcon = '/images/maps/darkgreen_MarkerI.png';
	$bIcon = '/images/maps/purple_MarkerS.png';
	$mIcon = '/images/maps/red_MarkerM.png';
	$jIcon = '/images/maps/yellow_MarkerJ.png';
	$mapIcon = "/images/maps/mapupdated.gif";
	
	if($typeId == "map") {
		return $mapIcon;
	}
	else if($typeId == 1 || $typeId == 2 || $typeId == 3 || $typeId == 4 || $typeId == 5 || $typeId == 6 || $typeId == 7 || $typeId == 8 || $typeId == 9 || $typeId == 10 || $typeId == 11 || $typeId == 12 || $typeId == 13 || $typeId == 14) {
		return $rIcon;
	}
	else if($typeId == 15 || $typeId == 37) {
		return $sIcon;
	}
	else if($typeId == 30) {
		return $iIcon;
	}
	else if( $$typeId == 29) {
		return $bIcon;
	}
	else if( $typeId == 27) {
		return $jIcon;
	}
	else {
		return $mIcon;
	}
}
?>