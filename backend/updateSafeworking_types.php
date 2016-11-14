<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisSafeworking_id = addslashes($_REQUEST['thisSafeworking_idField']);
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisLink = addslashes($_REQUEST['thisLinkField']);
	$thisDetails = addslashes($_REQUEST['thisDetailsField']);

?>
<?php
$sql = "UPDATE safeworking_types SET safeworking_id = '$thisSafeworking_id' , name = '$thisName' , link = '$thisLink' , details = '$thisDetails'  WHERE safeworking_id = '$thisSafeworking_id'";
$result = MYSQL_QUERY($sql);
if ($result != 0)
{
	failed();
}

?>
Record  has been updated in the database. Here is the updated information :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Safeworking_id : </b></td>
	<td><?php echo $thisSafeworking_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><?php echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Link : </b></td>
	<td><?php echo $thisLink; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Details : </b></td>
	<td><?php echo $thisDetails; ?></td>
</tr>
</table>
<br><br><a href="listSafeworking_types.php">Go Back to List All Records</a>

<?php
include_once("common/footer.php");
?>