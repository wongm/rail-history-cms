<?php

$pageTitle = 'Update Location Sources';
include_once("common/dbConnection.php");
include_once("common/header.php");

$uniqueId = $_REQUEST['id'];
$type = $_REQUEST['type'];
$niceName = ucfirst($type);

$sql = "SELECT  * FROM object_sources WHERE linkzor_id = '$uniqueId'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$uniqueId = $result[$i]["linkzor_id"];
	$thisObjectId = $result[$i][$type."_id"];
	$thisSourceId = $result[$i]["source_id"];
	$thisExtra = stripSlashes($result[$i]["extra"]);
	$thisDate = stripslashes($result[$i]["date"]);
	$thisPage = stripslashes($result[$i]["page"]);
	$thisUrl = stripslashes($result[$i]["url"]);
	$thisUrlTitle = stripslashes($result[$i]["url_title"]);

}
?>
<fieldset>
<form name="objectSourcesUpdateForm" method="POST" action="updateObjectSources.php">
<input type="hidden" name="thisLinkzorIdField" value="<?php echo $uniqueId; ?>">
<input type="hidden" name="type" value="<?php echo $type; ?>">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> ID :  </b> </td>
		<td><?php echo $uniqueId; ?></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <?php echo $niceName?> :  </b> </td>
		<td> <select name="thisObjectIdField">
<?php
	if ($type == 'location')
	{
		drawLocationNameSelectFields($thisObjectId);
	}
	else if ($type = 'railline')
	{
		drawLineNameSelectFields($thisObjectId);
	}
?>
		</select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Source :  </b> </td>
		<td> <select name="thisSourceIdField">
<?php drawSourcesSelectFields($thisSourceId); ?> 
		</select></td> 
	</tr>
			
	<tr><td align="right"><b>Extra : </b></td><td><input type="text" name="thisExtraField" size="80" value="<?php echo $thisExtra?>"></td></tr>
	<tr><td align="right"><b>URL : </b></td><td><input type="text" name="thisURLField" size="80" value="<?php echo $thisUrl?>"></td></tr>
	<tr><td align="right"><b>Link title : </b></td><td><input type="text" name="thisLinkTitleField" size="80" value="<?php echo $thisUrlTitle?>"></td></tr>
	<tr><td align="right"><b>Page : </b></td><td><input type="text" name="thisPageField" size="80" value="<?php echo $thisPage?>"></td></tr>
	<tr><td align="right"><b>Date : </b></td><td><input type="text" name="thisDateField" size="80" value="<?php echo $thisDate?>"></td></tr>

</table>

<input type="submit" name="submitUpdateObjectourcesForm" value="Update <?php echo $niceName?> Sources">
<input type="reset" name="resetForm" value="Clear Form">

</form></fieldset>

<?php
include_once("common/footer.php");
?>