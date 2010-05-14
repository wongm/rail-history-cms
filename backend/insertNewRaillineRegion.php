<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
	$thisArticle_id = addslashes($_REQUEST['thisArticle_idField']);
	$thisContent = addslashes($_REQUEST['thisContentField']);
?>
<?
$sqlQuery = "INSERT INTO railline_region (line_id , article_id , content ) VALUES ('$thisLine_id' , '$thisArticle_id' , '$thisContent' )";
$result = MYSQL_QUERY($sqlQuery);

?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

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

<?php
include_once("common/footer.php");
?>