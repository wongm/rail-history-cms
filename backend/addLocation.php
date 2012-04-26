<?php

$thisLineLink = $_REQUEST['line'];
$pageTitle = 'Insert New Location';
include_once("common/dbConnection.php");
include_once("common/header.php");

drawEditLineHeadbar($thisLineLink);
?>
<form name="locationsEnterForm" method="POST" action="insertNewLocations.php">
<fieldset><legend>General</legend>
<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="30" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Line :  </b> </td>
		<td> <select name="thisLineField">
<? drawLineNameSelectFields($thisLineLink); ?>	
		</select></td>
	</tr>
    <tr valign="top" height="20">
		<td align="right"> <b> Km :  </b> </td>
		<td> <input type="text" name="thisKmField" size="30">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> KM Accuracy :  </b> </td>
		<td><select name="thisKmAccuracyField">
<? drawApproxDistanceFields(); ?>
		</select></td>
	</tr>
    
	<tr valign="top" height="20">
		<td align="right"> <b> Location Type :  </b> </td>
		<td> <select name="thisTypeField">
<? drawLocationTypeFields(''); ?>	
		</select></td>
	</tr>
</table>
<br>
<input type="submit" name="submitAndEdit" value="Insert and Edit">&nbsp;&nbsp;
<input type="submit" name="submitAndNew" value="Insert and New">&nbsp;&nbsp;
<input type="submit" name="submit" value="Insert">

</form>
</fieldset>

<?php
include_once("common/footer.php");
?>