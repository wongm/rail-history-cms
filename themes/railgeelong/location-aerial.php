<?php
include_once("common/aerial-functions.php");
include_once("common/formatting-functions.php");
$id = $_REQUEST["id"];
$section = $_REQUEST["section"];
$preset = $_REQUEST["preset"];
$center = $_REQUEST["center"];
$zoom = $_REQUEST["zoom"];
$view = $_REQUEST["view"];
$lines = $_REQUEST["lines"];
$types = $_REQUEST["types"];

if ($id != '')
{
	drawSpecific($view, $id);
}
elseif ($section == 'overview')
{
	drawAllMap($center, $zoom, $types, $lines);
}
?>