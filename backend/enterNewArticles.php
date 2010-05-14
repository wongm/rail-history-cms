<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<h3>Enter Articles</h3><hr>
<form name="articlesEnterForm" method="POST" action="insertNewArticles.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Link :  </b> </td>
		<td> <input type="text" name="thisLinkField" size="40" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Title :  </b> </td>
		<td> <input type="text" name="thisTitleField" size="40" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> 
		<textarea name="thisDescriptionField" id="thisDescriptionField" wrap="VIRTUAL" cols="80" rows="5"><? echo $thisDescription; ?></textarea>
		</td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Line (optional):  </b> </td>
		<td> <select name="thisLineField">
			<option value="-1"  >[REGION]</option>
<? drawLineNameSelectFields()	; ?>		
		</select></td>
	</tr>
    
    <tr valign="top" height="20">
		<td align="right"> <b> Photos :  </b> </td>
		<td> <input type="text" name="thisPhotosField" size="40" value="">  </td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Content :  </b> </td>
		<td>
		<form>
		<script type="text/javascript" src="js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisContentField" id="thisContentField" wrap="VIRTUAL" cols="80" rows="30"><? echo $thisContent; ?></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisContentField');</script>
		</form>
		</td>
	</tr>
	
</table>

<input type="submit" name="submitEnterArticlesForm" value="Enter Articles">
<input type="reset" name="resetForm" value="Clear Form">

</form>

<?php
include_once("common/footer.php");
?>