<?php

/*
	contains:
	
	drawRaillineTypeFields($type)
	
	<? drawLineNameSelectFields($currentLineId); ?>
	<? drawLocationNameSelectFields($currentLocationId)	; ?>
	<? drawSourcesSelectFields($currentSourceId); ?>
	<? drawApproxDistanceFields($thisKmAccuracy); ?>
	<? drawApproxTimeFields($thisApprox); ?>
	<? drawLocationDisplayTypeFields($thisDisplay); ?>
	<? drawLineDisplayTypeFields($thisDisplay); ?>
	<? drawLocationTypeFields($thisType); ?>
	<? drawLocationLxEventFields($thisDetails); ?>
	<? drawAddNewRailineEvent(); ?>
	<? drawAddNewLocationEvent(); ?>
	<? drawSafeworkingWhyFields($thisSafeworkingWhy); ?>
	<? drawSafeworkingNameSelectFields($thisSafeworking); ?>
	<? drawGaugeFields($thisGauge); ?>
	
	
	
	drawObjectSources('location', $thisLocationId);
	drawRegionSelectFields($currentRegionId)
*/

/// how often to repeat headers in a table
DEFINE('NUM_REPEAT_HEADER', 15);

function drawSourceHelpText()
{
	echo "SHORT is used on the sources page<br>";
	echo "NAME is used on location, lineguide, etc pages<br>";
	echo "Formatted as <b>TITLE - AUTHOR</b><br>";
	echo "DETAILS is used on the sources page<br>";
	echo "For books format is <b>TITLE. AUTHOR (YEAR) PUBLISHER. DETAILS. ISBN</b><hr>";
}

function drawRaillineTypeFields($type)
{
	?>
		<option <? if ($type == 'main'){echo selected;} ?> value="main">Main</option>
		<option <? if ($type == 'branch'){echo selected;} ?> value="branch">Branch</option>
<?
}	// end function	

function drawHeadbar($thisKm, $thisLine)
{
	// next and forward linkzor
	$sqlBack = "SELECT * FROM locations l, locations_raillines lr 
	WHERE l.location_id = lr.location_id AND `km` < '".$thisKm."' AND line_id = '".$thisLine."' ORDER BY km DESC";
	$resultBack = MYSQL_QUERY($sqlBack);
	$sqlNext = "SELECT * FROM locations l, locations_raillines lr 
	WHERE l.location_id = lr.location_id AND `km` > '".$thisKm."' AND line_id = '".$thisLine."' ORDER BY km ASC";
	$resultNext = MYSQL_QUERY($sqlNext);
	
	if (MYSQL_NUM_ROWS($resultBack) > '0')	
	{
		$Name = stripslashes(MYSQL_RESULT($resultBack,0,"name"));
		$id = stripslashes(MYSQL_RESULT($resultBack,0,"l.location_id"));
		$back = '<a href="./editLocations.php?location='.$id.'">&laquo; '.$Name.'</a>'; 
	}	
	if (MYSQL_NUM_ROWS($resultNext) > '0')	
	{
		$Name = stripslashes(MYSQL_RESULT($resultNext,0,"name"));
		$id = stripslashes(MYSQL_RESULT($resultNext,0,"l.location_id"));
		$next = '<a href="./editLocations.php?location='.$id.'">'.$Name.' &raquo;</a>'; 
	}
	
	if ($back != '' OR $next != '')
	{
	?>
<!-- next / back links -->
<div class="pagelist">
	<div class="prev"><? echo $back; ?></div>
	<div class="next"><? echo $next; ?></div>
</div>
<?
	}
}	// end function

