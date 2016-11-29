<?php require_once("dbConnection.php");

function parseToXML($htmlStr) 
{ 
	$xmlStr=str_replace('<','&lt;',$htmlStr); 
	$xmlStr=str_replace('>','&gt;',$xmlStr); 
	$xmlStr=str_replace('"','&quot;',$xmlStr); 
	$xmlStr=str_replace("'",'&#39;',$xmlStr); 
	$xmlStr=str_replace("&",'&amp;',$xmlStr); 
	return $xmlStr; 
} 

// filter based on lineguide
$lineguide = $_REQUEST["lineguide"];

if ($lineguide != '')
{
	$lineguideSql = ' AND line_id = '.$lineguide;
}
else
{
	$lineguideSql = '';
}

// filter based on lines or locations
$types = $_REQUEST["types"];
$lines = $_REQUEST["lines"];

if ($lines != '')
{
	$linesarray = split(',', $lines);
	$lines = '';
	
	foreach ($linesarray as $entry)
	{
		$lines .= $entry.',';
	}
	
	$lines = str_replace(',,', '', $lines.',');
	$lineSelectSql = " AND lr.line_id IN ( $lines ) ";
}

if ($types != '')
{
	$typesarray = split(',', $types);
	$types = $numbers = '';
	
	// get the types of locations we want
	foreach ($typesarray as $entry)
	{
		switch ($entry)
		{
			case 's':	// stations
				$numbers .= '15,37,';
				$types .= "s,";
				break;	
			case 'i':	// industry
				$numbers .= '30,';
				$types .= "i,";
				break;	
			case 'b':	//	signal boxes
				$numbers .= '29,';
				$types .= "b,";
				break;	
			case 'r':	//	roads
				$numbers .= '1,2,3,4,5,6,7,8,9,10,11,12,13,14,';
				$types .= "r,";
				break;	
			case 'm':	//	misc
				$numbers .= '27,31,33,34,36,';
				$types .= "m,";
				break;
			default:
		}	// end switch
	}
	
	$types = str_replace(',,', '', $types.',');
	$numbers = str_replace(',,', '', $numbers.',');
	
	$locationTypeSql = " AND l.type IN ($numbers) ";
}

$sqlQuery = "SELECT * FROM locations l, location_types lt, locations_raillines lr 
WHERE lt.type_id = l.type AND l.location_id = lr.location_id
AND name != '' AND `long` != '' AND `long` != '0' AND display != 'tracks' ".
$lineguideSql.$locationTypeSql.$lineSelectSql.
" ORDER BY lr.km ASC";
$result = MYSQL_QUERY($sqlQuery, locationDBconnect());

// Start XML file, echo parent node
header("Content-type: text/xml");
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result))
{
	$coords = split (', ', $row['long']); 
	
	if (sizeOf($coords) == 2)
	{			
		// ADD TO XML DOCUMENT NODE
		echo '<marker ';
		echo 'name="' . parseToXML($row['name']) . '" ';
		echo 'photos="' . parseToXML($row['photos']) . '" ';
		echo 'events="' . $row['events'] . '" ';
		echo 'length="' . $row['length'] . '" ';	
		echo 'lat="' . $coords[0] . '" ';
		echo 'lng="' . $coords[1] . '" ';
		echo 'type="' . parseToXML($row['basic']) . '" ';
		echo '/>';
	}
}

// End XML file
echo '</markers>';

?>