<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<h2>Enter Safeworking_types</h2>
<form name="safeworking_typesEnterForm" method="POST" action="insertNewSafeworking_types.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Safeworking_id :  </b> </td>
		<td> <input type="text" name="thisSafeworking_idField" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Link :  </b> </td>
		<td> <input type="text" name="thisLinkField" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Details :  </b> </td>
		<td> <input type="text" name="thisDetailsField" size="20" value="">  </td> 
	</tr>
</table>

<input type="submit" name="submitEnterSafeworking_typesForm" value="Enter Safeworking_types">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>