function drawLocationTabDisplayFields($tabdisplay, $lineName)
{
	?>
<label><input type="checkbox" <? if (substr($tabdisplay, 4, 1) == 1) { echo 'checked="yes"'; } ?> name="showTrack" /> Track diagram
	<small>(exact type depends on options selected below)</small></label><br>
<label><input type="checkbox" <? if (substr($tabdisplay, 3, 1) == 1) { echo 'checked="yes"'; } ?> name="showSafeworking" /> Safeworking diagram</label><br>
<label><input type="checkbox" <? if (substr($tabdisplay, 2, 1) == 1) { echo 'checked="yes"'; } ?> name="showEvents" /> Events</label><br>
<label><input type="checkbox" <? if (substr($tabdisplay, 1, 1) == 1) { echo 'checked="yes"'; } ?> name="showLocations" /> Locations</label><br>
<label><input type="checkbox" <? if (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/kml-'.$lineName.'.kml')) { echo 'checked="yes"'; } ?> /> Google map
	<small>(user can't change, requires existance of "/images/kml/kml-<?=$lineName?>.kml")</small></label><br>
<label><input type="checkbox" <? if ($tabdisplay == 'hide' ) { echo 'checked="yes"'; } ?> name="hideAll" /> Hide line and all locations</label>
<?
}

function drawGaugeFields($thisGauge)
{
	?>
		<option <? if ($thisGauge == 'bg'){echo selected;} ?> value="bg">BG</option>
		<option <? if ($thisGauge == 'sg'){echo selected;} ?> value="sg">SG</option>
		<option <? if ($thisGauge == 'dg'){echo selected;} ?> value="dg">DG</option>
		<option <? if ($thisGauge == 'ng'){echo selected;} ?> value="ng">NG</option>
		<option value="bg">BG</option>
<?
}


function drawSafeworkingWhyFields($thisSafeworkingWhy)
{
	?>
		<option <? if ($thisSafeworkingWhy == 'replaced'){echo selected;} ?> value="replaced">New replaced old</option>
		<option <? if ($thisSafeworkingWhy == 'block'){echo selected;} ?> value="block">Block point opened</option>
		<option <? if ($thisSafeworkingWhy == 'station'){echo selected;} ?> value="station">Station opened</option>
		<option <? if ($thisSafeworkingWhy == 'closed'){echo selected;} ?> value="closed">Location closed</option>
		<option <? if ($thisSafeworkingWhy == 'singled'){echo selected;} ?> value="singled">Singled line (non-SW)</option>
		<option <? if ($thisSafeworkingWhy == 'opened'){echo selected;} ?> value="opened">Opened line (non-SW)</option>
		<option <? if ($thisSafeworkingWhy == 'downgrade'){echo selected;} ?> value="downgrade">Downgrade</option>
		<option <? if ($thisSafeworkingWhy == 'plain'){echo selected;} ?> value="plain">[Show plain]</option>
		<option value="replaced"></option>
<?
}

function drawSafeworkingNameSelectFields($thisSafeworking)
{
	$sql = "SELECT * FROM safeworking_types ORDER BY name ASC";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows==0) {  
	?>
	<option value="null" selected>None Found!</option>
	<?php
	}
	else if ($numberOfRows>0) 
	{
		echo '<option value="" selected>[NULL]</option>';
		
		for ($i = 0; $i<$numberOfRows; $i++)
		{
			$thisType = MYSQL_RESULT($result,$i,"link");
			$thisName = MYSQL_RESULT($result,$i,"name");
			
			if($thisType == $thisSafeworking)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
		?>
			<option <? echo $selected; ?> value="<? echo $thisType?>"><? echo $thisName.' ('.$thisType.')'?></option>
		<?
		} // end while loop
	}
}

function drawLocationLxEventFields($thisDetails)
{
?>
		<option selected value=""></option>
		<option <? if ($thisDetails == '8'){echo selected;} ?> value="8">Crossing</option>
		<option <? if ($thisDetails == '9'){echo selected;} ?> value="9">Flashing lights</option>
		<option <? if ($thisDetails == '10'){echo selected;} ?> value="10">Boom barriers</option>
		<option <? if ($thisDetails == '11'){echo selected;} ?> value="11">BB and BG</option>
		<option <? if ($thisDetails == '12'){echo selected;} ?> value="12">BG</option>
		<option <? if ($thisDetails == '13'){echo selected;} ?> value="13">Hand gates</option>
		<option <? if ($thisDetails == '14'){echo selected;} ?> value="14">CC</option>
		<option <? if ($thisDetails == '38'){echo selected;} ?> value="38">Interlocked gates</option>
		<option <? if ($thisDetails == '39'){echo selected;} ?> value="39">Wicket Gates</option>
		<option <? if ($thisDetails == '1'){echo selected;} ?> value="1">Underpass</option>
		<option <? if ($thisDetails == '2'){echo selected;} ?> value="2">Overpass</option>
		<option <? if ($thisDetails == '3'){echo selected;} ?> value="3">Subway</option>
		<option <? if ($thisDetails == '4'){echo selected;} ?> value="4">Footbridge</option>	
<?
}

function drawLineDisplayTypeFields($thisTodisplay)
{
	?>
		<option <? if ($thisTodisplay == 'both'){echo selected;} ?> value="both">All</option>
		<option <? if ($thisTodisplay == 'nosafeevent'){echo selected;} ?> value="nosafeevent">Diagram Only</option>
		<option <? if ($thisTodisplay == 'diagramonly'){echo selected;} ?> value="diagramonly">Diagram and Events Only</option>
		<option <? if ($thisTodisplay == 'safeworkingonly'){echo selected;} ?> value="safeworking">Safeworking and Events Only</option>
		<option <? if ($thisTodisplay == 'none'){echo selected;} ?> value="none">None</option>
		<option <? if ($thisTodisplay == 'hide'){echo selected;} ?> value="hide">Hide - Don't show any</option>
<?
}			
	

function drawLineNameSelectFields($currentLineId)
{
	$sql = "SELECT * FROM raillines";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows==0) {  
		?>
	<option value="" selected>None Found!</option>
	<?php
	}
	else if ($numberOfRows>0) 
	{
		echo '<option value="" selected>[NULL]</option>';
		
		for ($i = 0; $i<$numberOfRows; $i++)
		{
			$thisLoopLine_id = stripslashes(MYSQL_RESULT($result,$i,"line_id"));
			$thisLoopLine_link = stripslashes(MYSQL_RESULT($result,$i,"link"));
			$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));
			
			if($thisLoopLine_id == $currentLineId || $thisLoopLine_link == $currentLineId)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
		?>
		<option value="<? echo $thisLoopLine_id?>" <? echo $selected?> ><? echo $thisName?></option>
	<?	

		} 	// end while loop
	}		// end if
}			// end function
	
