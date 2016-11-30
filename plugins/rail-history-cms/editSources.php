<?php

$pageTitle = 'Update Sources';
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisSource_id = $_REQUEST['id'];

$sql = "SELECT   * FROM sources WHERE source_id = '$thisSource_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisSource_id = stripSlashes(MYSQL_RESULT($result,$i,"source_id"));
	$thisShort = stripSlashes(MYSQL_RESULT($result,$i,"short"));
	$thisName = stripSlashes(MYSQL_RESULT($result,$i,"name"));
	$thisDetails = stripSlashes(MYSQL_RESULT($result,$i,"details"));

}

drawSourceHelpText();
?>
<form name="sourcesUpdateForm" method="POST" action="updateSources.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
<input type="hidden" name="thisSource_idField" value="<?php echo $thisSource_id; ?>">

	<tr valign="top" height="20">
		<td align="right"> <b> Source_id :  </b> </td>
		<td><?php echo $thisSource_id; ?></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Short :  </b> </td>
		<td> <textarea name="thisShortField" wrap="VIRTUAL" cols="80" rows="1"><?php echo $thisShort; ?></textarea>  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <textarea type="text" name="thisNameField" wrap="VIRTUAL" cols="80" rows="1"><?php echo $thisName; ?></textarea>  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Details :  </b> </td>
		<td> <textarea type="text" name="thisDetailsField" wrap="VIRTUAL" cols="80" rows="5"><?php echo $thisDetails; ?></textarea>  </td> 
	</tr>
</table>

<input type="submit" name="submitUpdateSourcesForm" value="Update Sources">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>