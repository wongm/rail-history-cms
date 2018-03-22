<?php
// force UTF-8 Ø
	error_reporting(E_ALL - E_NOTICE);
require_once(dirname(dirname(dirname(__FILE__))).'/zp-core/global-definitions.php');
require_once(dirname(dirname(dirname(__FILE__)))."/".ZENFOLDER . "/template-functions.php");

require_once("common/definitions.php");

$time = strtotime($_GET['date']);
$today = date('Y-m-d', $time);
$day = date('j', $time);
$month = date('n', $time);

$sql = "SELECT DAY(date), MONTH(date), YEAR(date), event_id, DATE_FORMAT(date, '".SHORT_DATE_FORMAT."') AS fdate, date AS plaindate, RE.tracks AS tracks, 
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
		WHERE (( safeworking_why != 'singled' 
		AND (safeworking_why = 'opened') ) OR ( ((RE.tracks > '1' AND safeworking_why != 'opened') 
		OR safeworking_why = 'singled' ) ) OR ( safeworking != '' ) OR ( RE.tracks = '0' ) 
		OR ( gauge != 'BG' ) OR ( RE.description != '' AND start_location = '' AND end_location = '' ))  
		AND RE.display != 'hide'
		AND DAY(date) = '" . $day . "' AND MONTH(date) = '" . $month . "'
		UNION
		SELECT DAY(open), MONTH(open), YEAR(open), l.location_id AS event_id, DATE_FORMAT(l.open, '".SHORT_DATE_FORMAT."') AS fdate, l.open AS plaindate, 'opened' AS tracks, 
		'-', '-', '-', '-', 
		'-', '-', l.open AS date, line_id AS line, '', 
		'-', '-', basic, '-', '-', '-', l.openAccuracy AS dateAccuracy, 
		'-', '-', '-', l.name AS location_name
		FROM locations l
		INNER JOIN locations_raillines lr ON lr.location_id = l.location_id 
		INNER JOIN location_types lt ON type = type_id 
		WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") 
		AND name != '' AND open != '".DATE_UNKNOWN_OPEN."' AND open != '".DATE_UNKNOWN_CLOSE."' 
		AND DAY(open) = '" . $day . "' AND MONTH(open) = '" . $month . "'
		UNION
		SELECT DAY(close), MONTH(close), YEAR(close), l.location_id AS event_id, DATE_FORMAT(l.close, '".SHORT_DATE_FORMAT."')  AS fdate, l.close AS plaindate, 'closed' AS tracks, 
		'-', '-', '-', '-', 
		'-', '-', l.close, line_id AS line, '', 
		'-', '-', basic, '-', '-', '-', l.closeAccuracy AS dateAccuracy, 
		'-', '-', '-', l.name AS location_name 
		FROM locations l
		INNER JOIN locations_raillines lr ON lr.location_id = l.location_id 
		INNER JOIN location_types lt ON type = type_id 
		WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") 
		AND name != '' AND close != '".DATE_NULL."' AND close != '".DATE_UNKNOWN_CLOSE."' 
		AND DAY(close) = '" . $day . "' AND MONTH(close) = '" . $month . "'
		ORDER BY plaindate ASC";
		
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
	
	echo "<h1>$result results for $today</h1>";

?>
<pre>
<?php print_r($result); ?>
</pre>