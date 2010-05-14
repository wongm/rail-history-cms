<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisLine_id = $_REQUEST['line'];
$thisLocation_id = $_REQUEST['location'];
?>
<?php
$sql = "SELECT   * FROM locations_raillines WHERE line_id = '$thisLine_id' AND  location_id = '$thisLocation_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisLine_id = MYSQL_RESULT($result,$i,"line_id");
	$thisLocation_id = MYSQL_RESULT($result,$i,"location_id");
	$thisKm = MYSQL_RESULT($result,$i,"km");
	$thisKmaccuracy = MYSQL_RESULT($result,$i,"kmaccuracy");

}
?>

<h2>Confirm Record Deletion</h2><br><br>

<table>
<tr height="30">
	<td align="right"><b>Line_id : </b></td>
	<td><? echo $thisLine_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Location_id : </b></td>
	<td><? echo $thisLocation_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Km : </b></td>
	<td><? echo $thisKm; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Kmaccuracy : </b></td>
	<td><? echo $thisKmaccuracy; ?></td>
</tr>
</table>

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="locations_raillinesEnterForm" method="POST" action="deleteLocations_raillines.php">
<input type="hidden" name="thisLine_idField" value="<? echo $thisLine_id; ?>">
<input type="hidden" name="thisLocation_idField" value="<? echo $thisLocation_id; ?>">
<input type="submit" name="submitConfirmDeleteLocations_raillinesForm" value="Delete  from Locations_raillines">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>