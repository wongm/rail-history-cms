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
	$raillineSQL = sprintf("SELECT *, count(lr.line_id) AS line_locations 
		FROM railline_region rr
		INNER JOIN raillines r ON rr.line_id = r.line_id 
		LEFT OUTER JOIN locations_raillines lr ON lr.line_id = r.line_id
		WHERE article_id = '%s' AND todisplay != 'hide'
		GROUP BY lr.line_id 
		ORDER BY r.order ASC", mysql_real_escape_string($regionId));
	$raillineResults = MYSQL_QUERY($raillineSQL, locationDBconnect());
	$numberOfLines = MYSQL_NUM_ROWS($raillineResults);
	
	
	if ($numberOfLines > 0)
	{
		echo "<table class=\"linedTable\">\n";
			
		for ($i = 0; $i < $numberOfLines; $i++)
		{
			$name = stripslashes(MYSQL_RESULT($raillineResults,$i,"r.name"));
			$link = stripslashes(MYSQL_RESULT($raillineResults,$i,"r.link"));
			$content = parseLinks(stripslashes(MYSQL_RESULT($raillineResults,$i,"rr.content")));
			
			$itemstodisplay = getLineguidePages(getLineBasicDetails($raillineResults, $i));	
			
			echo "<tr><td colspan=\"2\"><h5>$name</h5></td></tr>\n";
			echo "<tr><td class=\"regionLinks\"><ul>\n";
			echo "<li><a href=\"/lineguide/$link\">Introduction</a></li>\n";

			for ($j = 0; $j < sizeof($itemstodisplay); $j++)
			{
?>
<li><a href="/lineguide/<?=$link; ?>/<?=$itemstodisplay[$j][0]; ?>" ><?=$itemstodisplay[$j][1]; ?></a></li>
<?		
			}
				
			echo "</ul></td>\n";
			echo "<td class=\"regionContent\">$content</td></tr>\n";
		}
		
		echo "</table>\n";
	}
}
?>