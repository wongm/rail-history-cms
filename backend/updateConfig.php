<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisValue = addslashes($_REQUEST['thisValueField']);

?>
<?
$sql = "UPDATE config SET name = '$thisName' , value = '$thisValue'  WHERE name = '$thisName'";
$result = MYSQL_QUERY($sql);

if ($result != 0)
{
	failed();
}	?>
Record  has been updated in the database. Here is the updated information :- <br><br>

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
<br><br><a href="listConfig.php">Go Back to List All Records</a>

<?php
include_once("common/footer.php");
?>