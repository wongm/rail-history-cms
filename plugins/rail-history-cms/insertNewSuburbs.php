<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisSuburb_id = addslashes($_REQUEST['thisSuburb_idField']);
	$thisName = addslashes($_REQUEST['thisNameField']);

?>
<?php
$sqlQuery = "INSERT INTO suburbs (name ) VALUES ('$thisName' )";
$result = query_full_array($sqlQuery);

if ($result != 0)
{
	failed();
}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Suburb_id : </b></td>
	<td><?php echo $thisSuburb_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><?php echo $thisName; ?></td>
</tr>
</table>

<a href="enterNewSuburbs.php">Add Another?</a>

<?php
include_once("common/footer.php");
?>