<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisId = addslashes($_REQUEST['thisIdField']);
	$thisLink = addslashes($_REQUEST['thisLinkField']);
	$thisTitle = addslashes($_REQUEST['thisTitleField']);
	$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
	$thisContent = addslashes($_REQUEST['thisContentField']);

?>
<?php
$sql = "DELETE FROM articles WHERE id = '$thisId'";
$result = MYSQL_QUERY($sql);

?>
Record  has been deleted from database. Here is the deleted record :-<br><br>

<table>
<tr height="30">
	<td align="right"><b>Id : </b></td>
	<td><?php echo $thisId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Link : </b></td>
	<td><?php echo $thisLink; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Title : </b></td>
	<td><?php echo $thisTitle; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Description : </b></td>
	<td><?php echo $thisDescription; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Content : </b></td>
	<td><?php echo $thisContent; ?></td>
</tr>
</table>

<?php
include_once("common/footer.php");
?>