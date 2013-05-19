<?php 
/*
 * Present the different types of ordered photo pages
 * - highest this month
 * - highest this week
 * - highest of all time
 * - highest ranking
 * - show most recent uploaded photos
 *
 */ 
$startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

require_once("functions-railgeelong.php");

// set up variables
$recentPageNumber = $_REQUEST['page'];
$pageType = $_REQUEST['type'];
$pageTypeModifier = $_REQUEST['period'];
$start = $_REQUEST['start'];
$count = $_REQUEST['count'];

// various modifiers for the recent upload pages
if (isset($_REQUEST['double']))
{
	$pageTypeModifier = 'double';
}

if (isset($_REQUEST['caption']))
{
	$pageTypeModifier = $_REQUEST['caption'];
}

// used when drawing out the galllery item later on
$galleryType = $pageTypeModifier;

// draw gallery of hitcounter or rating based ranked pages
if ($pageType == 'popular')
{
	$pageTitle = $popularImageText[$pageTypeModifier]['title'];
	$leadingIntroText = $popularImageText[$pageTypeModifier]['text'];
	$nextURL = $popularImageText[$pageTypeModifier]['url'];
	$pageBreadCrumb = "<a href=\"".POPULAR_URL_PATH."\" title=\"Popular photos\">Popular photos</a>
		&raquo; <a href=\"$nextURL\" title=\"$leadingIntroText\">$leadingIntroText</a>";
	
	if ($pageTypeModifier == 'ratings')
	{
		$trailingIntroText = '. '.RATINGS_TEXT;
	}
}
// get date based ranked pages
else if ($pageType == '')
{
	$nextURL = UPDATES_URL_PATH;
	$leadingIntroText = $pageTitle = 'Recent uploads';
	$rssType = 'Gallery';
	$rssTitle = 'Recent uploads';
	
	$pageBreadCrumb = "<a href=\"$nextURL\" title=\"Recent uploads\">Recent uploads</a>";
	
	if ( zp_loggedin() ) {
		$adminOnlyText = '<p><a href="'.$nextURL.'/?caption=images">Uncaptioned images</a><br/>
			<a href="'.$nextURL.'/?caption=albums">Albums with uncaptioned images</a><br/>
			<a href="'.$nextURL.'/?double=">Duplicate images</a></p>';
	}
}

$pageTitle = " - $pageTitle";
include_once('header.php'); 
require_once("functions-search.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; <?=$pageBreadCrumb?></div>
	<div class="search"><? drawHeadbarSearchBox(true); ?></div>
</div>
<?php include_once('midbar.php'); ?>
<div class="topbar">
	<h3><?=$leadingIntroText?></h3>
</div>
<?
if (!is_numeric($recentPageNumber) OR $recentPageNumber < 1)
{
	$recentPageNumber = 1;
}

if ($recentPageNumber == '' OR $recentPageNumber <= 1 OR !is_numeric($recentPageNumber))
{
	$currentImageResultIndex = 0;
}
else
{
	$currentImageResultIndex = ($recentPageNumber*MAXIMAGES_PERPAGE)-MAXIMAGES_PERPAGE;
}

// get gallery results, number of records, total number of records, and modified value of the next URL
$galleryResults = getGalleryUploadsResults($pageType, $pageTypeModifier, $nextURL, $start, $count, $currentImageResultIndex);

if ($galleryResults['galleryResultCount'] == 0 AND $recentPageNumber != 'fail')
{
	$currentImageResultIndex = 0;//getRecent('fail');
}
else
{
	echo "<p>$leadingIntroText, photos ".getNumberCurrentDispayedRecords(MAXIMAGES_PERPAGE, $galleryResults['galleryResultCount'], $recentPageNumber-1)."$trailingIntroText</p>";
	echo $adminOnlyText;
	
	drawImageGallery($galleryResults['galleryResult'], $galleryType);
	galleryPageNavigationLinks($currentImageResultIndex, $galleryResults['maxImagesCount'], $galleryResults['galleryResultCount'], $galleryResults['nextURL']);
}
include_once('footer.php'); 
?>