<?php

$locationName = strtolower(str_replace('\\\'', '\'', str_replace(' ', '-', $_REQUEST['name'])));
$locationBox = strtolower(str_replace('\\\'', '\'', str_replace(' ', '-', $_REQUEST['box'])));
$locationId = strtolower(str_replace('\\\'', '\'', str_replace(' ', '-', $_REQUEST['id'])));
$locationSearch = strtolower($_REQUEST['search']);

// test for numeric location IDs
if (!is_numeric($locationId) AND $locationId != '')
{
	$locationName = $locationId;
	$locationId = '';
	$url = "/location/".$locationName.'/';
}

// fix for auto modrewrite stuff
if (is_numeric($locationName) AND $locationId == '')
{
	$locationId = $locationName;
	$locationName = '';
	$url = "/location/".$locationName.'/';
}

// signal boxes
if ($locationBox != "")
{
	$url = "/location/$locationBox/box/";
}

// named location
if ($locationName != "")
{
	$url = "/location/$locationName/";
}

// types of locations
if ($locationType != "")
{
	$url = "/locations/".$locationType.'/';
}

// last default
if ($url == '')
{
	$url = '/locations/';
}
$url = strtolower(str_replace(' ', '-' , $url));
$url = "http://".$_SERVER['HTTP_HOST'].$url;

header("Location: ".$url,TRUE,301);

?>