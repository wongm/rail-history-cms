<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<h3>Enter Config</h3><hr>
<form name="configEnterForm" method="POST" action="insertNewConfig.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="40" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Value :  </b> </td>
		<td>
		<form>
		<script type="text/javascript" src="js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisValueField" id="thisValueField" wrap="VIRTUAL" cols="80" rows="30"></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisValueField');</script>
		</form>
		</td>
	</tr>
</table>

<input type="submit" name="submitEnterConfigForm" value="Enter Config">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>