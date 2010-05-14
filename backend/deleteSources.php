<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisSource_id = addslashes($_REQUEST['thisSource_idField']);
	$thisShort = addslashes($_REQUEST['thisShortField']);
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisDetails = addslashes($_REQUEST['thisDetailsField']);

?>
<?
$sql = "DELETE FROM sources WHERE source_id = '$thisSource_id'";
$result = MYSQL_QUERY($sql);

?>
Record  has been deleted from database. Here is the deleted record :-<br><br>

<table>
<tr height="30">
	<td align="right"><b>Source_id : </b></td>
	<td><? echo $thisSource_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Short : </b></td>
	<td><? echo $thisShort; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><? echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Details : </b></td>
	<td><? echo $thisDetails; ?></td>
</tr>
</table>

<?php
include_once("common/footer.php");
?>