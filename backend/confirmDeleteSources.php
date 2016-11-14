<?php
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisSource_id = $_REQUEST['id'];

$sql = "SELECT   * FROM sources WHERE source_id = '$thisSource_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisSource_id = MYSQL_RESULT($result,$i,"source_id");
	$thisShort = MYSQL_RESULT($result,$i,"short");
	$thisName = MYSQL_RESULT($result,$i,"name");
	$thisDetails = MYSQL_RESULT($result,$i,"details");

}
?>

<h2>Confirm Record Deletion</h2><br><br>

<table>
<tr height="30">
	<td align="right"><b>Source_id : </b></td>
	<td><?php echo $thisSource_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Short : </b></td>
	<td><?php echo $thisShort; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><?php echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Details : </b></td>
	<td><?php echo $thisDetails; ?></td>
</tr>
</table>

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="sourcesEnterForm" method="POST" action="deleteSources.php">
<input type="hidden" name="thisSource_idField" value="<?php echo $thisSource_id; ?>">
<input type="submit" name="submitConfirmDeleteSourcesForm" value="Delete  from Sources">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>