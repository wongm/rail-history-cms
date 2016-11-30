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
<?php
$sqlQuery = "INSERT INTO locations_raillines (line_id , location_id , km , kmaccuracy ) VALUES ('$thisLine_id' , '$thisLocation_id' , '$thisKm' , '$thisKmaccuracy' )";
$result = MYSQL_QUERY($sqlQuery);

?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Line_id : </b></td>
	<td><?php echo $thisLine_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Location_id : </b></td>
	<td><?php echo $thisLocation_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Km : </b></td>
	<td><?php echo $thisKm; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Kmaccuracy : </b></td>
	<td><?php echo $thisKmaccuracy; ?></td>
</tr>
</table>

<?php
include_once("common/footer.php");
?>