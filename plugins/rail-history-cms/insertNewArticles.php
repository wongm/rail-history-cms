<?php
include_once("common/dbConnection.php");

//do we want to create an article, or a region?
if (isset($_REQUEST['region']))
{
	$thisLine = -1;
}
else
{
	$thisLine = 0;
}

$thisLink = rand();

//$sqlQuery = "INSERT INTO articles (link , title , description , content , photos, line_id) VALUES ('$thisLink' , '$thisTitle' , '$thisDescription' , '$thisContent' , '$thisPhotos' , '$thisLine')";
$sqlQuery = "INSERT INTO articles (link, title, line_id) VALUES ('$thisLink', '$thisLink', '$thisLine')";
$result = query_full_array($sqlQuery);

// get location ID for next query
$thisArticleId = query_full_array("SELECT article_id FROM articles WHERE link = '$thisLink'")[0]['article_id'];

Header("Location: editArticles.php?id=" . $thisArticleId);
?>