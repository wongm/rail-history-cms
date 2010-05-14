<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
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

?>
<?
$thisModified = date('Y-m-d H:i:s');

$sqlQuery = "INSERT INTO locations (name , suburb , line_old , tracks , type , image , lx_type , url , 
									display , status , description , credits , open , openAccuracy , 
									close , closeAccuracy , `long` , lat , km_old , kmaccuracy_old , 
									events , photos , added, modified) 
			VALUES ('$thisName' , '$thisSuburb' , '$thisLine' , '$thisTracks' , '$thisType' , '$thisImage' , '$thisLx_type' , '$thisUrl' , '$thisDisplay' , '$thisStatus' , '$thisDescription' , '$thisCredits' , '$thisOpen' , '$thisOpenAccuracy' , '$thisClose' , '$thisCloseAccuracy' , '$thisLong' , '$thisLat' , '$thisKm' , '$thisKmaccuracy' , '$thisEvents' , '$thisPhotos' , '$thisModified' , '$thisModified' )";
$result = MYSQL_QUERY($sqlQuery);
echo $sqlQuery;
echo "<br>";
echo "<br>";

// get location ID for next query
$locationID = MYSQL_RESULT(MYSQL_QUERY("SELECT location_id FROM locations WHERE name = '$thisName' AND km_old = '$thisKm'"), 0, 'location_id');

$sqlQuery = "INSERT INTO locations_raillines (line_id, location_id, km, kmaccuracy) 
			VALUES ('$thisLine' , '$locationID', '$thisKm' , '$thisKmaccuracy')";
$result = MYSQL_QUERY($sqlQuery);
echo $sqlQuery;
echo "<br>";
echo $result;
echo "<br>";

?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Location_id : </b></td>
	<td><? echo $thisLocation_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><? echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Suburb : </b></td>
	<td><? echo $thisSuburb; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Line : </b></td>
	<td><? echo $thisLine; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Tracks : </b></td>
	<td><? echo $thisTracks; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Type : </b></td>
	<td><? echo $thisType; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Image : </b></td>
	<td><? echo $thisImage; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Lx_type : </b></td>
	<td><? echo $thisLx_type; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Url : </b></td>
	<td><? echo $thisUrl; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Display : </b></td>
	<td><? echo $thisDisplay; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Status : </b></td>
	<td><? echo $thisStatus; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Description : </b></td>
	<td><? echo $thisDescription; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Credits : </b></td>
	<td><? echo $thisCredits; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Open : </b></td>
	<td><? echo $thisOpen; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>OpenAccuracy : </b></td>
	<td><? echo $thisOpenAccuracy; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Close : </b></td>
	<td><? echo $thisClose; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>CloseAccuracy : </b></td>
	<td><? echo $thisCloseAccuracy; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Long : </b></td>
	<td><? echo $thisLong; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Lat : </b></td>
	<td><? echo $thisLat; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Km : </b></td>
	<td><? echo $thisKm; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Kmaccuracy : </b></td>
	<td><? echo $thisKmaccuracy; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Events : </b></td>
	<td><? echo $thisEvents; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Photos : </b></td>
	<td><? echo $thisPhotos; ?></td>
</tr>
</table>

<?php
include_once("common/footer.php");
?>