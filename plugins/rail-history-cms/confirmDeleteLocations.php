<?php
include_once("../common/dbConnection.php");
include_once("../common/header.php");
?>
<?php
$thisLocation_id = $_REQUEST['location']
?>
<?php
$sql = "SELECT   * FROM locations WHERE location_id = '$thisLocation_id'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisLocation_id = $result[$i]["location_id"];
	$thisName = $result[$i]["name"];
	$thisSuburb = $result[$i]["suburb"];
	$thisLine_old = $result[$i]["line_old"];
	$thisTracks = $result[$i]["tracks"];
	$thisType = $result[$i]["type"];
	$thisImage = $result[$i]["image"];
	$thisLx_type = $result[$i]["lx_type"];
	$thisUrl = $result[$i]["url"];
	$thisDiagrams = $result[$i]["diagrams"];
	$thisDisplay = $result[$i]["display"];
	$thisStatus = $result[$i]["status"];
	$thisDescription = $result[$i]["description"];
	$thisCredits = $result[$i]["credits"];
	$thisOpen = $result[$i]["open"];
	$thisOpenAccuracy = $result[$i]["openAccuracy"];
	$thisClose = $result[$i]["close"];
	$thisCloseAccuracy = $result[$i]["closeAccuracy"];
	$thisLong = $result[$i]["long"];
	$thisLat = $result[$i]["lat"];
	$thisKm_old = $result[$i]["km_old"];
	$thisKmaccuracy_old = $result[$i]["kmaccuracy_old"];
	$thisEvents = $result[$i]["events"];
	$thisPhotos = $result[$i]["photos"];
	$thisAdded = $result[$i]["added"];
	$thisModified = $result[$i]["modified"];

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