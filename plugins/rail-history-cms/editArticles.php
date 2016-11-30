<?php

$pageTitle = 'Update Articles';
include_once("common/dbConnection.php");
include_once("common/header.php");

$thisId = $_REQUEST['id'];
$thisLink = $_REQUEST['link'];

if ($thisLink != '')
{
	$sql = "SELECT   * FROM articles WHERE link = '$thisLink'";
}
else
{
	$sql = "SELECT   * FROM articles WHERE article_id = '$thisId'";
}
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisId = MYSQL_RESULT($result,$i,"article_id");
	$thisLink = stripslashes(MYSQL_RESULT($result,$i,"link"));
	$thisTitle = stripslashes(MYSQL_RESULT($result,$i,"title"));
	$thisDescription = stripslashes(MYSQL_RESULT($result,$i,"description"));
	$thisContent = stripslashes(MYSQL_RESULT($result,$i,"content"));
	$thisPhotos = stripslashes(MYSQL_RESULT($result,$i,"photos"));
	$thisLine = stripslashes(MYSQL_RESULT($result,$i,"line_id"));
	$thisCaption = stripslashes(MYSQL_RESULT($result,$i,"caption"));

}
?>
<fieldset id="general"><legend>Update Article</legend>
<form name="articlesUpdateForm" method="POST" action="updateArticles.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
<input type="hidden" name="thisIdField" value="<?php echo $thisId; ?>">
	<tr valign="top" height="20">
		<td align="right"> <b> Id :  </b> </td>
		<td><?php echo $thisId; ?></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Title :  </b> </td>
		<td> <input type="text" name="thisTitleField" size="40" value="<?php echo $thisTitle; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Link :  </b> </td>
		<td> <input type="text" name="thisLinkField" size="40" value="<?php echo $thisLink; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> 
		<textarea name="thisDescriptionField" id="thisDescriptionField" wrap="VIRTUAL" cols="80" rows="5"><?php echo $thisDescription; ?></textarea>
		</td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Line (optional):  </b> </td>
		<td> <select name="thisLineField">
<?php drawLineNameSelectFields($thisLine); ?>	
<option value="-1" <?php if ($thisLine == -1) { echo 'selected'; } ?>>[REGION] (region<>line map)</option>	
   	 	</select></td>
   	 </tr>
    
    <tr valign="top" height="20">
		<td align="right"> <b> Gallery folder :  </b> </td>
		<td> 
		<input type="text" name="thisPhotosField" size="40" value="<?php echo $thisPhotos?>">  <br>
		<small>Eg: 'LINE-NAME/LOCATION-NAME'</small> 
		</td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Lead image caption :  </b> </td>
		<td> 
		<input type="text" name="thisCaptionField" size="83" value="<?php echo $thisCaption; ?>"><br>
		<small>For image at top of article page, file needs to be at "/images/header-LINELINK-<?php echo $thisLink; ?>.jpg"</small> 
		</td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Content :  </b> </td>
		<td>
		<form>
		<script type="text/javascript" src="js/js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisContentField" id="thisContentField" wrap="VIRTUAL" cols="100" rows="30"><?php echo $thisContent; ?></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisContentField');</script>
		</form>
		</td>
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"><b>Show updated :  </b></td>
		<td><label><input type="checkbox" checked="yes" name="flag" /> <small>(check to show on main page as recently updated)</small></label></td> 
	</tr>
</table>

<input type="submit" name="submitUpdateArticlesForm" value="Update Articles">

</form>
</fieldset>

<?php drawObjectSources('article', $thisId); ?>

<?php
include_once("common/footer.php");
?>