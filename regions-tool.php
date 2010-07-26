<?php

include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/source-functions.php");
include_once("common/lineguide-functions.php");

/*
 * For regions, groupings of lineguides and articles, that all deal with a geographic area.
 * Bases around articles, but with extra details
 * 
 * 
 * 
 */
 
include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
 
$link = $_REQUEST['name'];

if ($link == '')
{
	draw404InvalidSubpage('lineguide');
}
else
{	
	$articleSQL = "SELECT DATE_FORMAT(modified, '".SHORT_DATE_FORMAT.sprintf("') AS fdate, title, 
		content, description, photos, article_id, caption, `link` 
		FROM articles WHERE `link` = '%s'", mysql_real_escape_string($link));
	$article = MYSQL_QUERY($articleSQL, locationDBconnect());
	
	if (MYSQL_NUM_ROWS($article) == '1')
	{
		$pageTitle = stripslashes(MYSQL_RESULT($article,0,"title"));
		$pageContent = stripslashes(MYSQL_RESULT($article,0,"content"));
		$pageIntro = stripslashes(MYSQL_RESULT($article,0,"description"));
		$photos = stripslashes(MYSQL_RESULT($article,0,"photos"));
		$regionId = stripslashes(MYSQL_RESULT($article,0,"article_id"));
		$caption = stripslashes(MYSQL_RESULT($article,0,"caption"));
		$lastUpdatedDate = MYSQL_RESULT($article,0,"fdate"); 
		
		include_once('common/header.php');
		
		global $editablelinkforadmin;
		if ($editablelinkforadmin)
		{
			echo "<b>Edit: </b><a href=\"/backend/editArticles.php?id=$regionId\" target=\"_new\">Edit Region</a><br/>\n";
		}
		
		// get pretty header photo
		$headerpicdisplayed = drawHeaderPic('region', $link, $pageTitle, $caption);
		
		// get contents titles
		$descriptionTitles = addDescriptionTitles('', 'lines');
		$descriptionTitles = getDescriptionTitles($pageContent, $descriptionTitles);
		$articleSources = getObjectSources('article', $regionId, '');
		
		if(showPhotos($photos))
		{
			include_once("common/gallery-functions.php");
			$regionPhotos = getLocationImages($photos);
			$showPhotos = (sizeof($regionPhotos) > 0);
			$descriptionTitles = addDescriptionTitles($descriptionTitles, 'photos');
		}
		else
		{
			$showPhotos = false;
		}
	
		if (!$articleSources)
		{
			$descriptionTitles = addDescriptionTitles($descriptionTitles, 'sources');
		}
		
		printDescriptionTitles($descriptionTitles);
		
		// get intro
		echo drawFormattedText($pageIntro);
		
		// draw rail lines in this region
		if ($headerpicdisplayed)
		{
			echo "\n<br clear=\"all\">\n";
		}
		echo "<h4 id=\"lines\">Rail lines</h4><hr/>\n";
		
		drawRegionRaillines($regionId);
		
		echo drawFormattedText($pageContent);
		
		if($showPhotos)
		{
			drawLocationImages($regionPhotos);
		}
		
		// draw credits previously formatted by drawObjectSources()
		if ($articleSources != '')
		{
			echo $articleSources;
		}
		
		include_once("common/footer.php");
	}
	else
	{
		draw404InvalidSubpage('lineguide','Region');
	}
}

function drawRegionRaillines($regionId)
{
	$raillineSQL = sprintf("SELECT r.*, r.`order` AS lineorder, r.link AS pagelink, r.name AS pagetitle, r.description as pagecontent, rr.content as regioncontent, 
			count(lr.line_id) AS line_locations, 'page' AS type
			FROM railline_region rr
			INNER JOIN raillines r ON rr.line_id = r.line_id 
			LEFT OUTER JOIN locations_raillines lr ON lr.line_id = r.line_id
			WHERE rr.article_id = '%s' AND todisplay != 'hide'
			GROUP BY lr.line_id 
		UNION ALL 
			SELECT r.*, r.`order` AS lineorder, a.link AS pagelink, a.title AS pagetitle, a.content as pagecontent, '' as regioncontent, 
			0 AS line_locations, 'subpage' AS type
			FROM railline_region rr
			INNER JOIN raillines r ON rr.line_id = r.line_id 
			LEFT OUTER JOIN articles a ON a.line_id = r.line_id
			WHERE rr.article_id = '%s' AND todisplay != 'hide'
			ORDER BY lineorder ASC",
			mysql_real_escape_string($regionId), mysql_real_escape_string($regionId));
						
	$raillineResults = MYSQL_QUERY($raillineSQL, locationDBconnect());
	$numberOfLines = MYSQL_NUM_ROWS($raillineResults);
	
	// build up the dataset
	if ($numberOfLines > 0)
	{
		$i = 0;
		$raillineIndex = -1;
		
		// add rows to the array
		while ($i < $numberOfLines)
		{
			$lineId = MYSQL_RESULT($raillineResults,$i,"line_id");
			// check what type of item the row is
			$rowType = MYSQL_RESULT($raillineResults,$i,"type");
			$regionContent = parseLinks(stripslashes(MYSQL_RESULT($raillineResults,$i,"regioncontent")));
			$pageTitle = stripslashes(MYSQL_RESULT($raillineResults,$i,"pagetitle"));
			$pageLink = strToLower(stripslashes(MYSQL_RESULT($raillineResults,$i,"pagelink")));
			$pageContent = stripslashes(MYSQL_RESULT($raillineResults,$i,"pagecontent"));
			
			if ($rowType == "page")
			{
				// the index into $lineArray, updated when a new / unique railine is found
				$raillineIndex++;
				$lineArray[$raillineIndex] = getLineBasicDetails($raillineResults, $i);
				$lineArray[$raillineIndex]['pageNameArray'][] = array($pageLink, $pageTitle, $pageTitle);
				$lineArray[$raillineIndex]['regionContent'] = $regionContent;
				
			}	
			else if ($rowType == "subpage" && $pageLink != "")
			{
				$lineArray[$raillineIndex]['pageNameArray'][] = array($pageLink, $pageTitle, $pageTitle);
			}
			
			$lineIdPast = $lineId;
			$i++;
		}
		
		// output the formatting HTML
		echo "<table class=\"linedTable\">\n";
		for ($i = 0; $i < sizeof($lineArray); $i++)
		{	
			$itemstodisplay = getLineguidePages($lineArray[$i]);
							
			echo "<tr><td colspan=\"2\"><h5>" . $lineArray[$i]['lineName'] . "</h5></td></tr>\n";
			echo "<tr><td class=\"regionLinks\"><ul>\n";
			echo "<li><a href=\"/lineguide/" . $lineArray[$i]['lineLink'] . "\">Introduction</a></li>\n";
	
			for ($j = 0; $j < sizeof($itemstodisplay); $j++)
			{
?>
<li><a href="/lineguide/<?=$link; ?>/<?=$itemstodisplay[$j][0]; ?>" ><?=$itemstodisplay[$j][1]; ?></a></li>
<?		
			}	
			echo "</ul></td>\n";
			echo "<td class=\"regionContent\">" . $lineArray[$i]['regionContent'] . "</td></tr>\n";
			
			$lineOrderPast = $lineOrder;
		}
		
		echo "</table>\n";
	}
}
?>