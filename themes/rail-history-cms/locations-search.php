<?php
$locationSearch = $_REQUEST['search'];
$locationSearchPage = '';	

if (isset($_REQUEST['page'])) {
	$locationSearchPage = $_REQUEST['page'];
}

require_once("common/location-database-functions.php");

$pageTitle = "Location search - \"$locationSearch\"";
require_once("common/header.php");
drawLocationSearch($locationSearch, 1, $locationSearchPage);
require_once("common/footer.php");
?>