<?php
include_once("../common/dbConnection.php");
include_once("../common/header.php");
?>
<?php
$thisLocation_id = $_REQUEST['location']
?>
<?php
$sql = "SELECT   * FROM locations WHERE location_id = '$thisLocation_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisLocation_id = MYSQL_RESULT($result,$i,"location_id");
	$thisName = MYSQL_RESULT($result,$i,"name");
	$thisSuburb = MYSQL_RESULT($result,$i,"suburb");
	$thisLine_old = MYSQL_RESULT($result,$i,"line_old");
	$thisTracks = MYSQL_RESULT($result,$i,"tracks");
	$thisType = MYSQL_RESULT($result,$i,"type");
	$thisImage = MYSQL_RESULT($result,$i,"image");
	$thisLx_type = MYSQL_RESULT($result,$i,"lx_type");
	$thisUrl = MYSQL_RESULT($result,$i,"url");
	$thisDiagrams = MYSQL_RESULT($result,$i,"diagrams");
	$thisDisplay = MYSQL_RESULT($result,$i,"display");
	$thisStatus = MYSQL_RESULT($result,$i,"status");
	$thisDescription = MYSQL_RESULT($result,$i,"description");
	$thisCredits = MYSQL_RESULT($result,$i,"credits");
	$thisOpen = MYSQL_RESULT($result,$i,"open");
	$thisOpenAccuracy = MYSQL_RESULT($result,$i,"openAccuracy");
	$thisClose = MYSQL_RESULT($result,$i,"close");
	$thisCloseAccuracy = MYSQL_RESULT($result,$i,"closeAccuracy");
	$thisLong = MYSQL_RESULT($result,$i,"long");
	$thisLat = MYSQL_RESULT($result,$i,"lat");
	$thisKm_old = MYSQL_RESULT($result,$i,"km_old");
	$thisKmaccuracy_old = MYSQL_RESULT($result,$i,"kmaccuracy_old");
	$thisEvents = MYSQL_RESULT($result,$i,"events");
	$thisPhotos = MYSQL_RESULT($result,$i,"photos");
	$thisAdded = MYSQL_RESULT($result,$i,"added");
	$thisModified = MYSQL_RESULT($result,$i,"modified");

}
?>

<h3>Confirm Record Deletion</h3><br><br>

<table>
<tr height="30">
	<td align="right"><b>Location ID : </b></td>
	<td><?php echo $thisLocation_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><?php echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Added : </b></td>
	<td><?php echo $thisAdded; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Modified : </b></td>
	<td><?php echo $thisModified; ?></td>
</tr>
</table>

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="locationsEnterForm" method="POST" action="deleteLocations.php">
<input type="hidden" name="thisLocation_idField" value="<?php echo $thisLocation_id; ?>">
<input type="submit" name="submitConfirmDeleteLocationsForm" value="Delete  from Locations">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("../common/footer.php");
?>