function drawLocationNameSelectFields($currentLocationId, $google=false)
{
	$sql = "SELECT * FROM locations ORDER BY name";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows==0) {  
		?>
		<option value="" selected>None Found!</option>
		<?php
	}
	else if ($numberOfRows>0) 
	{
		echo '<option value="" selected>[NULL]</option>';
		
		for ($i = 0; $i<$numberOfRows; $i++)
		{
			$thisName = stripSlashes(MYSQL_RESULT($result,$i,"name"));
			$thisLocation_id = MYSQL_RESULT($result,$i,"location_id");
			
			if ($google)
			{
				if (MYSQL_RESULT($result,$i,"long") != 0)
				{
					$thisName .= " [SET]";
				}
					
				$km = MYSQL_RESULT($result,$i,"km_old");
				$line = MYSQL_RESULT($result,$i,"line_old");
				$thisName .= " [$line / $km km]";
			}
			
			if ($currentLocationId == $thisLocation_id)
			{
				$selected = 'selected';
			}
			else
			{
				$selected = '';
			}
?>
		<option <?=$selected?> value="<?=$thisLocation_id?>"><?=$thisName?> (<?=$thisLocation_id?>)</option>
<?
		} // end while loop
	} //end if
}

function drawSourcesSelectFields($currentSourceId)
{
	$sql = "SELECT * FROM sources ORDER BY short ASC";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows==0) {  
	?>
		<option value="" selected>None Found!</option>
	<?php
	}
	else if ($numberOfRows>0) 
	{
		echo '<option value="" selected>[NULL]</option>';
		
		for ($i = 0; $i<$numberOfRows; $i++)
		{
			$thisSourceId = stripslashes(MYSQL_RESULT($result,$i,"source_id"));
			$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));
			
			if ($currentSourceId == $thisSourceId)
			{
				$selected = 'selected';
			}
			else
			{
				$selected = '';
			}
	?>
		<option <?=$selected?> value="<? echo $thisSourceId?>" ><? echo $thisName?></option>
	<?
		} // end while loop
	}
}	// end function


function drawApproxDistanceFields($thisKmAccuracy)
{
	?>
		<option <? if ($thisKmAccuracy == 'exact'){echo selected;} ?> value="exact">Exact</option>
		<option <? if ($thisKmAccuracy == 'approx'){echo selected;} ?> value="approx">Approx</option>
		<option <? if ($thisKmAccuracy == 'hide'){echo selected;} ?> value="hide">Hide</option>
<?
}	// end function	

