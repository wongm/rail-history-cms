<?php

$pageTitle = 'Update Location Event';
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisEvent_id = $_REQUEST['eventid'];

$sql = "SELECT   * FROM location_events WHERE event_id = '$thisEvent_id'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisEvent_id = $result[$i]["event_id"];
	$thisLocation = $result[$i]["location"];
	$thisDate = $result[$i]["date"];
	$thisApprox = $result[$i]["dateAccuracy"];
	$thisAdded = $result[$i]["added"];
	$thisModified = $result[$i]["modified"];
	$thisDetails = stripslashes($result[$i]["details"]);
	$thisSource = $result[$i]["source"];
	$thisSourceDetail = $result[$i]["sourcedetail"];
	
	// checking if this date is marked intereseting
	$year = substr($thisDate,0,4);
	$sqlCheckingForYear = "SELECT * FROM location_years WHERE location = '$thisLocation' AND year = ".$year;
	$resultCheckingForYear = query_full_array($sqlCheckingForYear);
	if ( sizeof($resultCheckingForYear) != '0') 
	{ 
		$thisDiagram = 'checked'; 
	}

}
?>
<fieldset>
<form name="location_eventsUpdateForm" method="POST" action="updateLocation_events.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Event ID :  </b> </td>
		<td><?php echo $thisEvent_id; ?> <input type="hidden" name="thisEvent_idField"value="<?php echo $thisEvent_id; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Location :  </b> </td>
		<td> <select name="thisLocationField">
<?php drawLocationNameSelectFields($thisLocation)	; ?>	
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Date :  </b> </td>
		<td> <input type="text" name="thisDateField" size="20" value="<?php echo $thisDate; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Approx? :  </b> </td>
		<td> <select name="thisApproxField">
<?php drawApproxTimeFields($thisApprox); ?>
		</select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Diagram Changed :  </b> </td>
		<td> <input type="checkbox" name="thisDiagramField" <?php echo $thisDiagram; ?> >  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Added :  </b> </td>
		<td> <input type="text" name="thisAddedField" size="21" value="<?php echo $thisAdded; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Modified :  </b> </td>
		<td> <input type="text" name="thisModifiedField" size="21" value="<?php echo $thisModified; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Details :  </b> </td>
		<td> <textarea name="thisDetailsField" wrap="VIRTUAL" cols="50" rows="4"><?php echo $thisDetails; ?></textarea></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> LX Updates :  </b><br>
		<b>Replaces Details!</b> </td>
		<td><select name="thisLxDetailsField">
<?php drawLocationLxEventFields($thisDetails); ?>
		</select></td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Source :  </b> </td>
		<td> <select name="thisSourceField">
<?php drawSourcesSelectFields($thisSource); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Source Details :  </b> </td>
		<td> <input type="text" name="thisSourceDetailsField" size="20" value="<?php echo $thisSourceDetails; ?>">  </td> 
	</tr>
</table>

<input type="submit" name="submitUpdateLocation_eventsForm" value="Update Location_events">
<input type="reset" name="resetForm" value="Clear Form">

</form>
</fieldset>

<?php
include_once("common/footer.php");
?>