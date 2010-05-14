<?php
include_once("../common/dbConnection.php");
include_once("../common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisLocation_id = addslashes($_REQUEST['thisLocation_idField']);
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisSuburb = addslashes($_REQUEST['thisSuburbField']);
	$thisLine_old = addslashes($_REQUEST['thisLine_oldField']);
	$thisTracks = addslashes($_REQUEST['thisTracksField']);
	$thisType = addslashes($_REQUEST['thisTypeField']);
	$thisImage = addslashes($_REQUEST['thisImageField']);
	$thisLx_type = addslashes($_REQUEST['thisLx_typeField']);
	$thisUrl = addslashes($_REQUEST['thisUrlField']);
	$thisDiagrams = addslashes($_REQUEST['thisDiagramsField']);
	$thisDisplay = addslashes($_REQUEST['thisDisplayField']);
	$thisStatus = addslashes($_REQUEST['thisStatusField']);
	$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
	$thisCredits = addslashes($_REQUEST['thisCreditsField']);
	$thisOpen = addslashes($_REQUEST['thisOpenField']);
	$thisOpenAccuracy = addslashes($_REQUEST['thisOpenAccuracyField']);
	$thisClose = addslashes($_REQUEST['thisCloseField']);
	$thisCloseAccuracy = addslashes($_REQUEST['thisCloseAccuracyField']);
	$thisLong = addslashes($_REQUEST['thisLongField']);
	$thisLat = addslashes($_REQUEST['thisLatField']);
	$thisKm_old = addslashes($_REQUEST['thisKm_oldField']);
	$thisKmaccuracy_old = addslashes($_REQUEST['thisKmaccuracy_oldField']);
	$thisEvents = addslashes($_REQUEST['thisEventsField']);
	$thisPhotos = addslashes($_REQUEST['thisPhotosField']);
	$thisAdded = addslashes($_REQUEST['thisAddedField']);
	$thisModified = addslashes($_REQUEST['thisModifiedField']);

?>
<?
$sql = "DELETE FROM locations WHERE location_id = '$thisLocation_id'";
$result = MYSQL_QUERY($sql);

?>
Record  has been deleted from database. Here is the deleted record :-<br><br>

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
	<td align="right"><b>Line_old : </b></td>
	<td><? echo $thisLine_old; ?></td>
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
	<td align="right"><b>Diagrams : </b></td>
	<td><? echo $thisDiagrams; ?></td>
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
	<td align="right"><b>Km_old : </b></td>
	<td><? echo $thisKm_old; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Kmaccuracy_old : </b></td>
	<td><? echo $thisKmaccuracy_old; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Events : </b></td>
	<td><? echo $thisEvents; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Photos : </b></td>
	<td><? echo $thisPhotos; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Added : </b></td>
	<td><? echo $thisAdded; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Modified : </b></td>
	<td><? echo $thisModified; ?></td>
</tr>
</table>

<?php
include_once("../common/footer.php");
?>