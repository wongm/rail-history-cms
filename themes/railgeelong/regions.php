<?php

require_once("common/definitions.php");
require_once("common/formatting-functions.php");
require_once("common/source-functions.php");
require_once("common/lineguide-functions.php");
require_once("common/lineguide-database-functions.php");

/*
 * For regions, groupings of lineguides and articles, that all deal with a geographic area.
 * Bases around articles, but with extra details
 * 
 * 
 * 
 */
 
$regionLink = $_REQUEST['name'];

if ($regionLink == '')
{
	draw404InvalidSubpage('lineguide');
}
else
{	
	$articleSQL = "SELECT DATE_FORMAT(modified, '".SHORT_DATE_FORMAT.sprintf("') AS fdate, title, 
		content, description, photos, article_id, caption, `link` 
		FROM articles WHERE `link` = %s", db_quote($regionLink));
	$article = query_full_array($articleSQL);
	
	if (sizeof($article) == 1)
	{
		$pageTitle = stripslashes($article[0]["title"]);
		$pageContent = stripslashes($article[0]["content"]);
		$pageIntro = stripslashes($article[0]["description"]);
		$photos = stripslashes($article[0]["photos"]);
		$regionId = $article[0]["article_id"];
		$caption = stripslashes($article[0]["caption"]);
		$lastUpdatedDate = $article[0]["fdate"]; 
		
		require_once('common/header.php');
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/lineguides/">Line Guides</a> &raquo; <?php echo $pageTitle?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once('common/midbar.php'); ?>
<h3><?php echo $pageTitle?></h3>
<?php 	
		drawAdminEditableLink("/backend/editArticles.php?id=$regionId", "Edit Region");
		
		// get pretty header photo
		$headerpicdisplayed = drawHeaderPic('region', $regionLink, $pageTitle, $caption);
		
		// get contents titles
		$descriptionTitles = addDescriptionTitles('', 'lines');
		$descriptionTitles = getDescriptionTitles($pageContent, $descriptionTitles);
		$articleSources = getObjectSources('article', $regionId, '');
		
		if(showPhotos($photos))
		{
			require_once("common/gallery-functions.php");
			$regionPhotos = getLocationImages($photos);
			$showPhotos = (sizeof($regionPhotos) > 0);
			$descriptionTitles = addDescriptionTitles($descriptionTitles, 'photos');
		}
		else
		{
			$showPhotos = false;
		}
	
		if ($articleSources)
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
		
		drawRegionRaillines($regionLink, $regionId);
		
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
		
		require_once("common/footer.php");
	}
	else
	{
		draw404InvalidSubpage('lineguide','Region');
	}
}

function drawRegionRaillines($regionLink, $regionId)
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
			($regionId), ($regionId));
						
	$raillineResults = query_full_array($raillineSQL);
	$numberOfLines = sizeof($raillineResults);
	
	// build up the dataset
	if ($numberOfLines > 0)
	{
		$i = 0;
		$raillineIndex = -1;
		
		// set parent item for nav.php
		global $pageNavigation;
		$pageNavigation['regions'] = array($regionLink);
		
		// add rows to the array
		while ($i < $numberOfLines)
		{
			$lineId = $raillineResults[$i]["line_id"];
			// check what type of item the row is
			$rowType = $raillineResults[$i]["type"];
			$regionContent = parseLinks(stripslashes($raillineResults[$i]["regioncontent"]));
			$pageTitle = stripslashes($raillineResults[$i]["pagetitle"]);
			$pageLink = strToLower(stripslashes($raillineResults[$i]["pagelink"]));
			$pageContent = stripslashes($raillineResults[$i]["pagecontent"]);
			
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
			// set child items for nav.php
			$pageNavigation[$i]['url'] = "/lineguide/" . $lineArray[$i]['lineLink'] . "/";
			$pageNavigation[$i]['title'] = $lineArray[$i]['lineName'];
			
			$itemstodisplay = getLineguidePages($lineArray[$i]);
							
			echo "<tr><td colspan=\"2\"><h5>" . $lineArray[$i]['lineName'] . "</h5></td></tr>\n";
			echo "<tr><td class=\"regionLinks\"><ul>\n";
			echo "<li><a href=\"/lineguide/" . $lineArray[$i]['lineLink'] . "\">History</a></li>\n";
			
			for ($j = 0; $j < sizeof($itemstodisplay); $j++)
			{
?>
<li><a href="/lineguide/<?php echo $lineArray[$i]['lineLink']; ?>/<?php echo $itemstodisplay[$j][0]; ?>" ><?php echo $itemstodisplay[$j][1]; ?></a></li>
<?php 	
			}	
			echo "</ul></td>\n";
			echo "<td class=\"regionContent\">" . $lineArray[$i]['regionContent'] . "</td></tr>\n";
		}
		
		echo "</table>\n";
	}
}
?>