function drawApproxTimeFields($thisApprox)
{
	?>
		<option selected value="exact">Exact</option>
		<option <? if ($thisApprox == 'approx'){echo selected;} ?> value="approx">Approx</option>
		<option <? if ($thisApprox == 'decade'){echo selected;} ?> value="decade">Decade</option>
		<option <? if ($thisApprox == 'year'){echo selected;} ?> value="year">Year Only</option>
		<option <? if ($thisApprox == 'month'){echo selected;} ?> value="month">Year and Month</option>
<?
}	// end function		

function drawLocationDisplayTypeFields($thisDisplay)
{
?>		
		<option <? if ($thisDisplay == 'both'){echo selected;} ?> value="both">Everywhere [Default]</option>
		<option <? if ($thisDisplay == 'map'){echo selected;} ?> value="map">Except Lineguide</option>
		<option <? if ($thisDisplay == 'tracks'){echo selected;} ?> value="tracks">Only Lineguide</option>
		<option <? if ($thisDisplay == 'none'){echo selected;} ?> value="none">Hide diagrams on own page</option>
		<option <? if ($thisDisplay == 'line'){echo selected;} ?> value="line">Depreciated (line linker)</option>
<?
}	// end function		

function drawLocationTypeFields($thisType)
{
	$sql = "SELECT * FROM location_types";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows==0)
	{  
?>
		<option value="" selected>None Found!</option>
<?php
	}
	else if ($numberOfRows>0) 
	{
		echo '<option value="" selected>[NULL]</option>';
		
		for ($i = 0; $i<$numberOfRows; $i++)
		{
			$thisType_id = stripslashes(MYSQL_RESULT($result,$i,"type_id"));
			$thisBasic = stripslashes(MYSQL_RESULT($result,$i,"basic"));
			$thisMore = stripslashes(MYSQL_RESULT($result,$i,"more"));
			$thisSpecific = stripslashes(MYSQL_RESULT($result,$i,"specific"));
			
			if($thisType_id == $thisType)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
?>
		<option value="<? echo $thisType_id?>" <? echo $selected?> ><? echo $thisBasic?> - <? echo $thisMore?> - <? echo $thisSpecific?></option>
<?
		} // end while loop
	}
}	// end function		





function drawAddNewRailineEvent()
{
	?>


	<tr valign="top" height="20">
		<td align="right"> <b> Date :  </b> </td>
		<td> <input type="text" name="thisDateField" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Approx? :  </b> </td>
		<td> <select name="thisApproxField">
<? drawApproxTimeFields(); ?> 
		</select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Display :  </b> </td>
		<td> <select name="thisDisplayField">
		<option <? if ($thisDisplay == 'yes'){echo selected;} ?> value="Yes">Yes</option>
		<option <? if ($thisDisplay == 'hide'){echo selected;} ?> value="Hide">Hide</option></select></td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right">EITHER</td>
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> <input type="text" name="thisDescriptionField" size="50" value="<? echo $thisDescription; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right">OR</td>
	</tr>
	
	<!--Start Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Start Location:  </b> </td>
		<td> <select name="thisStart_locationField">
<? drawLocationNameSelectFields()	; ?>	
		</select></td>
	</tr>
	
	<!--End Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> End Location:  </b> </td>
		<td> <select name="thisEnd_locationField">
<? drawLocationNameSelectFields()	; ?>	
		</select></td>
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> # of Tracks :  </b> </td>
		<td> <input type="text" name="thisTracksField" size="20" value="">  </td> 
	</tr>

	<!--Safeworking Type field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Safeworking :  </b> </td>
		<td> <select name="thisSafeworkingField">
		<option value=""></option>
	
	<?php
	$sql = "SELECT * FROM safeworking_types ORDER BY name ASC";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows==0) {  
	?>
	<option value="null" selected>None Found!</option>
	<?php
	}
	else if ($numberOfRows>0) 
	{
		
		for ($i = 0;$i<$numberOfRows; $i++)
		{
			$thisType = MYSQL_RESULT($result,$i,"link");
			$thisName = MYSQL_RESULT($result,$i,"name");
			
			if($thisType == $thisSafeworking)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
		?>
			<option <? echo $selected; ?> value="<? echo $thisType?>"><? echo $thisName.' ('.$thisType.')'?></option>
		<?
			
		} // end while loop
}?>
</select></td></tr>
	
	<!--Middle Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Middle Location (optional) :  </b> </td>
		<td> <select name="thisSafeworkingMiddleField">
