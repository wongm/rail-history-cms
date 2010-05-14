<?php

$thisLocation_id = $_REQUEST['location'];
$thisLine = $_REQUEST['line'];
$pageTitle = 'Insert New Location';
include_once("common/dbConnection.php");
include_once("common/header.php");	?>

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
<? drawLineNameSelectFields($thisLine); ?>	
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
		<td align="right"> <b> Tracks :  </b> </td>
		<td><select name="thisTracksField">
		<option <? if ($thisTracks == '1'){echo selected;} ?> value="1">Normal</option>
		<option <? if ($thisTracks == '0'){echo selected;} ?> value="0">No Gap (Top)</option>
		<option <? if ($thisTracks == '9'){echo selected;} ?> value="9">No Gap (Bottom)</option>
		<option <? if ($thisTracks == '2'){echo selected;} ?> value="2">2</option>
		</select></td> 
		<!--<input type="text" name="thisTracksField" size="30" value="<? echo $thisTracks; ?>"> -->
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Location Type :  </b> </td>
		<td> <select name="thisTypeField">
<? drawLocationTypeFields(''); ?>	
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="string for the images to be used for the lineguide diagam">Image</abbr> :  </b> </td>
		<td> <input type="text" name="thisImageField" size="30" value="<? echo $thisImage; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Can have text name of line; or line_id; if a junction">Url</abbr> :  </b> </td>
		<td> <input type="text" name="thisUrlField" size="30" value="<? echo $thisUrl; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="comma separated year values, for special cases where a 'full' diagram needs to be displayed on a page, not just the little lineguide ones">Diagrams</abbr> :  </b> </td>
		<td> <input type="text" name="thisDiagramsField" size="30" value="<? echo $thisDiagrams; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Display :  </b> </td>
		<td><select name="thisDisplayField">
<? drawLocationDisplayTypeFields(); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> <textarea name="thisDescriptionField" wrap="VIRTUAL" cols="80" rows="8"></textarea></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Credits :  </b> </td>
		<td> <textarea name="thisCreditsField" wrap="VIRTUAL" cols="80" rows="8"></textarea></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Open :  </b> </td>
		<td> <input type="text" name="thisOpenField" size="30">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Open Accuracy :  </b> </td>
		<td><select name="thisOpenAccuracyField">
<? drawApproxTimeFields(); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Close :  </b> </td>
		<td> <input type="text" name="thisCloseField" size="30">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Close Accuracy :  </b> </td>
		<td><select name="thisCloseAccuracyField">
<? drawApproxTimeFields(); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Co-ordinates :  </b> </td>
		<td> <input type="text" name="thisCoordsField" size="30" value="<? echo $thisCoOrds; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Photos?:  </b> </td>
		<td> <input type="text" name="thisPhotosField" size="30">  </td> 
	</tr>
</table>

<input type="submit" name="submitEnterLocationsForm" value="Insert Location"></form>
</fieldset>




<?php
include_once("common/footer.php");
?>