<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<form name="railline_regionEnterForm" method="POST" action="insertNewRaillineRegion.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Line :  </b> </td>
		<td> <select name="thisLine_idField" id="thisLine_idField">
<? drawLineNameSelectFields(); ?>	
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Region link :  </b> </td>
		<td> <select name="thisArticle_idField" id="thisLine_idField">
<? drawRegionSelectFields(); ?>	
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Content :  </b> </td>
		<td>
		<form>
		<script type="text/javascript" src="js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisContentField" id="thisContentField" wrap="VIRTUAL" cols="100" rows="30"><? echo $thisContent; ?></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisContentField');</script>
		</form>
		</td>
	</tr>
</table>

<input type="submit" name="submitEnterRaillineRegionForm" value="Enter RaillineRegion">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>