<? drawLocationNameSelectFields()	; ?>	
		</select></td>
	</tr>
	
<tr valign="top" height="20">
	<td align="right"> <b> Reason For Above? :  </b> </td>
	<td><select name="thisSafeworkingWhyField">
<? drawSafeworkingWhyFields($thisSafeworkingWhy); ?>
</tr>

	<tr valign="top" height="20">
		<td align="right"> <b> Gauge :  </b> </td>
		<td><select name="thisGaugeField">
<? drawGaugeFields($thisGauge); ?>
		</select></td>
	</tr>
<?
}	// end function



function  drawAddNewLocationEvent()
{
	?>
	<tr valign="top" height="20">
	<td align="right"> <b> Date :  </b> </td>
	<td> <input type="text" name="thisDateField" size="30" value="">  </td> 
</tr>
<tr valign="top" height="20">
		<td align="right"> <b> Approx? :  </b> </td>
		<td> <select name="thisApproxField">
		<option selected value="exact">Exact</option>
		<option <? if ($thisApprox == 'approx'){echo selected;} ?> value="approx">Approx</option>
		<option <? if ($thisApprox == 'year'){echo selected;} ?> value="year">Year Only</option>
		<option <? if ($thisApprox == 'month'){echo selected;} ?> value="month">Year and Month</option></select></td> 
</tr>
<tr valign="top" height="20">
	<td align="right"> <b> Details :  </b> </td>
	<td> <textarea name="thisDetailsField" wrap="VIRTUAL" cols="50" rows="4"></textarea></td> 
</tr>
<tr valign="top" height="20">
		<td align="right"> <b> LX Updates :  </b><br>
		<b>Replaces Details!</b> </td>
		<td><select name="thisLxDetailsField">
		<option selected value=""></option>
		<option <? if ($thisDetails == '8'){echo selected;} ?> value="8">Crossing</option>
		<option <? if ($thisDetails == '9'){echo selected;} ?> value="9">Flashing lights</option>
		<option <? if ($thisDetails == '10'){echo selected;} ?> value="10">Boom barriers</option>
		<option <? if ($thisDetails == '11'){echo selected;} ?> value="11">BB and BG</option>
		<option <? if ($thisDetails == '12'){echo selected;} ?> value="12">BG</option>
		<option <? if ($thisDetails == '13'){echo selected;} ?> value="13">Hand gates</option>
		<option <? if ($thisDetails == '14'){echo selected;} ?> value="14">CC</option>
		<option <? if ($thisDetails == '38'){echo selected;} ?> value="38">Interlocked gates</option>
		<option <? if ($thisDetails == '39'){echo selected;} ?> value="39">Wicket Gates</option>
		<option <? if ($thisDetails == '1'){echo selected;} ?> value="1">Underpass</option>
		<option <? if ($thisDetails == '2'){echo selected;} ?> value="2">Overpass</option>
		<option <? if ($thisDetails == '3'){echo selected;} ?> value="3">Subway</option>
		<option <? if ($thisDetails == '4'){echo selected;} ?> value="4">Footbridge</option></select></td> 
	</tr>
<tr valign="top" height="20">
	<td align="right"> <b> Diagram Changed :  </b> </td>
	<td> <input type="checkbox" name="thisDiagramField" >  </td> 
</tr>
<?
}

function drawEditLineHeadbar($lineLink)
{
	?>
<div class="headbar">
<a href="editLines.php?line=<? echo $lineLink; ?>">Details</a> :: 
<a href="listLineLocations.php?line=<? echo $lineLink; ?>">Locations</a> :: 
<a href="listLineEvents.php?line=<? echo $lineLink; ?>">Events</a>
</div>
<?
}






