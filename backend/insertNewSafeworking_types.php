<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisSafeworking_id = addslashes($_REQUEST['thisSafeworking_idField']);
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisLink = addslashes($_REQUEST['thisLinkField']);
	$thisDetails = addslashes($_REQUEST['thisDetailsField']);

	if ($thisName == '')
	{
		insertfailed();
	}
	else
	{
		$sqlQuery = "INSERT INTO safeworking_types (safeworking_id , name , link , details ) VALUES ('$thisSafeworking_id' , '$thisName' , '$thisLink' , '$thisDetails' )";
		$result = MYSQL_QUERY($sqlQuery);

		if ($result != 0)
		{
			failed();
		}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Safeworking_id : </b></td>
	<td><?php echo $thisSafeworking_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><?php echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Link : </b></td>
	<td><?php echo $thisLink; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Details : </b></td>
	<td><?php echo $thisDetails; ?></td>
</tr>
</table>

<?php
	}	// end if
include_once("common/footer.php");
?>