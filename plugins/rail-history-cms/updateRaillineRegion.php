<?php
include_once("../common/dbConnection.php");

// Retreiving Form Elements from Form
$thisLinkzor_id = addslashes($_REQUEST['thisLinkzor_idField']);
$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
$thisArticle_id = addslashes($_REQUEST['thisArticle_idField']);
$thisContent = addslashes($_REQUEST['thisContentField']);

if ($thisLinkzor_id > 0)
{
	$sql = "UPDATE railline_region SET content = '$thisContent' , line_id = '$thisLine_id' , article_id = '$thisArticle_id'  WHERE linkzor_id = '$thisLinkzor_id'";
	$result = query_full_array($sql);
}
else
{
	$sqlQuery = "INSERT INTO railline_region (line_id , article_id , content ) VALUES ('$thisLine_id' , '$thisArticle_id' , '$thisContent' )";
	$result = query_full_array($sqlQuery);
}

Header("Location: /backend/listRaillineRegion.php");
?>