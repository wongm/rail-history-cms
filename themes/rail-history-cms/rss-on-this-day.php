<?php
// force UTF-8 Ø
require_once(dirname(dirname(dirname(__FILE__))).'/zp-core/global-definitions.php');
require_once(dirname(dirname(dirname(__FILE__)))."/".ZENFOLDER . "/template-functions.php");

require_once("common/definitions.php");
require_once("common/formatting-functions.php");

$host = htmlentities($_SERVER["HTTP_HOST"], ENT_QUOTES, 'UTF-8');
$protocol = SERVER_PROTOCOL;
$baseUrl = $protocol . '://' . $host;

header('Content-Type: application/xml');
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title>Rail Geelong - On this day</title>
<link>http://www.railgeelong.com</link>
<atom:link href="<?php echo $baseUrl; ?>/page/rss-updates" rel="self" type="application/rss+xml" />
<description>A guide to historical events on the railways of Geelong and district</description>
<language>en-AU</language>
<pubDate><?php echo date("r", time()); ?></pubDate>
<lastBuildDate><?php echo date("r", time()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>Rail Geelong RSS Generator</generator>
<?php

if (isset($_GET['date'])) {
	$time = strtotime($_GET['date']);
} else {
	$time = time();
}

$count = 0;
while ($count < 10)
{
	$added = runQuery($time, $baseUrl);
	$time = strtotime("-1 day", $time);
	if ($added) {
		$count++;
	}
}

function runQuery($time, $baseUrl)
{
	$queryDateFormatted = date('Y-m-d', $time);
	$day = date('j', $time);
	$month = date('n', $time);

	$filter = "";
	// show if admin when page is in edit mode
	if ( !zp_loggedin() ) {
		$filter = " AND r.todisplay != 'hide'";
	}

	$sql = "SELECT YEAR(open) AS year, l.location_id AS event_id, DATE_FORMAT(l.open, '".SHORT_DATE_FORMAT."') AS fdate, openAccuracy AS approx, DATE_FORMAT(l.close, '".SHORT_DATE_FORMAT."') AS fdatealt, closeAccuracy AS approxAlt, l.open AS plaindate, 'opened' AS tracks, 
			'-', '-', '-', '-', 
			'-', '-', l.open AS date, r.name AS line, '', 
			'-', '-', l.type, '-', '-', '-', l.openAccuracy AS dateAccuracy, 
			'-', '-', '-', l.name AS location_name, l.link
			FROM locations l
			INNER JOIN locations_raillines lr ON lr.location_id = l.location_id 
			INNER JOIN raillines r ON lr.line_id = r.line_id 
			INNER JOIN location_types lt ON type = type_id 
			WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") 
			AND l.name != '' AND open != '".DATE_UNKNOWN_OPEN."' AND open != '".DATE_UNKNOWN_CLOSE."' 
			AND DAY(open) = '" . $day . "' AND MONTH(open) = '" . $month . "'
			AND l.openAccuracy = 'exact' " . $filter . "
			UNION
			SELECT YEAR(close) AS year, l.location_id AS event_id, DATE_FORMAT(l.close, '".SHORT_DATE_FORMAT."')  AS fdate, closeAccuracy AS approx, DATE_FORMAT(l.open, '".SHORT_DATE_FORMAT."') AS fdatealt, openAccuracy AS approxAlt, l.close AS plaindate, 'closed' AS tracks, 
			'-', '-', '-', '-', 
			'-', '-', l.close, r.name AS line, '', 
			'-', '-', l.type, '-', '-', '-', l.closeAccuracy AS dateAccuracy, 
			'-', '-', '-', l.name AS location_name, l.link
			FROM locations l
			INNER JOIN locations_raillines lr ON lr.location_id = l.location_id 
			INNER JOIN raillines r ON lr.line_id = r.line_id 
			INNER JOIN location_types lt ON type = type_id 
			WHERE display != 'tracks' AND (".IMPORTANT_LOCATION.") 
			AND l.name != '' AND close != '".DATE_NULL."' AND close != '".DATE_UNKNOWN_CLOSE."' 
			AND DAY(close) = '" . $day . "' AND MONTH(close) = '" . $month . "'
			AND l.closeAccuracy = 'exact' " . $filter . "
			ORDER BY plaindate ASC";
			
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
	
	if (sizeof($result) > 0)
	{
		$currentYear = date('Y', $time);
		$pastYear = $result[0]['year'];
		$fdate = $result[0]['fdate'];
		$fdatealt = $result[0]['fdatealt'];
		$fdateFormatted = formatDate($result[0]['fdate'], $result[0]['approx']);
		$fdatealtFormatted = formatDate($result[0]['fdatealt'], $result[0]['approxAlt']);
		$location_name = getLocationName($result[0]['location_name'], $result[0]['type']);
		$link = $result[0]['link'];
		$line = $result[0]['line'];
		$action = $result[0]['tracks'];
		$location_id = $result[0]['event_id'];
		$yearsAgo = $currentYear - $pastYear;
		$yearPlural = ($yearsAgo == 1) ? "" : "s";

		$oppositeMessage = "";
		if ($fdatealt != 'January 1, 9999' && $fdatealt != 'January 1, 0001') {
			$opposite = ($action == 'opened') ? 'closed' : 'opened';
			$oppositeMessage = ". It $opposite on $fdatealtFormatted";
		}
		
		$root = "$location_name $action on the $line line";
		$title = "On this day $yearsAgo year$yearPlural ago, $fdateFormatted: $root";
		$description = "On this day $yearsAgo year$yearPlural ago: $root on $fdateFormatted$oppositeMessage.";
		$urlText = "$baseUrl/location/$link";
?>
<item>
	<title><?php echo $title; ?></title>
	<link><![CDATA[<?php echo $urlText; ?>]]></link>
	<description><![CDATA[<?php echo $description; ?>]]></description>
	<guid><![CDATA[<?php echo $queryDateFormatted; ?>]]></guid>
	<pubDate><?php echo $queryDateFormatted; ?></pubDate>
</item>
<?php 
	}	
	
	return (sizeof($result) > 0);
}
?>
</channel>
</rss>