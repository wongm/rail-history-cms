<?php
$pageTitle = 'Enter Location Events';
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<form name="location_eventsEnterForm" method="POST" action="insertNewLocation_events.php">
<fieldset>
<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<!--Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Location :  </b> </td>
		<td> <select name="thisLocationField">
<?	drawLocationNameSelectFields(); ?>
		</select></td>
	</tr>
<? drawAddNewLocationEvent(); ?>
</table>

<input type="submit" name="submitEnterLocation_eventsForm" value="Enter Location Event">
</fieldset>
</form>

<?php
include_once("common/footer.php");
?>