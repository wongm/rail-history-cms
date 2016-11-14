<?php
$pageTitle = 'Enter Sources';
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<form name="sourcesEnterForm" method="POST" action="insertNewSources.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Source_id :  </b> </td>
		<td> <input type="text" name="thisSource_idField" size="40" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Short :  </b> </td>
		<td> <input type="text" name="thisShortField" size="40" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="40" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Details :  </b> </td>
		<td> <textarea name="thisDetailsField" wrap="VIRTUAL" cols="80" rows="8"><?php echo $thisDescription; ?></textarea></td> 
	</tr>
</table>

<input type="submit" name="submitEnterSourcesForm" value="Enter Sources">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>