<?php

$pageTitle = "Update Locations - Raillines links";
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisLine_id = $_REQUEST['line'];
$thisLocation_id = $_REQUEST['location'];

$sql = "SELECT  * FROM locations_raillines WHERE line_id = '$thisLine_id' AND location_id = '$thisLocation_id' ";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisLine_id = $result[$i]["line_id"];
	$thisLocation_id = $result[$i]["location_id"];
	$thisKm = $result[$i]["km"];
	$thisKmAccuracy = $result[$i]["kmaccuracy"];
	$thisJunctionType = $result[$i]["junctiontype"];
}
?>
<fieldset>
<form name="locations_raillinesUpdateForm" method="POST" action="updateLocationsRaillines.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Location :  </b> </td>
		<td> <select name="thisLocation_idField">
<?php drawLocationNameSelectFields($thisLocation_id); ?>
		</select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Line :  </b> </td>
		<td> <select name="thisLine_idField">
<?php drawLineNameSelectFields($thisLine_id); ?>
    	</select></td>
    </tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Km :  </b> </td>
		<td> <input type="text" name="thisKmField" size="20" value="<?php echo $thisKm; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> KM Accuracy :  </b> </td>
		<td><select name="thisKmAccuracyField">
<?php drawApproxDistanceFields($thisKmAccuracy); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Type :  </b> </td>
		<td><select name="thisJunctionTypeField">
<?php drawRaillineTypeFields($thisJunctionType) ?>	
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