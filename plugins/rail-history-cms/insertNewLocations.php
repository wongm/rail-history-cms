<?php
include_once("common/dbConnection.php");

// Retreiving Form Elements from Form
$thisName = addslashes($_REQUEST['thisNameField']);
$thisSuburb = addslashes($_REQUEST['thisSuburbField']);
$thisLine = addslashes($_REQUEST['thisLineField']);
$thisTracks = addslashes($_REQUEST['thisTracksField']);
$thisType = addslashes($_REQUEST['thisTypeField']);
$thisImage = addslashes($_REQUEST['thisImageField']);
$thisLx_type = addslashes($_REQUEST['thisLx_typeField']);
$thisUrl = addslashes($_REQUEST['thisUrlField']);
$thisDisplay = addslashes($_REQUEST['thisDisplayField']);
$thisStatus = addslashes($_REQUEST['thisStatusField']);
$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
$thisCredits = addslashes($_REQUEST['thisCreditsField']);
$thisOpen = addslashes($_REQUEST['thisOpenField']);
$thisClose = addslashes($_REQUEST['thisCloseField']);

if ($thisOpen == '')
{
	$thisOpen = '0001-01-01';
}

if ($thisClose == '')
{
	$thisClose = '9999-01-01';
}

$thisOpenAccuracy = addslashes($_REQUEST['thisOpenAccuracyField']);
$thisCloseAccuracy = addslashes($_REQUEST['thisCloseAccuracyField']);
$thisLong = addslashes($_REQUEST['thisLongField']);
$thisLat = addslashes($_REQUEST['thisLatField']);
$thisKm = addslashes($_REQUEST['thisKmField']);
$thisKmaccuracy = addslashes($_REQUEST['thisKmAccuracyField']);
$thisEvents = addslashes($_REQUEST['thisEventsField']);
$thisPhotos = addslashes($_REQUEST['thisPhotosField']);

if ($thisPhotos == '')
{
	$thisPhotos = 0;
}

$thisModified = date('Y-m-d H:i:s');

$sqlQuery = "INSERT INTO locations (name , suburb , line_old , tracks , type , image , lx_type , url , 
									display , status , description , credits , open , openAccuracy , 
									close , closeAccuracy , `long` , lat , km_old , kmaccuracy_old , 
									events , photos , added, modified) 
			VALUES ('$thisName' , '$thisSuburb' , '$thisLine' , '$thisTracks' , '$thisType' , '$thisImage' , '$thisLx_type' , '$thisUrl' , '$thisDisplay' , '$thisStatus' , '$thisDescription' , '$thisCredits' , '$thisOpen' , '$thisOpenAccuracy' , '$thisClose' , '$thisCloseAccuracy' , '$thisLong' , '$thisLat' , '$thisKm' , '$thisKmaccuracy' , '$thisEvents' , '$thisPhotos' , '$thisModified' , '$thisModified' )";
$result = query_full_array($sqlQuery);

// get location ID for next query
$thisLocationId = query_full_array("SELECT location_id FROM locations WHERE name = '$thisName' AND km_old = '$thisKm'")[0]['location_id'];

$sqlQuery = "INSERT INTO locations_raillines (line_id, location_id, km, kmaccuracy) 
			VALUES ('$thisLine' , '$thisLocationId', '$thisKm' , '$thisKmaccuracy')";
$result = query_full_array($sqlQuery);

if (strlen($_POST['submitAndEdit']))
{
	Header("Location: /backend/editLocations.php?location=" . $thisLocationId);
}
else if (strlen($_POST['submitAndNew']))
{
	Header("Location: ".$_SERVER['HTTP_REFERER']);
}

include_once("common/header.php");
?>
<table width="100%">
<tr><td>
	<h2><a href="/backend/editLocations.php?location=<?php echo $thisLocationId?>">Return to editing!</a></h2><BR />
	<a href="<?php echo $_SERVER['HTTP_REFERER']?>">Add another location</a><BR />
	<a href="/location/<?php echo $thisLocationId?>">View location</a>
</td></tr>
</table>
<?php
include_once("common/footer.php");
?>