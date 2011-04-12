<?php

function drawUpdatedPagesTable($updatedLocations, $frontPage=false)
{
	echo "<table class=\"linedTable updatedPages\" id=\"locationTable\">\n";
	$j = 0;	// for line styling independant of # of records
	
	if (!$frontPage)
	{
?>
<tr>
	<th class="d">Date</th>
	<th class="t">Type</th>
	<th class="t">Photos</th>
	<th class="t">History</th>
	<th></th>
</tr>
<?
	}
	
	for ($i = 0; $i < MYSQL_NUM_ROWS($updatedLocations); $i++)
	{	
		if ($j%2 == '0')
		{
			$style = 'class="x"';
		}
		else
		{
			$style = 'class="y"';
		}
		
		$date = MYSQL_RESULT($updatedLocations,$i,"fdate");
		$id = 	MYSQL_RESULT($updatedLocations,$i,"link");
		$name = stripslashes(MYSQL_RESULT($updatedLocations,$i,"name"));
		$objecttype = MYSQL_RESULT($updatedLocations,$i,"object_type");
		$locationtype = MYSQL_RESULT($updatedLocations,$i,"type");
		$length = MYSQL_RESULT($updatedLocations,$i,"length");
		$events = MYSQL_RESULT($updatedLocations,$i,"events");
		
		//print_r(mysql_fetch_assoc($updatedLocations));
		
		switch ($objecttype)
		{
			case 'L':
				$path = 'Location';
				$name = getLocationName($name, $locationtype);
				break;
			case 'RL':
				$path = 'Lineguide';
				$name = getLineName($name);
				break;
			case 'A':
				$path = 'Article';
				break;	
			case 'R':
				$path = 'Region';
				break;
		}
		
		$urlText = '<a href="/'.strtolower($path).'/'.$id.'">';
		
		if (showPhotos(MYSQL_RESULT($updatedLocations,$i,"photos")))
		{
			$photosText = $urlText.'<img src="/images/photos.gif" alt="Photos" title="Photos" /></a>';
		}
		else
		{
			$photosText = '';
		}
		
		// skip location if has already been displayed, when on multiple lines it has the same ID
		if ($id != $pastId)
		{
			$j++;
?>
<tr <? echo $style; ?>>
	<td><? echo $date; ?></td>
	<td><?=$path ?></td>
	<td><?=$photosText ?></td>
	<td><?=getLocationDescriptionLengthImage($length, $events) ?></td>
	<td align="left"><?=$urlText?><?=$name; ?></a></td>
</tr>
<?		}
		
		$pastId = $id;
	}	// end for loop
?>
</table>
<?
}	// end function

function drawPageOfUpdated($updatedPages)
{
	$page = $updatedPages['page'];
	$maxRowsPerPage = $updatedPages['maxRowsPerPage'];
	
	$extraBit = ', pages '.drawNumberCurrentDispayedRecords($maxRowsPerPage, $updatedPages["numberOfRows"], $page-1);
	$nextPageUrl = '/updates/page/';
?>
<h3>Updated content</h3>
<p>Pages most recently created, expanded and updated appear first<?=$extraBit?></p>
<?php
	drawUpdatedPagesTable($updatedPages["result"]);
	drawNextAndBackLinks($updatedPages['index'], $updatedPages["maxRows"], $maxRowsPerPage, $nextPageUrl, true);
} //end function

function getUpdatedPages($index, $maxRowsPerPage)
{
	$sqlQuery = "SELECT l.location_id AS link, 'L' AS object_type, l.name, l.modified, l.events, 
		DATE_FORMAT(l.modified, '".SHORT_DATE_FORMAT."') AS fdate, l.type, l.photos AS photos, l.description AS length
	FROM locations_raillines lr
	INNER JOIN locations l ON lr.location_id = l.location_id
	INNER JOIN raillines r ON r.line_id = lr.line_id 
	INNER JOIN location_types lt ON l.type = lt.type_id 
	WHERE ".SQL_NEXTABLE." AND r.todisplay != 'hide'  AND l.name != '' 
	AND l.display != 'tracks' AND l.type != ".TYPE_TIMING_LOOP."
	GROUP BY l.location_id
	
	UNION ALL
	SELECT link AS link, 'RL' AS object_type, name, modified, 0, 
		DATE_FORMAT(r.modified, '".SHORT_DATE_FORMAT."') AS fdate, '' AS type, photos, description AS length
	FROM raillines r 
	WHERE r.todisplay != 'hide'
	
	UNION ALL
	SELECT link AS link, 'A' AS object_type, title AS name, modified, 0, 
		DATE_FORMAT(a.modified, '".SHORT_DATE_FORMAT."') AS fdate, '' AS type, photos, content AS length
	FROM articles a
	WHERE a.line_id = 0 AND a.link != ''
	
	UNION ALL
	SELECT link AS link, 'R' AS object_type, title AS name, modified, 0, 
		DATE_FORMAT(a.modified, '".SHORT_DATE_FORMAT."') AS fdate, '' AS type, photos, description AS length
	FROM articles a
	WHERE a.line_id = -1";
	
	$sqlOrderBy = sprintf(" ORDER BY modified DESC LIMIT %s,%s", 
		mysql_real_escape_string($index), 
		mysql_real_escape_string($maxRowsPerPage)
		);
	
	$locations["result"] = $result = MYSQL_QUERY($sqlQuery.$sqlOrderBy, locationDBconnect());
	$locations["numberOfRows"] = MYSQL_NUM_ROWS($result);
	$resultMaxRows = MYSQL_QUERY($sqlQuery, locationDBconnect());
	$locations["maxRows"] = MYSQL_NUM_ROWS($resultMaxRows);
	
	return $locations;
}	// end function
?>