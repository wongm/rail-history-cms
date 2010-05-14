<?php

$pageTitle = 'Update Location Event';
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisEvent_id = $_REQUEST['eventid'];

$sql = "SELECT   * FROM location_events WHERE event_id = '$thisEvent_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisEvent_id = MYSQL_RESULT($result,$i,"event_id");
	$thisLocation = MYSQL_RESULT($result,$i,"location");
	$thisDate = MYSQL_RESULT($result,$i,"date");
	$thisApprox = MYSQL_RESULT($result,$i,"dateAccuracy");
	$thisAdded = MYSQL_RESULT($result,$i,"added");
	$thisModified = MYSQL_RESULT($result,$i,"modified");
	$thisDetails = stripslashes(MYSQL_RESULT($result,$i,"details"));
	$thisSource = MYSQL_RESULT($result,$i,"source");
	$thisSourceDetail = MYSQL_RESULT($result,$i,"sourcedetail");
	
	// checking if this date is marked intereseting
	$year = substr($thisDate,0,4);
	$sqlCheckingForYear = "SELECT * FROM location_years WHERE location = '$thisLocation' AND year = ".$year;
	$resultCheckingForYear = MYSQL_QUERY($sqlCheckingForYear);
	if ( MYSQL_NUMROWS($resultCheckingForYear) != '0') 
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
		<td><? echo $thisEvent_id; ?> <input type="hidden" name="thisEvent_idField"value="<? echo $thisEvent_id; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Location :  </b> </td>
		<td> <select name="thisLocationField">
<? drawLocationNameSelectFields($thisLocation)	; ?>	
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Date :  </b> </td>
		<td> <input type="text" name="thisDateField" size="20" value="<? echo $thisDate; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Approx? :  </b> </td>
		<td> <select name="thisApproxField">
<? drawApproxTimeFields($thisApprox); ?>
		</select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Diagram Changed :  </b> </td>
		<td> <input type="checkbox" name="thisDiagramField" <? echo $thisDiagram; ?> >  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Added :  </b> </td>
		<td> <input type="text" name="thisAddedField" size="21" value="<? echo $thisAdded; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Modified :  </b> </td>
		<td> <input type="text" name="thisModifiedField" size="21" value="<? echo $thisModified; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Details :  </b> </td>
		<td> <textarea name="thisDetailsField" wrap="VIRTUAL" cols="50" rows="4"><? echo $thisDetails; ?></textarea></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> LX Updates :  </b><br>
		<b>Replaces Details!</b> </td>
		<td><select name="thisLxDetailsField">
<? drawLocationLxEventFields($thisDetails); ?>
		</select></td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Source :  </b> </td>
		<td> <select name="thisSourceField">
<? drawSourcesSelectFields($thisSource); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Source Details :  </b> </td>
		<td> <input type="text" name="thisSourceDetailsField" size="20" value="<? echo $thisSourceDetails; ?>">  </td> 
	</tr>
</table>

<input type="submit" name="submitUpdateLocation_eventsForm" value="Update Location_events">
<input type="reset" name="resetForm" value="Clear Form">

</form>
</fieldset>

<?php
include_once("common/footer.php");
?>