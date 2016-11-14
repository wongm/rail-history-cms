<?php
$pageTitle = 'Enter Line Events';
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<fieldset><legend>Add Events</legend>
<form name="railline_eventsEnterForm" method="POST" action="insertNewRailline_events.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Line :  </b> </td>
		<td> <select name="thisLineField">
<?php drawLineNameSelectFields(); ?>
    	</select></td>
    </tr>
<?php drawAddNewRailineEvent(); ?>
</table>

<input type="submit" name="submitEnterRailline_eventsForm" value="Enter New Rail Line Event">
</form></fieldset>

<?php
include_once("common/footer.php");
?>