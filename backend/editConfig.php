<?php
$pageTitle = 'Update Config Variable';
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisName = $_REQUEST['nameField']
?>
<?php
$sql = "SELECT   * FROM config WHERE name = '$thisName'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisName = MYSQL_RESULT($result,$i,"name");
	$thisValue = stripslashes(MYSQL_RESULT($result,$i,"value"));

}
?>
<form name="configUpdateForm" method="POST" action="updateConfig.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="20" value="<? echo $thisName; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Value :  </b> </td>
		<td>
		<form>
		<script type="text/javascript" src="js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisValueField" id="thisValueField" wrap="VIRTUAL" cols="80" rows="30"><? echo $thisValue; ?></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisValueField');</script>
		</form>
		</td>
	</tr>
</table>

<input type="submit" name="submitUpdateConfigForm" value="Update Config">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>