<?php
require_once("common/aerial-functions.php");
require_once("common/formatting-functions.php");
$id = $_REQUEST["id"];
$view = $_REQUEST["view"];
	
drawSpecific($view, $id);
?>