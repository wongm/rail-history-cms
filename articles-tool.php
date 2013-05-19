<?php 

include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/source-functions.php");
include_once("common/map-functions.php");
	
$link = $_REQUEST['name'];

if ($link == '')
{
	drawAllArticles('article');
}
// for a specific article
else
{
	$article = MYSQL_QUERY("SELECT *, DATE_FORMAT(modified, '%M %e, %Y') AS fdate FROM articles WHERE `link` = '$link'");
	
	if (MYSQL_NUM_ROWS($article) == '1')
	{
		$pageTitle = stripslashes(MYSQL_RESULT($article,0,"title"));
		$articleId = stripslashes(MYSQL_RESULT($article,0,"article_id"));
		$description = stripslashes(MYSQL_RESULT($article,0,"content"));
		$photos = stripslashes(MYSQL_RESULT($article,0,"photos"));
		$articleSources = getObjectSources('article', $articleId, '');
		$lastUpdatedDate = MYSQL_RESULT($article,0,"fdate"); 
		
		$mapKML = parseDescriptionForMap($description);
		
		if ($mapKML)
		{
			$googleHeader = 'article';
			$googleHeaderKMLscript = generateKMLScript($mapKML);
			$description = insertMapElement($description, $mapKML);
		}
		
		include_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/articles">Articles</a> &raquo; <?=$pageTitle?></div>
	<div class="search"><? drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("common/midbar.php"); ?>
<h3><?=$pageTitle?></h3>
<?	
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
		include_once("common/footer.php");
	}
	else
	{
		draw404InvalidSubpage('articles','Article');
	}
}
?>