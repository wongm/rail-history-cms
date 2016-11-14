<?php

function drawUpdatedPagesTable($updatedLocations, $frontPage=false)
{
	echo "<table class=\"linedTable updatedPages\" id=\"locationTable\">\n";
	$j = 0;	// for line styling independant of # of records
	
	if (!$frontPage)
	{
?>
<tr>
	<th></th>
	<th class="t">Type</th>
	<th class="d">Date</th>
	<th class="t">Photos</th>
	<th class="t">History</th>
</tr>
<?php
	}
	
	for ($i = 0; $i < sizeof($updatedLocations); $i++)
	{	
		if ($j%2 == '0')
		{
			$style = 'odd';
		}
		else
		{
			$style = 'even';
		}
		
		$date = $updatedLocations[$i]["fdate"];
		$objectid = $updatedLocations[$i]["object_id"];
		$locationlink = $updatedLocations[$i]["link"];
		$name = stripslashes($updatedLocations[$i]["name"]);
		$objecttype = $updatedLocations[$i]["object_type"];
		$locationtype = $updatedLocations[$i]["type"];
		$length = $updatedLocations[$i]["length"];
		$events = $updatedLocations[$i]["events"];
		
		$itemlink = $objectid;
		
		//print_r(mysql_fetch_assoc($updatedLocations));
		
		switch ($objecttype)
		{
			case 'L':
				$itemType = 'Location';
				$name = getLocationName($name, $locationtype);
				if (strlen($locationlink) > 0) {
					$itemlink = $locationlink;
				}
				break;
			case 'RL':
				$itemType = 'Lineguide';
				$name = getLineName($name);
				break;
			case 'A':
				$itemType = 'Article';
				break;	
			case 'R':
				$itemType = 'Region';
				break;
		}
		
		$urlText = '<a href="/'.strtolower($itemType).'/'.$itemlink.'">';
		
		if (showPhotos($updatedLocations[$i]["photos"]))
		{
			$photosText = $urlText.'<img src="/images/photos.gif" alt="Photos" title="Photos" /></a>';
		}
		else
		{
			$photosText = '';
		}
		
		// skip location if has already been displayed, when on multiple lines it has the same ID
		if ($objectid != $pastId)
		{
			$j++;
?>
<tr class="<?php echo $style; ?>">
<?php if ($frontPage) { ?><td class="d"><?php echo $date; ?></td><?php } ?>
	<td align="left"><?php echo $urlText?><?php echo $name; ?></a></td>
	<td><?php echo $itemType ?></td>
<?php if (!$frontPage) { ?><td class="d"><?php echo $date; ?></td><?php } ?>
	<td><?php echo $photosText ?></td>
	<td><?php echo getLocationDescriptionLengthImage($length, $events) ?></td>
</tr>
<?php 	}
		
		$pastId = $objectid;
	}	// end for loop
?>
</table>
<?php
}	// end function

function drawPageOfUpdated($updatedPages)
{
	$page = $updatedPages['page'];
	$maxRowsPerPage = $updatedPages['maxRowsPerPage'];
	
	$extraBit = ', pages '.drawNumberCurrentDisplayedRecords($maxRowsPerPage, $updatedPages["numberOfRows"], $page-1);
	$nextPageUrl = '/updates/page/';
?>
<h3>Updated content</h3>
<p>Pages most recently created, expanded and updated appear first<?php echo $extraBit?></p>
<?php
	drawUpdatedPagesTable($updatedPages["result"]);
	drawNextAndBackLinks($updatedPages['index'], $updatedPages["maxRows"], $maxRowsPerPage, $nextPageUrl, true);
} //end function

function getUpdatedPages($index, $maxRowsPerPage)
{
	$sqlQuery = "SELECT l.location_id AS object_id, 'L' AS object_type, l.name, l.link AS link, l.modified, l.events, 
		DATE_FORMAT(l.modified, '".SHORT_DATE_FORMAT."') AS fdate, l.type, l.photos AS photos, l.description AS length
	FROM locations_raillines lr
	INNER JOIN locations l ON lr.location_id = l.location_id
	INNER JOIN raillines r ON r.line_id = lr.line_id 
	INNER JOIN location_types lt ON l.type = lt.type_id 
	WHERE ".SQL_NEXTABLE." AND r.todisplay != 'hide'  AND l.name != '' 
	AND l.display != 'tracks' AND l.type != ".TYPE_TIMING_LOOP."
	GROUP BY l.location_id
	
	UNION ALL
	SELECT link AS object_id, 'RL' AS object_type, name, '' AS link, modified, 0, 
		DATE_FORMAT(r.modified, '".SHORT_DATE_FORMAT."') AS fdate, '' AS type, photos, description AS length
	FROM raillines r 
	WHERE r.todisplay != 'hide'
	
	UNION ALL
	SELECT link AS object_id, 'A' AS object_type, title AS name, '' AS link, modified, 0, 
		DATE_FORMAT(a.modified, '".SHORT_DATE_FORMAT."') AS fdate, '' AS type, photos, content AS length
	FROM articles a
	WHERE a.line_id = 0 AND a.link != ''
	
	UNION ALL
	SELECT link AS object_id, 'R' AS object_type, title AS name, '' AS link, modified, 0, 
		DATE_FORMAT(a.modified, '".SHORT_DATE_FORMAT."') AS fdate, '' AS type, photos, description AS length
	FROM articles a
	WHERE a.line_id = -1";
	
	$sqlLimitedOrderBy = sprintf(" ORDER BY modified DESC LIMIT %s,%s", 
		($index), 
		($maxRowsPerPage)
		);
	
	$locations["result"] = $result = query_full_array($sqlQuery.$sqlLimitedOrderBy);
	$locations["numberOfRows"] = db_affected_rows($result);
	$resultMaxRows = query($sqlQuery);
	$locations["maxRows"] = db_affected_rows($resultMaxRows);
	
	return $locations;
}	// end function
?>