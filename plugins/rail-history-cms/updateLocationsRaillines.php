<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
	$thisLocation_id = addslashes($_REQUEST['thisLocation_idField']);
	$thisKm = addslashes($_REQUEST['thisKmField']);
	$thisKmAccuracy = addslashes($_REQUEST['thisKmAccuracyField']);
	$thisJunctionType = addslashes($_REQUEST['thisJunctionTypeField']);

?>
<?php
echo $sql = "UPDATE locations_raillines 
	SET line_id = '$thisLine_id' , location_id = '$thisLocation_id' , km = '$thisKm' , 
	kmaccuracy = '$thisKmAccuracy', junctiontype = '$thisJunctionType' 
	WHERE line_id = '$thisLine_id' AND location_id = '$thisLocation_id'";
$result = query_full_array($sql);

?>
Record  has been updated in the database. Here is the updated information :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Line ID : </b></td>
	<td><?php echo $thisLine_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Location ID : </b></td>
	<td><?php echo $thisLocation_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>KM : </b></td>
	<td><?php echo $thisKm; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>KM accuracy : </b></td>
	<td><?php echo $thisKmAccuracy; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Junction type : </b></td>
	<td><?php echo $thisJunctionType; ?></td>
</tr>
</table>
<br><br><a href="listLocationsRaillines.php">Go Back to List All Records</a>

<?php
include_once("common/footer.php");
?>