<?php 

include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/source-functions.php");
include_once("common/map-functions.php");
	


if (!isset($_REQUEST['name']))
{
	drawAllArticles('article');
}
// for a specific article
else
{
	$link = $_REQUEST['name'];
	$link = str_replace('/', '', $link);
	$article = query_full_array("SELECT *, DATE_FORMAT(modified, '%M %e, %Y') AS fdate FROM articles WHERE `link` = '$link'");
	
	if (sizeof($article) == 1)
	{
		$pageTitle = stripslashes($article[0]["title"]);
		$articleId = stripslashes($article[0]["article_id"]);
		$description = stripslashes($article[0]["content"]);
		$photos = stripslashes($article[0]["photos"]);
		$articleSources = getObjectSources('article', $articleId, '');
		$lastUpdatedDate = $article[0]["fdate"]; 
		
		$mapKML = parseDescriptionForMap($description);
		
		if ($mapKML)
		{
			$googleHeader = 'article';
			$googleHeaderKMLscript = generateKMLScript($mapKML);
			$description = insertMapElement($description, $mapKML);
		}
		
		include_once("header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/articles/">Articles</a> &raquo; <?php echo $pageTitle?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("midbar.php"); ?>
<h3><?php echo $pageTitle?></h3>
<?php 
		global $editablelinkforadmin;
		if ($editablelinkforadmin)
		{
			echo "<b>Edit: </b><a href=\"/backend/editArticles.php?id=$articleId\" target=\"_new\">Edit Article</a><br/>\n";
		}
		
		// get pretty header photo
		drawHeaderPic('articles', $link, $pageTitle);
		
		$descriptionTitles = getDescriptionTitles($description);
		$articleSources = getObjectSources('article', $articleId, '');
		
		if($photos != '')
		{
			$descriptionTitles = addDescriptionTitles($descriptionTitles, 'photos');
		}
		if (!$articleSources)
		{
			$descriptionTitles = addDescriptionTitles($descriptionTitles, 'sources');
		}
		
		printDescriptionTitles($descriptionTitles);
		drawFormattedText($description);
		
		if($photos != '')
		{
			include_once("common/gallery-functions.php");
			getLocationImages($photos,$photos);
		}
		
		// draw credits previously formatted by drawObjectSources()
		if ($articleSources != '')
		{
			echo $articleSources;
		}
		include_once("footer.php");
	}
	else
	{
		draw404InvalidSubpage('articles','Article');
	}
}

function drawAllArticles($type)
{
	$pageTitle = $pageTitleArticles = ucfirst($type).'s Listing';
	include_once("header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Articles</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php
	include_once("midbar.php");
?>
<h3><?php echo $pageTitleArticles?></h3>
<?php 
	$articles = query_full_array("SELECT * FROM articles WHERE link != '' AND `line_id` = '0'");

	for ($i = 0; $i < sizeof($articles); $i++)
	{
		echo '<h4><a href="/'.$type.'/'.stripslashes($articles[$i]["link"]).'">'.stripslashes($articles[$i]["title"]).'</a></h4>';
		echo '<p class="details">'.stripslashes($articles[$i]["description"]).'</p>';
	}
	include_once("footer.php");
}
?>