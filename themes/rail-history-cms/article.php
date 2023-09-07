<?php 

require_once("common/definitions.php");
require_once("common/formatting-functions.php");
require_once("common/source-functions.php");
require_once("common/map-functions.php");

$link = $_REQUEST['name'];
$link = str_replace('/', '', $link);
$article = query_full_array("SELECT *, DATE_FORMAT(modified, '%M %e, %Y') AS fmodified, DATE_FORMAT(added, '%M %e, %Y') AS fadded FROM articles WHERE `link` = '$link'");

if (sizeof($article) == 1)
{
	$pageTitle = stripslashes($article[0]["title"]);
	$articleId = stripslashes($article[0]["article_id"]);
	$description = stripslashes($article[0]["content"]);
	$photos = stripslashes($article[0]["photos"]);
	$articleSources = getObjectSources('article', $articleId, '');
	$modified = $article[0]["fmodified"]; 
	$added = $article[0]["fadded"]; 
	
	$dateString = "";
	if (strlen($added) > 0) 
	{
		$dateString = "<p>Published: " . $added;
		if (strlen($modified) > 0 && $modified != $added) 
		{
			$dateString .= " (Last updated: " . $modified . ")";
		}
		$dateString .= "</p>";
	}
	
	$mapKMLfile = parseDescriptionForMap($description);
	$mapJS = "";
	
	if ($mapKMLfile)
	{
		$description = replaceMapElement($description, $mapKMLfile);
		$mapJS.= generateKMLScript("/images/kml/" . $mapKMLfile, 0);
	}
	
	require_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/articles/">Articles</a> &raquo; <?php echo $pageTitle?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once("common/midbar.php"); ?>
<h3><?php echo $pageTitle?></h3>
<?php 
	drawAdminEditableLink("editArticles.php?id=$articleId", "Edit Article");
	//echo $dateString;
	
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
	echo $mapJS;
	drawFormattedText($description);
	
	if($photos != '')
	{
		require_once("common/linked-photo-functions.php");
		if (getLinkedPhotoCount($photos) > 0)
		{
    		drawLinkedPhotosFromGallery($photos);
		}
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
	draw404InvalidSubpage('articles','Article');
}
?>