function drawObjectSources($type, $objectID)
{
	$niceName = ucfirst($type);
?>
<fieldset id="sources"><legend>Sources</legend>
<table class="linedTable">
<tr>
	<th>Source</th>
	<th align='left'>Extra</th>
	<th align='left'>Page</th>
	<th align='left'>Date</th>
	<th align='left'>URL</th>
	<th align='left'>Edit?</th>
	<th align='left'>Delete?</th>
</tr>
<?
// gets the sources for this location
$sql3 = "SELECT * FROM object_sources, sources WHERE object_sources.source_id = sources.source_id AND `".$type."_id` = '".$objectID."'";
$result3 = MYSQL_QUERY($sql3);
$numberOfRows3 = MYSQL_NUMROWS($result3);
if ($numberOfRows3>0) 
{		
	for ($i3 = 0; $i3 < $numberOfRows3; $i3++)
	{
		$sourceName = stripslashes(MYSQL_RESULT($result3,$i3,"short"));
		$uniqueId = MYSQL_RESULT($result3,$i3,"linkzor_id");
		$extra = stripslashes(MYSQL_RESULT($result3,$i3,"extra"));
		$date = stripslashes(MYSQL_RESULT($result3,$i3,"date"));
		$page = stripslashes(MYSQL_RESULT($result3,$i3,"page"));
		$url = stripslashes(MYSQL_RESULT($result3,$i3,"url"));
		$url_title = stripslashes(MYSQL_RESULT($result3,$i3,"url_title"));
		$sourceId = MYSQL_RESULT($result3,$i3,"source_id");
		
		if ($date != '')
		{
			$date = 'Y';
		}
		
		if ($url != '')
		{
			$url = 'Y';
		}
	
		if (($i3%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }
		
		?>
<tr class="<? echo $bgColor; ?>">
	<td ALIGN='CENTER'><? echo $sourceName; ?></td>
	<td><? echo $extra; ?> </td>
	<td><? echo $page; ?> </td>
	<td><? echo $date; ?> </td>
	<td><? echo $url; ?> </td>
	<td><a href="editObjectSources.php?type=<?=$type?>&id=<? echo $uniqueId; ?>">Edit?</a></td>
	<td><a href="confirmDeleteObjectSources.php?type=<?=$type?>&id=<? echo $uniqueId; ?>">Delete?</a></td>
</tr>	<?
	} // end for loop
} //end if
else
{
	echo '<tr><th colspan="2">NONE!</th></tr>';
}
?>
</table>
<br/>


<form name="ObjectSourcesForm" method="POST" action="insertNewObjectSources.php">
<table>
<tr><td><b> Source :  </b></td><td>
<select name="thisSourceIdField">	
<? drawSourcesSelectFields(''); ?>
</select></td></tr>
<tr><td><b>Extra : </b></td><td><input type="text" name="thisExtraField" size="30"></td></tr>
<tr><td><b>URL : </b></td><td><input type="text" name="thisURLField" size="30"></td></tr>
<tr><td><b>Link title : </b></td><td><input type="text" name="thisLinkTitleField" size="30"></td></tr>
<tr><td><b>Page : </b></td><td><input type="text" name="thisPageField" size="30"></td></tr>
<tr><td><b>Date : </b></td><td><input type="text" name="thisDateField" size="30"></td></tr>
</table>
<input type="hidden" name="thisObjectIdField" value="<? echo $objectID; ?>">
<input type="hidden" name="type" value="<?=$type?>">
<input type="submit" name="submitObjectSourcesForm" value="Enter New <?=$niceName?> Source">
</form>
</fieldset>
<?

} /// end function








function drawRegionSelectFields($currentRegionId)
{
	$sql = "SELECT * FROM articles WHERE line_id = '-1'";
	$result = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUMROWS($result);
	if ($numberOfRows==0) {  
		?>
		<option value="" selected>None Found!</option>
		<?php
	}
	else if ($numberOfRows>0) 
	{
		echo '<option value="" selected>[NULL]</option>';
		
		for ($i = 0; $i<$numberOfRows; $i++)
		{
			$thisName = stripSlashes(MYSQL_RESULT($result,$i,"title"));
			$thisRegion_id = MYSQL_RESULT($result,$i,"article_id");
			
			if ($currentRegionId == $thisRegion_id)
			{
				$selected = 'selected';
			}
			else
			{
				$selected = '';
			}
?>
		<option <?=$selected?> value="<?=$thisRegion_id?>"><?=$thisName?> (<?=$thisRegion_id?>)</option>
<?
		} // end while loop
	} //end if
}
?>