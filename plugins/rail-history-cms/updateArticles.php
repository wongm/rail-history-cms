<?php
include_once("common/dbConnection.php");

// Retreiving Form Elements from Form
$thisId = addslashes($_REQUEST['thisIdField']);
$thisLink = addslashes($_REQUEST['thisLinkField']);
$thisTitle = addslashes($_REQUEST['thisTitleField']);
$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
$thisContent = addslashes($_REQUEST['thisContentField']);
$thisPhotos = addslashes($_REQUEST['thisPhotosField']);
$thisLine = addslashes($_REQUEST['thisLineField']);
$thisCaption = addslashes($_REQUEST['thisCaptionField']);

$sql = "UPDATE articles SET caption = '$thisCaption' , link = '$thisLink' , title = '$thisTitle' , description = '$thisDescription' , content = '$thisContent' , photos = '$thisPhotos' , line_id = '$thisLine' ";

// for auto modification of last modified 
if ($_REQUEST['flag'] == 'on')
{
	$thisModified = date('Y-m-d H:i:s');
	$sql = $sql." , modified = '$thisModified'";
	$done .= '<p>Last updated articles updated!</p>';
}

$sql .= " WHERE article_id = '$thisId'";
$result = MYSQL_QUERY($sql);

Header("Location: /backend/editArticles.php?id=" . $thisId . "#general");
?>