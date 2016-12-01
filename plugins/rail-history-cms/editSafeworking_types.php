<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisSafeworking_id = $_REQUEST['safeworking_idField']
?>
<?php
$sql = "SELECT   * FROM safeworking_types WHERE safeworking_id = '$thisSafeworking_id'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisSafeworking_id = $result[$i]["safeworking_id"];
	$thisName = $result[$i]["name"];
	$thisLink = $result[$i]["link"];
	$thisDetails = stripslashes($result[$i]["details"]);

}
?>

<h2>Update Safeworking Types</h2>
<form name="safeworking_typesUpdateForm" method="POST" action="updateSafeworking_types.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Safeworking_id :  </b> </td>
		<td> <input type="text" name="thisSafeworking_idField" size="20" value="<?php echo $thisSafeworking_id; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="40" value="<?php echo $thisName; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Link :  </b> </td>
		<td> <input type="text" name="thisLinkField" size="40" value="<?php echo $thisLink; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Details :  </b> </td>
		<td> <form>
		<script type="text/javascript" src="js/js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisDetailsField"" id="thisDetailsField"" wrap="VIRTUAL" cols="100" rows="30"><?php echo stripslashes($thisDetails); ?></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisDescriptionField');</script>
		</form></td> 
	</tr>
</table>

<input type="submit" name="submitUpdateSafeworking_typesForm" value="Update Safeworking Types">

</form>

<?php
include_once("common/footer.php");
?>