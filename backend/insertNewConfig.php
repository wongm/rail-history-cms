<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisValue = addslashes($_REQUEST['thisValueField']);

	if ($thisName == '' OR $thisValue == '')
	{
		insertfail();
	}
	else
	{
		$sqlQuery = "INSERT INTO config (name , value ) VALUES ('$thisName' , '$thisValue' )";
		$result = MYSQL_QUERY($sqlQuery);
		
		if ($result != 0)
		{
			failed();
		}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><? echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Value : </b></td>
	<td><? echo $thisValue; ?></td>
</tr>
</table>

<?php
}	// end if
include_once("common/footer.php");
?>