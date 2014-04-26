<? include_once("common/dbConnection.php");
//PHP SCRIPT: getlocations.php
Header("content-type: application/x-javascript");	

$requestedLinesToDisplay = $_REQUEST["lines"];
$requestedTypesToDisplay = $_REQUEST["types"];

// parse params for values
if (strlen($requestedLinesToDisplay) > 0 && explode(',', $requestedLinesToDisplay) > 0)
{
	$linesToDisplay = explode(',', $requestedLinesToDisplay);
}
else if (is_numeric($requestedLinesToDisplay))
{
	$linesToDisplay = $requestedLinesToDisplay;
}
if (strlen($requestedTypesToDisplay) > 0 && explode(',', $requestedTypesToDisplay) > 0)
{
	$typesToDisplay = explode(',', $requestedTypesToDisplay);
}
else if (is_numeric($requestedTypesToDisplay))
{
	$typesToDisplay = $requestedTypesToDisplay;
}

// build up query
$sqlQuery = "SELECT * FROM locations l
				INNER JOIN location_types lt ON lt.type_id = l.type 
				INNER JOIN locations_raillines lr ON l.location_id = lr.location_id
				WHERE name != '' AND `long` != '' AND `long` != '0' AND display != 'tracks'";
			
// add required filters
if (sizeof($linesToDisplay) > 0)
{
	$sqlQuery .= " AND lr.line_id IN (" . implode(',', $linesToDisplay) . ")";
}

if (sizeof($typesToDisplay) > 0)
{
	$sqlQuery .= " AND l.type IN (" . implode(',', $typesToDisplay) . ")";
}

$result = MYSQL_QUERY($sqlQuery, locationDBconnect());
$numberOfRows = MYSQL_NUM_ROWS($result);

$locationArray = array();

if ($numberOfRows > 0) 
{	
	for($i = 0; $i < $numberOfRows; $i++)
	{
		$coords = split(',', stripslashes(MYSQL_RESULT($result,$i,"long"))); 
		
		if (sizeOf($coords) == 2)
		{
			$location['id'] = stripslashes(MYSQL_RESULT($result,$i,"l.location_id")); 
			$location['name'] = stripslashes(MYSQL_RESULT($result,$i,"name"));
			$location['lat'] = trim($coords[0]);
			$location['long'] = trim($coords[1]);
			$location['photos'] = stripslashes(MYSQL_RESULT($result,$i,"photos"));
			$location['events'] = stripslashes(MYSQL_RESULT($result,$i,"events"));
			$location['length'] = strlen((MYSQL_RESULT($result,$i,"description")));
			$location['type']['name'] = stripslashes(MYSQL_RESULT($result,$i,"lt.basic"));
			$location['line'] = stripslashes(MYSQL_RESULT($result,$i,"line_id")); 
			
			$t = stripslashes(MYSQL_RESULT($result,$i,"type"));
			
			if($t == 1 || $t == 2 || $t == 3 || $t == 4 || $t == 5 || $t == 6 || $t == 7 || $t == 8 || $t == 9 || $t == 10 || $t == 11 || $t == 12 || $t == 13 || $t == 14) {
				$location['type']['id'] = "r";
			}
			else if($t == 15 || $t == 37) {
				$location['type']['id'] = "s";
			}
			else if($t == 30) {
				$location['type']['id'] = "i";
			}
			else if( $t == 29) {
				$location['type']['id'] = "b";
			}
			else if( $t == 27) {
				$location['type']['id'] = "j";
			}
			else {
				$location['type']['id'] = "m";
			}
			
			$locationArray[] = $location;
		}
	}
}

if (!function_exists('json_encode')) {
    function json_encode($data) {
        switch ($type = gettype($data)) {
            case 'NULL':
                return 'null';
            case 'boolean':
                return ($data ? 'true' : 'false');
            case 'integer':
            case 'double':
            case 'float':
                return $data;
            case 'string':
                return '"' . addslashes($data) . '"';
            case 'object':
                $data = get_object_vars($data);
            case 'array':
                $output_index_count = 0;
                $output_indexed = array();
                $output_associative = array();
                foreach ($data as $key => $value) {
                    $output_indexed[] = json_encode($value);
                    $output_associative[] = json_encode($key) . ':' . json_encode($value);
                    if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                        $output_index_count = NULL;
                    }
                }
                if ($output_index_count !== NULL) {
                    return '[' . implode(',', $output_indexed) . ']';
                } else {
                    return '{' . implode(',', $output_associative) . '}';
                }
            default:
                return ''; // Not supported
        }
    }
}

echo str_replace("\'", "'", json_encode($locationArray));
?>