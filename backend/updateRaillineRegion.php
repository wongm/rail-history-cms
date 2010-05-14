<?php
include_once("../common/dbConnection.php");
include_once("../common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisLinkzor_id = addslashes($_REQUEST['thisLinkzor_idField']);
	$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
	$thisArticle_id = addslashes($_REQUEST['thisArticle_idField']);
	$thisContent = addslashes($_REQUEST['thisContentField']);

?>
<?
$sql = "UPDATE railline_region SET content = '$thisContent' , line_id = '$thisLine_id' , article_id = '$thisArticle_id'  WHERE linkzor_id = '$thisLinkzor_id'";
$result = MYSQL_QUERY($sql);

?>
Record  has been updated in the database. Here is the updated information :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Linkzor_id : </b></td>
	<td><? echo $thisLinkzor_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Line_id : </b></td>
	<td><? echo $thisLine_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Article_id : </b></td>
	<td><? echo $thisArticle_id; ?></td>
</tr>
</table>
<br><br><a href="listRailline_region.php">Go Back to List All Records</a>

<?php
include_once("../common/footer.php");
?>