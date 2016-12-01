<?php 
require_once("common/definitions.php");
require_once("common/location-functions.php");
require_once("common/formatting-functions.php");

/*
 * show a listing of certain type of location
 */
if (isset($_REQUEST['type']))
{
	require_once("locations-type.php");
}
/*
 * find a location by name
 */
else if (isset($_REQUEST['search']))
{	
	require_once("locations-search.php");
}
/*
 * a default opening info page
 */
else
{	
	require_once("locations-home.php");
}
?>