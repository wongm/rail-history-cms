<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisId = $_REQUEST['id']
?>
<?php
$sql = "SELECT   * FROM articles WHERE article_id = '$thisId'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisId = $result[$i]["article_id"];
	$thisLink = $result[$i]["link"];
	$thisTitle = $result[$i]["title"];
	$thisDescription = $result[$i]["description"];
	$thisContent = $result[$i]["content"];

}
?>

<h2>Confirm Record Deletion</h2><br><br>

<table>
<tr height="30">
	<td align="right"><b>Article_id : </b></td>
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

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="articlesEnterForm" method="POST" action="deleteArticles.php">
<input type="hidden" name="thisIdField" value="<?php echo $thisId; ?>">
<input type="submit" name="submitConfirmDeleteArticlesForm" value="Delete  from Articles">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>