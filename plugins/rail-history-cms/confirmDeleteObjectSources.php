<?php
include_once("common/dbConnection.php");
include_once("common/header.php");

$id = $_REQUEST['id'];
$type = $_REQUEST['type'];

$sql = "SELECT * FROM object_sources WHERE linkzor_id = '$id'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisObjectId = $result[$i][$type."_id"];
	$thisSourceId = $result[$i]["source_id"];

}
?>

<h2>Confirm <?php echo $type?> Record Deletion</h2><br><br>

<table>
<tr height="30">
	<td align="right"><b>Unique ID : </b></td>
	<td><?php echo $id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b><?php echo ucfirst($type)?> ID : </b></td>
	<td><?php echo $thisObjectId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Source ID : </b></td>
	<td><?php echo $thisSourceId; ?></td>
</tr>
</table>

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="location_sourcesEnterForm" method="POST" action="deleteObjectSources.php">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="type" value="<?php echo $type; ?>">
<input type="submit" name="submitConfirmDeleteLocation_sourcesForm" value="Delete  from Location_sources">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>