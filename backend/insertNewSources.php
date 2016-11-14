<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisSource_id = addslashes($_REQUEST['thisSource_idField']);
	$thisShort = addslashes($_REQUEST['thisShortField']);
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisDetails = addslashes($_REQUEST['thisDetailsField']);
	
	if($thisShort == '' OR $thisName == '' OR $thisDetails == '')
	{
		insertfailed();
	}
	else
	{

		$sqlQuery = "INSERT INTO sources (source_id , short , name , details ) VALUES ('$thisSource_id' , '$thisShort' , '$thisName' , '$thisDetails' )";
		$result = MYSQL_QUERY($sqlQuery);
		
		if ($result != 0)
		{
			failed();
		}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Source_id : </b></td>
	<td><?php echo $thisSource_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Short : </b></td>
	<td><?php echo $thisShort; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><?php echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Details : </b></td>
	<td><?php echo $thisDetails; ?></td>
</tr>
</table>

<?php
	} // end fi
include_once("common/footer.php");
?>