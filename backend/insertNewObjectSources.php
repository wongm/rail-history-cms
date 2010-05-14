<?php
include_once("common/dbConnection.php");
//include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisObjectId = addslashes($_REQUEST['thisObjectIdField']);
	$thisSourceId = addslashes($_REQUEST['thisSourceIdField']);
	$thisExtra = addslashes($_REQUEST['thisExtraField']);
	$thisURL = addslashes($_REQUEST['thisURLField']);
	$thisLinkTitle = addslashes($_REQUEST['thisLinkTitleField']);
	$thisPage = addslashes($_REQUEST['thisPageField']);
	$thisDate = addslashes($_REQUEST['thisDateField']);
	$type = addslashes($_REQUEST['type']);
	
	$a = $b = '';
	
	if ($thisExtra != '')
	{
		$a .= ", extra";
		$b .= " , '$thisExtra' ";
	}
	
	if ($thisURL != '')
	{
		$a .= ", url";
		$b .= " , '$thisURL' ";
	}
	
	if ($thisLinkTitle != '')
	{
		$a .= ", url_title";
		$b .= " , '$thisLinkTitle' ";
	}
	
	if ($thisPage != '')
	{
		$a .= ", page";
		$b .= " , '$thisPage' ";
	}

	if ($thisDate != '')
	{
		$a .= ", date";
		$b .= " , '$thisDate' ";
	}
	
	if ($thisObjectId == '')
	{
		insertfail();
	}
	else
	{		
		$sqlQuery = "INSERT INTO object_sources (".$type."_id , source_id ".$a.") VALUES ('$thisObjectId' , '$thisSourceId' ".$b.")";
		$result = MYSQL_QUERY($sqlQuery);
		
		Header("Location: ".$_SERVER['HTTP_REFERER']."#sources");
		
		if ($result != 0)
		{
			failed();
		}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b><?=ucfirst($type)?> ID : </b></td>
	<td><? echo $thisObjectId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Source ID : </b></td>
	<td><? echo $thisSourceId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Extra : </b></td>
	<td><? echo $thisExtra; ?></td>
</tr>
</table>

<?php
}	// end if
include_once("common/footer.php");
?>