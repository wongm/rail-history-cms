<?php
include_once("common/dbConnection.php");
//include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisLinkzorId = addslashes($_REQUEST['thisLinkzorIdField']);
	$thisObjectId = addslashes($_REQUEST['thisObjectIdField']);
	$thisSourceId = addslashes($_REQUEST['thisSourceIdField']);
	$thisExtra = addslashes($_REQUEST['thisExtraField']);
	$thisDate = addslashes($_REQUEST['thisDateField']);
	$thisPage = addslashes($_REQUEST['thisPageField']);
	$thisURL = addslashes($_REQUEST['thisURLField']);
	$thisLinkTitle = addslashes($_REQUEST['thisLinkTitleField']);
	
	$type = addslashes($_REQUEST['type']);
	$niceName = ucfirst($type);

$sql = "UPDATE object_sources SET ".$type."_id = '$thisObjectId' , source_id = '$thisSourceId',  extra = '$thisExtra',  
		date = '$thisDate', page = '$thisPage', url = '$thisURL', url_title = '$thisLinkTitle'
		WHERE linkzor_id = '$thisLinkzorId'";
$result = MYSQL_QUERY($sql);

Header("Location: ".$_SERVER['HTTP_REFERER']."#sources");

if ($result != 0)
{
	failed();
}	?>
Record has been updated in the database. Here is the updated information :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>LinkzorId : </b></td>
	<td><? echo $thisLinkzorId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>LocationId : </b></td>
	<td><? echo $thisObjectId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>SourceId : </b></td>
	<td><? echo $thisSourceId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Extra : </b></td>
	<td><? echo $thisExtra; ?></td>
</tr>
</table>
<br><br><a href="listLocation_sources.php">Go Back to List All Records</a>

<?php
include_once("common/footer.php");
?>