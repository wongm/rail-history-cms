<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisLinkzor_id = $_REQUEST['id']
?>
<?php
$sql = "SELECT   * FROM railline_region WHERE linkzor_id = '$thisLinkzor_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisLinkzor_id = MYSQL_RESULT($result,$i,"linkzor_id");
	$thisLine_id = MYSQL_RESULT($result,$i,"line_id");
	$thisArticle_id = MYSQL_RESULT($result,$i,"article_id");

}
?>

<h2>Confirm Record Deletion</h2><br><br>

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

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="railline_regionEnterForm" method="POST" action="deleteRaillineRegion.php">
<input type="hidden" name="thisLinkzor_idField" value="<?php echo $thisLinkzor_id; ?>">
<input type="submit" name="submitConfirmDeleteRaillineRegionForm" value="Delete  from RaillineRegion">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>