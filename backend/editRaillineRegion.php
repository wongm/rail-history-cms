<?php
$pageTitle = 'Update Railline Regions';
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisLinkzor_id = $_REQUEST['id'];

$sql = "SELECT   * FROM railline_region WHERE linkzor_id = '$thisLinkzor_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);

if ($numberOfRows>0) {

	$i=0;
	$thisLinkzor_id = MYSQL_RESULT($result,$i,"linkzor_id");
	$thisLine_id = MYSQL_RESULT($result,$i,"line_id");
	$thisArticle_id = MYSQL_RESULT($result,$i,"article_id");
	$thisContent = MYSQL_RESULT($result,$i,"content");
}
?>
<a href="listRaillineRegion.php">Return</a>
<form name="railline_regionUpdateForm" method="POST" action="updateRaillineRegion.php">
<fieldset>
<input type="hidden" name="thisLinkzor_idField" value="<? echo $thisLinkzor_id; ?>">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Line :  </b> </td>
		<td width="220"> <select name="thisLine_idField" id="thisLine_idField">
<? drawLineNameSelectFields($thisLine_id); ?>	
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Region link :  </b> </td>
		<td width="220"> <select name="thisArticle_idField" id="thisLine_idField">
<? drawRegionSelectFields($thisArticle_id); ?>	
		</select></td>
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Content :  </b> </td>
		<td>
		<form>
		<script type="text/javascript" src="js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisContentField" id="thisContentField" wrap="VIRTUAL" cols="100" rows="10"><? echo $thisContent; ?></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisContentField');</script>
		</form>
		</td>
	</tr>
</table>

<input type="submit" name="submitUpdateRaillineRegionForm" value="Update region mapping">
</fieldset>
</form>

<?php
include_once("common/footer.php");
?>