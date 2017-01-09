<?php
// don't display errors
$time = round(microtime(), 3);
$server = $_SERVER['SERVER_NAME'];
if ($server == 'localhost' OR isset($_GET['wongm']))
{
	error_reporting(E_ALL - E_NOTICE);
}
else
{
	error_reporting(0);
}

$showRailHistoryCmsRssLink = false;
$showGalleryRssLink  = false;

// work out the page title
$currentPage = isset($_GET['p']) ? $_GET['p'] : "";
switch ($currentPage)
{
	case 'news':
		$localPageTitle = " - News";
		$pageHeading = $localPageTitle;
		$showRailHistoryCmsRssLink = true;
		break;
		
	case 'locations':
	case 'locations-home':
	case 'location':
	case 'lineguides':
	case 'lineguide':
	case 'regions':
	case 'region':
	case 'articles':
	case 'article':
	case 'aerial':
	case 'site-search':
	case 'sitemap':
	case 'updates':
		$showRailHistoryCmsRssLink = true;
		
	case 'contact':
		$localPageTitle = " - $pageTitle";
		$pageHeading = $localPageTitle;
		break;

	default:
		$localPageTitle = " - Gallery" . (isset($pageTitle) ? $pageTitle : "");
		$pageHeading = " - Gallery";
		$showGalleryRssLink = true;
}
		
if (!isset($_GET['album']) && $currentPage == '') 
{
	$localPageTitle = " - Welcome";
	$pageHeading = $localPageTitle;
	$showRailHistoryCmsRssLink = true;
}

$localPageTitle = getGalleryTitle() . $localPageTitle;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $localPageTitle; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $_zp_themeroot ?>/css/style.css" media="all" title="Normal" />
<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/css/zen.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_zp_themeroot ?>/js/functions.js"></script>
<script type="text/javascript" src="<?php echo $_zp_themeroot ?>/js/lightbox.js"></script>
<?php zp_apply_filter('theme_head') ?>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<meta name="description" content="<?php echo getGalleryDesc();?>" />
<?php
if ($showRailHistoryCmsRssLink)
{
	echo '<link rel="alternate" type="application/rss+xml" title="Recently updated pages" href="' . PROTOCOL . '://' . html_encode($_SERVER["HTTP_HOST"]) . '/rss-feed" />';
}
else if ($showGalleryRssLink)
{
	printRSSHeaderLink("AlbumsRSS", "Recent gallery uploads");
}
//facebook headers for image.php
if (getImageThumb())
{
	printFacebookTag();
}
?>
</head>
<body>
<div id="container">
<div id="header">
	<div id="sitename"><h1><a href="/" title="Home"><?php echo getGalleryTitle();?></a></h1></div>
	<div id="sitedesc"><?php echo getGalleryDesc();?></div>
	<div style="clear:both;"></div>
</div>
<?php zp_apply_filter('theme_body_close'); ?>
<div id="contentwrapper">