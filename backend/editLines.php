<?php
include_once("common/dbConnection.php");

$lineLink = $_REQUEST['line'];
$sql = "SELECT * FROM raillines WHERE link = '$lineLink'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));

$pageTitle = 'Update '.$thisName.' Line Details';
include_once("common/header.php");

if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) 
{
	$i = 0;
	$thisLineId = stripslashes(MYSQL_RESULT($result,$i,"line_id"));
	
	$thisLink = stripslashes(MYSQL_RESULT($result,$i,"link"));
	$thisOrder = stripslashes(MYSQL_RESULT($result,$i,"order"));
	$thisStartlocation = stripslashes(MYSQL_RESULT($result,$i,"startlocation"));
	$thisEndlocation = stripslashes(MYSQL_RESULT($result,$i,"endlocation"));
	$thisOpened = stripslashes(MYSQL_RESULT($result,$i,"opened"));
	$thisClosed = stripslashes(MYSQL_RESULT($result,$i,"closed"));
	$thisKmstart = stripslashes(MYSQL_RESULT($result,$i,"kmstart"));
	$thisKmend = stripslashes(MYSQL_RESULT($result,$i,"kmend"));
	$thisDescription = stripslashes(MYSQL_RESULT($result,$i,"description"));
	$thisCredits = stripslashes(MYSQL_RESULT($result,$i,"credits"));
	$thisPhotos = stripslashes(MYSQL_RESULT($result,$i,"photos"));
	$thisTrackyears = stripslashes(MYSQL_RESULT($result,$i,"trackyears"));
    $thisTrackdefault = stripslashes(MYSQL_RESULT($result,$i,"trackdefault"));
    $thisTracksubpage = stripslashes(MYSQL_RESULT($result,$i,"tracksubpage"));
    $thisSafeworkingyears = stripslashes(MYSQL_RESULT($result,$i,"safeworkingyears"));
    $thisSafeworkingdefault = stripslashes(MYSQL_RESULT($result,$i,"safeworkingdefault"));
    $thisTodisplay = stripslashes(MYSQL_RESULT($result,$i,"todisplay"));
    $thisDiagramtabs = stripslashes(MYSQL_RESULT($result,$i,"trackdiagramtabs"));
    $thisImageCaption = stripslashes(MYSQL_RESULT($result,$i,"imagecaption"));
    $thisSafeworkingDiagramNote = stripslashes(MYSQL_RESULT($result,$i,"safeworkingdiagramnote"));
    $thisTrackDiagramNote = stripslashes(MYSQL_RESULT($result,$i,"trackdiagramnote"));

    drawEditLineHeadbar($lineLink);
?>
<fieldset id="general"><legend>Line</legend>
<form name="raillinesUpdateForm" method="POST" action="updateRaillines.php">
<input type="hidden" name="thisLine_idField" value="<?php echo $thisLineId; ?>">
<table cellspacing="5" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Line ID :  </b> </td>
		<td><?php echo $thisLineId; ?></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="30" value="<?php echo $thisName; ?>">  </td> 
	</tr>	
	<tr valign="top" height="20">
		<td align="right"> <b> Link :  </b> </td>
		<td> <input type="text" name="thisLinkField" size="30" value="<?php echo $thisLink; ?>">  </td> 
	</tr>	
	<tr valign="top" height="20">
		<td align="right"> <b> Opened :  </b> </td>
		<td> <input type="text" name="thisOpenedField" size="30" value="<?php echo $thisOpened; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="'9999-01-01' for still open">Closed</abbr> :  </b> </td>
		<td> <input type="text" name="thisClosedField" size="30" value="<?php echo $thisClosed; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Order :  </b> </td>
		<td> <input type="text" name="thisOrderField" size="5" value="<?php echo $thisOrder; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"><b>Show updated :  </b></td>
		<td><label><input type="checkbox" checked="yes" name="flag" /> <small>(check to show on main page as recently updated)</small></label></td> 
	</tr>
	
<tr valign="top" height="20"><td align="left" colspan=2><hr>Display</td></tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Tabs to show on lineguide page">Display tabs</abbr> :  </b> </td>
		<td>
<?php drawLocationTabDisplayFields($thisTodisplay, $thisLink); ?>
		</td>
	</tr>
	
<tr valign="top" height="20"><td align="left" colspan=2><hr>Track diagrams</td></tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Note for the top of lineguide track diagram pages">Track note</abbr> :    </b> </td>
		<td> 
		<textarea name="thisTrackDiagramNoteField" wrap="VIRTUAL" cols="100" rows="4"><?php echo $thisTrackDiagramNote; ?></textarea>
		</td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> EITHER </td><td>dynamically generated from the location database</td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="CSVs - years to show in lineguide track diagram">Track years</abbr> :  </b> </td>
		<td> <input type="text" name="thisTrackyearsField" size="100" value="<?php echo $thisTrackyears; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="'9999' to default to now, '0' to show all">(Default)</abbr> :   </b> </td>
		<td> <input type="text" name="thisTrackdefaultField" size="100" value="<?php echo $thisTrackdefault; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Use a dash to separate bounds, and a semicolon to seprate pages">Track subpages</abbr> :   </b> </td>
		<td> <input type="text" name="thisTracksubpageField" size="100" value="<?php echo $thisTracksubpage; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> OR </td><td>a series of images shown using tabs</td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Single diagrams :  </b></td>
		<td> <input type="text" name="thisDiagramTabsField" size="100" value="<?php echo $thisDiagramtabs; ?>"> <br>
		<small>CSV's years for a separate diagrams subpage. (Images need to be titled '/t/<?php echo $thisLink; ?>-[YEAR].gif') </small></td> 
	</tr>

<tr valign="top" height="20"><td align="left" colspan=2><hr>Safeworking diagrams</td></tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Note for the top of lineguide safeworking diagram pages">Safeworking note</abbr> :    </b> </td>
		<td> 
		<textarea name="thisSafeworkingDiagramNoteField" wrap="VIRTUAL" cols="100" rows="4"><?php echo $thisSafeworkingDiagramNote; ?></textarea>
		</td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="CSVs - years to show in lineguide safeworking diagram">Safeworking years</abbr> :  </b> </td>
		<td> <input type="text" name="thisSafeworkingyearsField" size="100" value="<?php echo $thisSafeworkingyears; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="'9999' to default to now, '0' to show all">(Default)</abbr> :   </b> </td>
		<td> <input type="text" name="thisSafeworkingdefaultField" size="100" value="<?php echo $thisSafeworkingdefault; ?>">  </td> 
	</tr>
	
<tr valign="top" height="20"><td align="left" colspan=2><hr>Front page</td></tr>		
		
	<tr valign="top" height="20">
		<td align="right"> <b> Lead image caption :    </b> </td>
		<td> <input type="text" name="thisImageCaptionField" size="100" value="<?php echo $thisImageCaption; ?>"><br>
		<small>For image at top of lineguide page, File needs to be at "/images/header-<?php echo $thisLink; ?>.jpg"</small> 
		</td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Gallery folder :    </b> </td>
		<td> <input type="text" name="thisPhotosField" size="100" value="<?php echo $thisPhotos; ?>">  <br>
		<small>Eg: 'LINE-NAME/LOCATION-NAME'</small> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> <form>
		<script type="text/javascript" src="js_quicktags.js"></script>
		<script type="text/javascript">edToolbar();</script>
		<textarea name="thisDescriptionField" id="thisDescriptionField" wrap="VIRTUAL" cols="100" rows="30"><?php echo $thisDescription; ?></textarea>
		<script type="text/javascript">var edCanvas = document.getElementById('thisDescriptionField');</script>
		</form></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Credits :  </b> </td>
		<td> <textarea name="thisCreditsField" wrap="VIRTUAL" cols="100" rows="8"><?php echo $thisCredits; ?></textarea></td> 
	</tr>
</table>

<input type="submit" name="submitUpdateRaillinesForm" value="Update Rail Line">
</form></fieldset><br>

<?php drawObjectSources('railline', $thisLineId); ?>

<?php
}	//end else if
include_once("common/footer.php");
?>