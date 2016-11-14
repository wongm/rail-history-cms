<?php

$pageTitle = 'Update Rail Line Event';
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisEvent_id = $_REQUEST['event'];
$thisLink = $_REQUEST['line'];

$sql = "SELECT   * FROM railline_events WHERE event_id = '$thisEvent_id'";
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
	$thisLine = MYSQL_RESULT($result,$i,"line");
	$thisDate = MYSQL_RESULT($result,$i,"date");
	$thisApprox = MYSQL_RESULT($result,$i,"dateAccuracy");
	$thisDisplay = MYSQL_RESULT($result,$i,"display");
	$thisStartLocation = MYSQL_RESULT($result,$i,"start_location");
	$thisEndLocation = MYSQL_RESULT($result,$i,"end_location");
	$thisTracks = MYSQL_RESULT($result,$i,"tracks");
	$thisDescription = MYSQL_RESULT($result,$i,"description");
	$thisSafeworking = MYSQL_RESULT($result,$i,"safeworking");
	$thisSafeworkingWhy = MYSQL_RESULT($result,$i,"safeworking_why");
	$thisSafeworkingMiddle = MYSQL_RESULT($result,$i,"safeworking_middle");
	$thisGauge = MYSQL_RESULT($result,$i,"gauge");

}
?>
<fieldset>
<form name="railline_eventsUpdateForm" method="POST" action="updateRaillineEvents.php">
<input type="hidden" name="thisLinkField" value="<?php echo $thisLink; ?>">
<input type="hidden" name="thisEvent_idField" value="<?php echo $thisEvent_id; ?>">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Event ID :  </b> </td>
		<td><?php echo $thisEvent_id; ?></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Line :  </b> </td>
		<td> <select name="thisLineField">
<?php drawLineNameSelectFields($thisLine); ?>	
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
		<td align="right"> <b> Display :  </b> </td>
		<td> <select name="thisDisplayField">
		<option <?php if ($thisDisplay == 'yes'){echo selected;} ?> value="Yes">Yes</option>
		<option <?php if ($thisDisplay == 'hide'){echo selected;} ?> value="Hide">Hide</option></select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right">EITHER</td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> <input type="text" name="thisDescriptionField" id="thisDescriptionField" size="50" value="<?php echo $thisDescription; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right">OR</td>
	</tr>

	<!--Start Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Start Location:  </b> </td>
		<td> <select name="thisStart_locationField">
<?php drawLocationNameSelectFields($thisStartLocation)	; ?>		
		</select></td>
	</tr>
	
	<!--End Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> End Location:  </b> </td>
		<td> <select name="thisEnd_locationField">
<?php drawLocationNameSelectFields($thisEndLocation)	; ?>		
		</select></td>	
	</tr>	
	
	<tr valign="top" height="20">
		<td align="right"> <b> Tracks :  </b> </td>
		<td> <input type="text" name="thisTracksField" size="20" value="<?php echo $thisTracks; ?>">  </td> 
	</tr>
	
	<!--Safeworking Type field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Safeworking :  </b> </td>
		<td> <select name="thisSafeworkingField">
	<?php drawSafeworkingNameSelectFields($thisSafeworking); ?>
		</select></td>
	</tr>

	<!--Middle Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Middle Location (optional) :  </b> </td>
		<td> <select name="thisSafeworkingMiddleField">
		<?php drawLocationNameSelectFields($thisSafeworkingMiddle)	; ?>		
		</select></td>	
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Reason For Above? :  </b> </td>
		<td><select name="thisSafeworkingWhyField">
<?php drawSafeworkingWhyFields($thisSafeworkingWhy); ?>
		</select></td>
	</tr>

	<tr valign="top" height="20">
		<td align="right"> <b> Gauge :  </b> </td>
		<td><select name="thisGaugeField">
<?php drawGaugeFields($thisGaugeWhy); ?>
		</select></td>
	</tr>

</table>

<input type="submit" name="submitUpdateRailline_eventsForm" value="Update Rail Line Events">

</form>
</fieldset>

<?php
include_once("common/footer.php");
?>