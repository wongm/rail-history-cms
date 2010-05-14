<?php

$pageTitle = 'Update Location Sources';
include_once("common/dbConnection.php");
include_once("common/header.php");

$uniqueId = $_REQUEST['id'];
$type = $_REQUEST['type'];
$niceName = ucfirst($type);

$sql = "SELECT  * FROM object_sources WHERE linkzor_id = '$uniqueId'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$uniqueId = MYSQL_RESULT($result,$i,"linkzor_id");
	$thisObjectId = MYSQL_RESULT($result,$i,$type."_id");
	$thisSourceId = MYSQL_RESULT($result,$i,"source_id");
	$thisExtra = stripSlashes(MYSQL_RESULT($result,$i,"extra"));
	$thisDate = stripslashes(MYSQL_RESULT($result,$i,"date"));
	$thisPage = stripslashes(MYSQL_RESULT($result,$i,"page"));
	$thisUrl = stripslashes(MYSQL_RESULT($result,$i,"url"));
	$thisUrlTitle = stripslashes(MYSQL_RESULT($result,$i,"url_title"));

}
?>
<fieldset>
<form name="objectSourcesUpdateForm" method="POST" action="updateObjectSources.php">
<input type="hidden" name="thisLinkzorIdField" value="<? echo $uniqueId; ?>">
<input type="hidden" name="type" value="<? echo $type; ?>">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> ID :  </b> </td>
		<td><? echo $uniqueId; ?></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <?=$niceName?> :  </b> </td>
		<td> <select name="thisObjectIdField">
<?
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
<? drawSourcesSelectFields($thisSourceId); ?> 
		</select></td> 
	</tr>
			
	<tr><td align="right"><b>Extra : </b></td><td><input type="text" name="thisExtraField" size="80" value="<?=$thisExtra?>"></td></tr>
	<tr><td align="right"><b>URL : </b></td><td><input type="text" name="thisURLField" size="80" value="<?=$thisUrl?>"></td></tr>
	<tr><td align="right"><b>Link title : </b></td><td><input type="text" name="thisLinkTitleField" size="80" value="<?=$thisUrlTitle?>"></td></tr>
	<tr><td align="right"><b>Page : </b></td><td><input type="text" name="thisPageField" size="80" value="<?=$thisPage?>"></td></tr>
	<tr><td align="right"><b>Date : </b></td><td><input type="text" name="thisDateField" size="80" value="<?=$thisDate?>"></td></tr>

</table>

<input type="submit" name="submitUpdateObjectourcesForm" value="Update <?=$niceName?> Sources">
<input type="reset" name="resetForm" value="Clear Form">

</form></fieldset>

<?php
include_once("common/footer.php");
?>