<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
	$thisLocation_id = addslashes($_REQUEST['thisLocation_idField']);
	$thisKm = addslashes($_REQUEST['thisKmField']);
	$thisKmaccuracy = addslashes($_REQUEST['thisKmAccuracyField']);

?>
<?
$sql = "DELETE FROM locations_raillines WHERE line_id = '$thisLine_id' AND  location_id = '$thisLocation_id'";
$result = MYSQL_QUERY($sql);

?>
Record  has been deleted from database. Here is the deleted record :-<br><br>

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

<?php
include_once("common/footer.php");
?>