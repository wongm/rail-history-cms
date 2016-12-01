<?php
include_once("common/dbConnection.php");
//include_once("common/header.php");

// Retreiving Form Elements from Form
$thisSource_id = addslashes($_REQUEST['thisSource_idField']);
$thisShort = addslashes($_REQUEST['thisShortField']);
$thisName = addslashes($_REQUEST['thisNameField']);
$thisDetails = addslashes($_REQUEST['thisDetailsField']);

$sql = "UPDATE sources SET source_id = '$thisSource_id' , short = '$thisShort' , name = '$thisName' , details = '$thisDetails'  WHERE source_id = '$thisSource_id'";
$result = query_full_array($sql);

// redirect to the list all page
header("Location: listSources.php?updated=".urlencode(stripslashes(stripslashes($thisName))));
?>
Record  has been updated in the database. Here is the updated information :- <br><br>

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
<br><br><a href="listSources.php">Go Back to List All Records</a>

<?php
include_once("common/footer.php");
?>