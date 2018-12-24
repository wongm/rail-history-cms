<?php

/*
	contains:
	
	drawRaillineTypeFields($type)
	
	<?php drawLineNameSelectFields($currentLineId); ?>
	<?php drawLocationNameSelectFields($currentLocationId)	; ?>
	<?php drawSourcesSelectFields($currentSourceId); ?>
	<?php drawApproxDistanceFields($thisKmAccuracy); ?>
	<?php drawApproxTimeFields($thisApprox); ?>
	<?php drawLocationDisplayTypeFields($thisDisplay); ?>
	<?php drawLineDisplayTypeFields($thisDisplay); ?>
	<?php drawLocationTypeFields($thisType); ?>
	<?php drawLocationLxEventFields($thisDetails); ?>
	<?php drawAddNewRailineEvent(); ?>
	<?php drawAddNewLocationEvent(); ?>
	<?php drawSafeworkingWhyFields($thisSafeworkingWhy); ?>
	<?php drawSafeworkingNameSelectFields($thisSafeworking); ?>
	<?php drawGaugeFields($thisGauge); ?>
	
	
	
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
		<option <?php if ($type == 'main'){echo "selected";} ?> value="main">Main</option>
		<option <?php if ($type == 'branch'){echo "selected";} ?> value="branch">Branch</option>
<?php
}	// end function	

function drawHeadbar($thisKm, $thisLine)
{
	// next and forward linkzor
	$sqlBack = "SELECT * FROM locations l, locations_raillines lr 
	WHERE l.location_id = lr.location_id AND `km` < '".$thisKm."' AND line_id = '".$thisLine."' ORDER BY km DESC";
	$resultBack = query_full_array($sqlBack);
	$sqlNext = "SELECT * FROM locations l, locations_raillines lr 
	WHERE l.location_id = lr.location_id AND `km` > '".$thisKm."' AND line_id = '".$thisLine."' ORDER BY km ASC";
	$resultNext = query_full_array($sqlNext);
	
	if (sizeof($resultBack) > '0')	
	{
		$Name = stripslashes($resultBack[0]["name"]);
		$id = stripslashes($resultBack[0]["location_id"]);
		$back = '<a href="./editLocations.php?location='.$id.'">&laquo; '.$Name.'</a>'; 
	}	
	if (sizeof($resultNext) > '0')	
	{
		$Name = stripslashes($resultNext[0]["name"]);
		$id = stripslashes($resultNext[0]["location_id"]);
		$next = '<a href="./editLocations.php?location='.$id.'">'.$Name.' &raquo;</a>'; 
	}
	
	if ($back != '' OR $next != '')
	{
	?>
<!-- next / back links -->
<div class="pagelist">
	<div class="prev"><?php echo $back; ?></div>
	<div class="next"><?php echo $next; ?></div>
</div>
<?php
	}
}	// end function

function drawLocationTabDisplayFields($tabdisplay, $lineName)
{
	?>
<label><input type="checkbox" <?php if (substr($tabdisplay, 4, 1) == 1) { echo 'checked="yes"'; } ?> name="showTrack" /> Track diagram
	<small>(exact type depends on options selected below)</small></label><br>
<label><input type="checkbox" <?php if (substr($tabdisplay, 3, 1) == 1) { echo 'checked="yes"'; } ?> name="showSafeworking" /> Safeworking diagram</label><br>
<label><input type="checkbox" <?php if (substr($tabdisplay, 2, 1) == 1) { echo 'checked="yes"'; } ?> name="showEvents" /> Events</label><br>
<label><input type="checkbox" <?php if (substr($tabdisplay, 1, 1) == 1) { echo 'checked="yes"'; } ?> name="showLocations" /> Locations</label><br>
<label><input type="checkbox" <?php if (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/kml/kml-'.$lineName.'.kml')) { echo 'checked="yes"'; } ?> /> Google map
	<small>(user can't change, requires existance of "/images/kml/kml-<?php echo $lineName?>.kml")</small></label><br>
<label><input type="checkbox" <?php if ($tabdisplay == 'hide' ) { echo 'checked="yes"'; } ?> name="hideAll" /> Hide line and all locations</label>
<?php
}

function drawGaugeFields($thisGauge)
{
	?>
		<option <?php if ($thisGauge == 'bg'){echo "selected";} ?> value="bg">BG</option>
		<option <?php if ($thisGauge == 'sg'){echo "selected";} ?> value="sg">SG</option>
		<option <?php if ($thisGauge == 'dg'){echo "selected";} ?> value="dg">DG</option>
		<option <?php if ($thisGauge == 'ng'){echo "selected";} ?> value="ng">NG</option>
		<option value="bg">BG</option>
<?php
}


function drawSafeworkingWhyFields($thisSafeworkingWhy)
{
	?>
		<option <?php if ($thisSafeworkingWhy == 'replaced'){echo "selected";} ?> value="replaced">New replaced old</option>
		<option <?php if ($thisSafeworkingWhy == 'block'){echo "selected";} ?> value="block">Block point opened</option>
		<option <?php if ($thisSafeworkingWhy == 'station'){echo "selected";} ?> value="station">Station opened</option>
		<option <?php if ($thisSafeworkingWhy == 'closed'){echo "selected";} ?> value="closed">Location closed</option>
		<option <?php if ($thisSafeworkingWhy == 'singled'){echo "selected";} ?> value="singled">Singled line (non-SW)</option>
		<option <?php if ($thisSafeworkingWhy == 'opened'){echo "selected";} ?> value="opened">Opened line (non-SW)</option>
		<option <?php if ($thisSafeworkingWhy == 'downgrade'){echo "selected";} ?> value="downgrade">Downgrade</option>
		<option <?php if ($thisSafeworkingWhy == 'plain'){echo "selected";} ?> value="plain">[Show plain]</option>
		<option value="replaced"></option>
<?php
}

function drawSafeworkingNameSelectFields($thisSafeworking)
{
	$sql = "SELECT * FROM safeworking_types ORDER BY name ASC";
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
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
			$thisType = $result[$i]["link"];
			$thisName = $result[$i]["name"];
			
			if($thisType == $thisSafeworking)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
		?>
			<option <?php echo $selected; ?> value="<?php echo $thisType?>"><?php echo $thisName.' ('.$thisType.')'?></option>
		<?php
		} // end while loop
	}
}

function drawLocationLxEventFields($thisDetails)
{
?>
		<option selected value=""></option>
		<option <?php if ($thisDetails == '8'){echo "selected";} ?> value="8">Crossing</option>
		<option <?php if ($thisDetails == '9'){echo "selected";} ?> value="9">Flashing lights</option>
		<option <?php if ($thisDetails == '10'){echo "selected";} ?> value="10">Boom barriers</option>
		<option <?php if ($thisDetails == '11'){echo "selected";} ?> value="11">BB and BG</option>
		<option <?php if ($thisDetails == '12'){echo "selected";} ?> value="12">BG</option>
		<option <?php if ($thisDetails == '13'){echo "selected";} ?> value="13">Hand gates</option>
		<option <?php if ($thisDetails == '14'){echo "selected";} ?> value="14">CC</option>
		<option <?php if ($thisDetails == '38'){echo "selected";} ?> value="38">Interlocked gates</option>
		<option <?php if ($thisDetails == '39'){echo "selected";} ?> value="39">Wicket Gates</option>
		<option <?php if ($thisDetails == '1'){echo "selected";} ?> value="1">Underpass</option>
		<option <?php if ($thisDetails == '2'){echo "selected";} ?> value="2">Overpass</option>
		<option <?php if ($thisDetails == '3'){echo "selected";} ?> value="3">Subway</option>
		<option <?php if ($thisDetails == '4'){echo "selected";} ?> value="4">Footbridge</option>	
<?php
}

function drawLineDisplayTypeFields($thisTodisplay)
{
	?>
		<option <?php if ($thisTodisplay == 'both'){echo "selected";} ?> value="both">All</option>
		<option <?php if ($thisTodisplay == 'nosafeevent'){echo "selected";} ?> value="nosafeevent">Diagram Only</option>
		<option <?php if ($thisTodisplay == 'diagramonly'){echo "selected";} ?> value="diagramonly">Diagram and Events Only</option>
		<option <?php if ($thisTodisplay == 'safeworkingonly'){echo "selected";} ?> value="safeworking">Safeworking and Events Only</option>
		<option <?php if ($thisTodisplay == 'none'){echo "selected";} ?> value="none">None</option>
		<option <?php if ($thisTodisplay == 'hide'){echo "selected";} ?> value="hide">Hide - Don't show any</option>
<?php
}			
	

function drawLineNameSelectFields($currentLineId)
{
	$sql = "SELECT * FROM raillines";
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
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
			$thisLoopLine_id = stripslashes($result[$i]["line_id"]);
			$thisLoopLine_link = stripslashes($result[$i]["link"]);
			$thisName = stripslashes($result[$i]["name"]);
			
			if($thisLoopLine_id == $currentLineId || $thisLoopLine_link == $currentLineId)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
		?>
		<option value="<?php echo $thisLoopLine_id?>" <?php echo $selected?> ><?php echo $thisName?></option>
	<?php 

		} 	// end while loop
	}		// end if
}			// end function
	
function drawLocationNameSelectFields($currentLocationId, $google=false)
{
	$sql = "SELECT * FROM locations ORDER BY name";
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
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
			$thisName = stripSlashes($result[$i]["name"]);
			$thisLocation_id = $result[$i]["location_id"];
			
			if ($google)
			{
				if ($result[$i]["long"] != 0)
				{
					$thisName .= " [SET]";
				}
					
				$km = $result[$i]["km_old"];
				$line = $result[$i]["line_old"];
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
		<option <?php echo $selected?> value="<?php echo $thisLocation_id?>"><?php echo $thisName?> (<?php echo $thisLocation_id?>)</option>
<?php
		} // end while loop
	} //end if
}

function drawSourcesSelectFields($currentSourceId)
{
	$sql = "SELECT * FROM sources ORDER BY short ASC";
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
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
			$thisSourceId = stripslashes($result[$i]["source_id"]);
			$thisName = stripslashes($result[$i]["name"]);
			
			if ($currentSourceId == $thisSourceId)
			{
				$selected = 'selected';
			}
			else
			{
				$selected = '';
			}
	?>
		<option <?php echo $selected?> value="<?php echo $thisSourceId?>" ><?php echo $thisName?></option>
	<?php
		} // end while loop
	}
}	// end function


function drawApproxDistanceFields($thisKmAccuracy)
{
	?>
		<option <?php if ($thisKmAccuracy == 'exact'){echo "selected";} ?> value="exact">Exact</option>
		<option <?php if ($thisKmAccuracy == 'approx'){echo "selected";} ?> value="approx">Approx</option>
		<option <?php if ($thisKmAccuracy == 'hide'){echo "selected";} ?> value="hide">Hide</option>
<?php
}	// end function	

function drawApproxTimeFields($thisApprox)
{
	?>
		<option selected value="exact">Exact</option>
		<option <?php if ($thisApprox == 'approx'){echo "selected";} ?> value="approx">Approx</option>
		<option <?php if ($thisApprox == 'decade'){echo "selected";} ?> value="decade">Decade</option>
		<option <?php if ($thisApprox == 'year'){echo "selected";} ?> value="year">Year Only</option>
		<option <?php if ($thisApprox == 'month'){echo "selected";} ?> value="month">Year and Month</option>
<?php
}	// end function		

function drawLocationDisplayTypeFields($thisDisplay)
{
?>		
		<option <?php if ($thisDisplay == 'both'){echo "selected";} ?> value="both">Everywhere [Default]</option>
		<option <?php if ($thisDisplay == 'map'){echo "selected";} ?> value="map">Except Lineguide</option>
		<option <?php if ($thisDisplay == 'tracks'){echo "selected";} ?> value="tracks">Only Lineguide</option>
		<option <?php if ($thisDisplay == 'none'){echo "selected";} ?> value="none">Hide diagrams on own page</option>
		<option <?php if ($thisDisplay == 'line'){echo "selected";} ?> value="line">Depreciated (line linker)</option>
<?php
}	// end function		

function drawLocationTypeFields($thisType)
{
	$sql = "SELECT * FROM location_types";
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
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
			$thisType_id = stripslashes($result[$i]["type_id"]);
			$thisBasic = stripslashes($result[$i]["basic"]);
			$thisMore = stripslashes($result[$i]["more"]);
			$thisSpecific = stripslashes($result[$i]["specific"]);
			
			if($thisType_id == $thisType)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
?>
		<option value="<?php echo $thisType_id?>" <?php echo $selected?> ><?php echo $thisBasic?> - <?php echo $thisMore?> - <?php echo $thisSpecific?></option>
<?php
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
<?php drawApproxTimeFields(); ?> 
		</select></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Display :  </b> </td>
		<td> <select name="thisDisplayField">
		<option <?php if ($thisDisplay == 'yes'){echo "selected";} ?> value="Yes">Yes</option>
		<option <?php if ($thisDisplay == 'hide'){echo "selected";} ?> value="Hide">Hide</option></select></td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right">EITHER</td>
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> <input type="text" name="thisDescriptionField" size="50" value="<?php echo $thisDescription; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right">OR</td>
	</tr>
	
	<!--Start Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Start Location:  </b> </td>
		<td> <select name="thisStart_locationField">
<?php drawLocationNameSelectFields()	; ?>	
		</select></td>
	</tr>
	
	<!--End Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> End Location:  </b> </td>
		<td> <select name="thisEnd_locationField">
<?php drawLocationNameSelectFields()	; ?>	
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
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
	if ($numberOfRows==0) {  
	?>
	<option value="null" selected>None Found!</option>
	<?php
	}
	else if ($numberOfRows>0) 
	{
		
		for ($i = 0;$i<$numberOfRows; $i++)
		{
			$thisType = $result[$i]["link"];
			$thisName = $result[$i]["name"];
			
			if($thisType == $thisSafeworking)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
		?>
			<option <?php echo $selected; ?> value="<?php echo $thisType?>"><?php echo $thisName.' ('.$thisType.')'?></option>
		<?php
			
		} // end while loop
}?>
</select></td></tr>
	
	<!--Middle Location field-->
	<tr valign="top" height="20">
		<td align="right"> <b> Middle Location (optional) :  </b> </td>
		<td> <select name="thisSafeworkingMiddleField">
<?php drawLocationNameSelectFields()	; ?>	
		</select></td>
	</tr>
	
<tr valign="top" height="20">
	<td align="right"> <b> Reason For Above? :  </b> </td>
	<td><select name="thisSafeworkingWhyField">
<?php drawSafeworkingWhyFields($thisSafeworkingWhy); ?>
</tr>

	<tr valign="top" height="20">
		<td align="right"> <b> Gauge :  </b> </td>
		<td><select name="thisGaugeField">
<?php drawGaugeFields($thisGauge); ?>
		</select></td>
	</tr>
<?php
}	// end function



function  drawAddNewLocationEvent()
{
    $thisApprox = null;
    $thisDetails = null;
    
	?>
	<tr valign="top" height="20">
	<td align="right"> <b> Date :  </b> </td>
	<td> <input type="text" name="thisDateField" size="30" value="">  </td> 
</tr>
<tr valign="top" height="20">
		<td align="right"> <b> Approx? :  </b> </td>
		<td> <select name="thisApproxField">
		<option selected value="exact">Exact</option>
		<option <?php if ($thisApprox == 'approx'){echo "selected";} ?> value="approx">Approx</option>
		<option <?php if ($thisApprox == 'year'){echo "selected";} ?> value="year">Year Only</option>
		<option <?php if ($thisApprox == 'month'){echo "selected";} ?> value="month">Year and Month</option></select></td> 
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
		<option <?php if ($thisDetails == '8'){echo "selected";} ?> value="8">Crossing</option>
		<option <?php if ($thisDetails == '9'){echo "selected";} ?> value="9">Flashing lights</option>
		<option <?php if ($thisDetails == '10'){echo "selected";} ?> value="10">Boom barriers</option>
		<option <?php if ($thisDetails == '11'){echo "selected";} ?> value="11">BB and BG</option>
		<option <?php if ($thisDetails == '12'){echo "selected";} ?> value="12">BG</option>
		<option <?php if ($thisDetails == '13'){echo "selected";} ?> value="13">Hand gates</option>
		<option <?php if ($thisDetails == '14'){echo "selected";} ?> value="14">CC</option>
		<option <?php if ($thisDetails == '38'){echo "selected";} ?> value="38">Interlocked gates</option>
		<option <?php if ($thisDetails == '39'){echo "selected";} ?> value="39">Wicket Gates</option>
		<option <?php if ($thisDetails == '1'){echo "selected";} ?> value="1">Underpass</option>
		<option <?php if ($thisDetails == '2'){echo "selected";} ?> value="2">Overpass</option>
		<option <?php if ($thisDetails == '3'){echo "selected";} ?> value="3">Subway</option>
		<option <?php if ($thisDetails == '4'){echo "selected";} ?> value="4">Footbridge</option></select></td> 
	</tr>
<tr valign="top" height="20">
	<td align="right"> <b> Diagram Changed :  </b> </td>
	<td> <input type="checkbox" name="thisDiagramField" >  </td> 
</tr>
<?php
}

function drawEditLineHeadbar($lineLink)
{
	?>
<div class="headbar">
<a href="editLines.php?line=<?php echo $lineLink; ?>">Details</a> :: 
<a href="listLineLocations.php?line=<?php echo $lineLink; ?>">Locations</a> :: 
<a href="listLineEvents.php?line=<?php echo $lineLink; ?>">Events</a>
</div>
<?php
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
<?php
// gets the sources for this location
$sql3 = "SELECT * FROM object_sources, sources WHERE object_sources.source_id = sources.source_id AND `".$type."_id` = '".$objectID."'";
$result3 = query_full_array($sql3);
$numberOfRows3 = sizeof($result3);
if ($numberOfRows3>0) 
{		
	for ($i3 = 0; $i3 < $numberOfRows3; $i3++)
	{
		$sourceName = stripslashes($result3[$i3]["short"]);
		$uniqueId = $result3[$i3]["linkzor_id"];
		$extra = stripslashes($result3[$i3]["extra"]);
		$date = stripslashes($result3[$i3]["date"]);
		$page = stripslashes($result3[$i3]["page"]);
		$url = stripslashes($result3[$i3]["url"]);
		$url_title = stripslashes($result3[$i3]["url_title"]);
		$sourceId = $result3[$i3]["source_id"];
		
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
<tr class="<?php echo $bgColor; ?>">
	<td ALIGN='CENTER'><?php echo $sourceName; ?></td>
	<td><?php echo $extra; ?> </td>
	<td><?php echo $page; ?> </td>
	<td><?php echo $date; ?> </td>
	<td><?php echo $url; ?> </td>
	<td><a href="editObjectSources.php?type=<?php echo $type?>&id=<?php echo $uniqueId; ?>">Edit?</a></td>
	<td><a href="confirmDeleteObjectSources.php?type=<?php echo $type?>&id=<?php echo $uniqueId; ?>">Delete?</a></td>
</tr>	<?php
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
<?php drawSourcesSelectFields(''); ?>
</select></td></tr>
<tr><td><b>Extra : </b></td><td><input type="text" name="thisExtraField" size="30"></td></tr>
<tr><td><b>URL : </b></td><td><input type="text" name="thisURLField" size="30"></td></tr>
<tr><td><b>Link title : </b></td><td><input type="text" name="thisLinkTitleField" size="30"></td></tr>
<tr><td><b>Page : </b></td><td><input type="text" name="thisPageField" size="30"></td></tr>
<tr><td><b>Date : </b></td><td><input type="text" name="thisDateField" size="30"></td></tr>
</table>
<input type="hidden" name="thisObjectIdField" value="<?php echo $objectID; ?>">
<input type="hidden" name="type" value="<?php echo $type?>">
<input type="submit" name="submitObjectSourcesForm" value="Enter New <?php echo $niceName?> Source">
</form>
</fieldset>
<?php

} /// end function








function drawRegionSelectFields($currentRegionId)
{
	$sql = "SELECT * FROM articles WHERE line_id = '-1'";
	$result = query_full_array($sql);
	$numberOfRows = sizeof($result);
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
			$thisName = stripSlashes($result[$i]["title"]);
			$thisRegion_id = $result[$i]["article_id"];
			
			if ($currentRegionId == $thisRegion_id)
			{
				$selected = 'selected';
			}
			else
			{
				$selected = '';
			}
?>
		<option <?php echo $selected?> value="<?php echo $thisRegion_id?>"><?php echo $thisName?> (<?php echo $thisRegion_id?>)</option>
<?php
		} // end while loop
	} //end if
}
?>