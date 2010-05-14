<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<h2>Enter Location_types</h2>
<form name="location_typesEnterForm" method="POST" action="insertNewLocation_types.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Type_id :  </b> </td>
		<td> <input type="text" name="thisType_idField" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Basic :  </b> </td>
		<td> <input type="text" name="thisBasicField" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> More :  </b> </td>
		<td> <input type="text" name="thisMoreField" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Specific :  </b> </td>
		<td> <input type="text" name="thisSpecificField" size="20" value="">  </td> 
	</tr>
</table>

<input type="submit" name="submitEnterLocation_typesForm" value="Enter Location_types">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>