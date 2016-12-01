<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisLinkzor_id = addslashes($_REQUEST['thisLinkzor_idField']);
	$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
	$thisArticle_id = addslashes($_REQUEST['thisArticle_idField']);

?>
<?php
$sql = "DELETE FROM railline_region WHERE linkzor_id = '$thisLinkzor_id'";
$result = query_full_array($sql);

?>
Record  has been deleted from database. Here is the deleted record :-<br><br>

<table>
<tr height="30">
	<td align="right"><b>Linkzor_id : </b></td>
	<td><?php echo $thisLinkzor_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Line_id : </b></td>
	<td><?php echo $thisLine_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Article_id : </b></td>
	<td><?php echo $thisArticle_id; ?></td>
</tr>
</table>

<?php
include_once("common/footer.php");
?>