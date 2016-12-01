<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisType_id = addslashes($_REQUEST['thisType_idField']);
	$thisBasic = addslashes($_REQUEST['thisBasicField']);
	$thisMore = addslashes($_REQUEST['thisMoreField']);
	$thisSpecific = addslashes($_REQUEST['thisSpecificField']);

	if ($thisBasic == '')
	{
		insertfail();
	}
	else
	{
$sqlQuery = "INSERT INTO `location_types` (`basic` , `more` , `specific` ) VALUES ('$thisBasic' , '$thisMore' , '$thisSpecific' )";
$result = query_full_array($sqlQuery);

if ($result != 0)
{
	failed();
}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Type_id : </b></td>
	<td><?php echo $thisType_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Basic : </b></td>
	<td><?php echo $thisBasic; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>More : </b></td>
	<td><?php echo $thisMore; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Specific : </b></td>
	<td><?php echo $thisSpecific; ?></td>
</tr>
</table>

<a href="enterNewLocation_types.php">More?</a>

<?php
}	// end if
include_once("common/footer.php");
?>