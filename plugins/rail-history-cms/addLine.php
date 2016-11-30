<?php
$pageTitle = 'Insert New Line';
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<fieldset><legend>General</legend>
<form name="raillinesUpdateForm" method="POST" action="insertNewRaillines.php">
<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="30" value="">  </td> 
	</tr>	
	<tr valign="top" height="20">
		<td align="right"> <b> Link :  </b> </td>
		<td> <input type="text" name="thisLinkField" size="30" value="">  </td> 
	</tr>	
	<!--Start Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Start Location:  </b> </td>
		<td> <select name="thisStartlocationField">
<?php drawLocationNameSelectFields($thisStartlocation)	; ?>		
		</select></td>
	</tr>
	
	<!--End Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> End Location:  </b> </td>
		<td> <select name="thisEndlocationField">
<?php drawLocationNameSelectFields($thisEndlocation)	; ?>		
		</select></td>
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Opened :  </b> </td>
		<td> <input type="text" name="thisOpenedField" size="30" value="<?php echo $thisOpened; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="'9999-01-01' for still open">Closed</abbr> :  </b> </td>
		<td> <input type="text" name="thisClosedField" size="30" value="<?php echo $thisClosed; ?>">  </td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="CSVs - years to show in lineguide track diagram">Track Years</abbr> :  </b> </td>
		<td> <input type="text" name="thisTrackyearsField" size="30" value="<?php echo $thisTrackyears; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="'9999' to default to now, '0' to show all">(Default)</abbr>    </b> </td>
		<td> <input type="text" name="thisTrackdefaultField" size="30" value="<?php echo $thisTrackdefault; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="CSVs - years to show in lineguide safeworking diagram">Safeworking Years</abbr> :  </b> </td>
		<td> <input type="text" name="thisSafeworkingyearsField" size="30" value="<?php echo $thisSafeworkingyears; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="'9999' to default to now, '0' to show all">(Default)</abbr>    </b> </td>
		<td> <input type="text" name="thisSafeworkingdefaultField" size="30" value="<?php echo $thisSafeworkingdefault; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Tabs to show on lineguide page">Display</abbr> :  </b> </td>
		<td><select name="thisTodisplayField">
<?php drawLineDisplayTypeFields($thisTodisplay); ?>
		</select></td>
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> <textarea name="thisDescriptionField" wrap="VIRTUAL" cols="80" rows="8"><?php echo $thisDescription; ?></textarea></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Credits :  </b> </td>
		<td> <textarea name="thisCreditsField" wrap="VIRTUAL" cols="80" rows="8"><?php echo $thisCredits; ?></textarea></td> 
	</tr>
</table>

<input type="submit" name="submitUpdateRaillinesForm" value="Insert Rail Line">
</form></fieldset><br><br>



<?php
include_once("common/footer.php");
?>