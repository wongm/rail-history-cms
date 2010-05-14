<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
include_once("../common/formatting-functions.php");
?>
<?php
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

if ($result != 0)
{
	failed();
}	?>
Record  has been updated in the database. Here is the updated information :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Id : </b></td>
	<td><? echo $thisId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Link : </b></td>
	<td><? echo $thisLink; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Title : </b></td>
	<td><? echo $thisTitle; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Description : </b></td>
	<td><? echo $thisDescription; ?></td>
</tr>
<tr height="30">
    <td valign="top" align="right"><b>Content: </b></td>
    <td bgcolor="white"><? echo drawFormattedText(stripslashes(stripslashes($thisContent))); ?></td>
</tr>
</table>
<br><br><a href="listArticles.php">Go Back to List All Records</a>

<?php
include_once("common/footer.php");
?>