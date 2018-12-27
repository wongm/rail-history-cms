<?php
require_once("common/map-functions.php");
require_once("common/formatting-functions.php");
$id = $_REQUEST["id"];
$view = $_REQUEST["view"];

drawGoogleMapForSpecificLocation($view, $id);
?>