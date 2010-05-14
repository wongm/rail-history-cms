<?php

$pageTitle = "Update Locations - Raillines links";
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisLine_id = $_REQUEST['line'];
$thisLocation_id = $_REQUEST['location'];

$sql = "SELECT  * FROM locations_raillines WHERE line_id = '$thisLine_id' AND location_id = '$thisLocation_id' ";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisLine_id = MYSQL_RESULT($result,$i,"line_id");
	$thisLocation_id = MYSQL_RESULT($result,$i,"location_id");
	$thisKm = MYSQL_RESULT($result,$i,"km");
	$thisKmAccuracy = MYSQL_RESULT($result,$i,"kmaccuracy");
	$thisJunctionType = MYSQL_RESULT($result,$i,"junctiontype");
}
?>
<fieldset>
<form name="locations_raillinesUpdateForm" method="POST" action="updateLocationsRaillines.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Location :  </b> </td>
		<td> <select name="thisLocation_idField">
<? drawLocationNameSelectFields($thisLocation_id); ?>
		</select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Line :  </b> </td>
		<td> <select name="thisLine_idField">
<? drawLineNameSelectFields($thisLine_id); ?>
    	</select></td>
    </tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Km :  </b> </td>
		<td> <input type="text" name="thisKmField" size="20" value="<? echo $thisKm; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> KM Accuracy :  </b> </td>
		<td><select name="thisKmAccuracyField">
<? drawApproxDistanceFields($thisKmAccuracy); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Type :  </b> </td>
		<td><select name="thisJunctionTypeField">
<? drawRaillineTypeFields($thisJunctionType) ?>	
		</select></td>
	</tr>
</table>

<input type="submit" name="submitUpdateLocations_raillinesForm" value="Update Locations_raillines">
<input type="reset" name="resetForm" value="Clear Form">

</form>
</fieldset>

<?php
include_once("common/footer.php");
?>