<?php 
include_once("../common/dbConnection.php");
include_once("../common/location-functions.php");
include_once("../common/location-database-functions.php");
include_once("../common/source-functions.php");
include_once("../common/formatting-functions.php");

$locationName = str_replace('-', ' ', $_REQUEST['name']);
$locationBox = str_replace('-', ' ', $_REQUEST['box']);
$locationId = str_replace('-', ' ', $_REQUEST['id']);
$lineLink = $_REQUEST['line'];

// reset bad input data
if (!is_numeric($locationId) AND $locationId != '')
{
	$locationName = $locationId;
	$locationId = '';
}

// fix for auto mod_rewrite stuff for numeric IDs
if (is_numeric($locationName) AND $locationId == '')
{
	$locationId = $locationName;
	$locationName = '';
}

// show specific location - when lots of info given
if($locationBox != "" OR $locationName != "" OR $locationId != "")
{
	$location = getLocation($locationName, $locationBox, $locationId, $lineLink);
	
	if (!$location['error'])
	{
		drawLocation($location);
	}
	else if ($location['error'] == 'duplicates')
	{
		drawDuplicateLocation($location['duplicates']);
	}
	else if ($location['error'] == 'empty')
	{
		drawInvalidLocation($location['locationToFind']);
	}
}
?>