<?php
// don't display errors
$server = $_SERVER['HTTP_HOST'];
if ($server == 'z' OR $server == 'localhost' OR isset($_GET['wongm']))
{
	///error_reporting(E_ERROR);
}
else
{
	error_reporting(0);
}

// work out the page title
switch ($_GET['p'])
{
	case 'news':
		$pageTitle = " - News";
		$pageHeading = $pageTitle;
		$newsRSS = true;
		break;
		
	case 'home':
		$pageTitle = " - Welcome";
		$pageHeading = $pageTitle;
		$railGeelongRSS = true;
		break;
		
	case 'locations':
	case 'lineguides':
	case 'regions':
		$pageTitle = " - $pageTitle";
		$pageHeading = $pageTitle;
		$railGeelongRSS = true;
		break;

	default:
		$pageTitle = " - Gallery" . $pageTitle;
		$pageHeading = " - Gallery";
		$galleryRSS = true;
}

$pageTitle = "Rail Geelong" . $pageTitle;

require_once("functions-railgeelong.php");
include_once('functions-gallery-formatting.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $pageTitle; ?></title>
<link rel="stylesheet" type="text/css" href="/common/style.css" media="all" title="Normal" />
<link rel="stylesheet" href="<?= $_zp_themeroot ?>/zen.css" type="text/css" />
<script type="text/javascript" src="/common/lightbox.js"></script>
<?php zenJavascript(); ?>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
<?php
if ($galleryRSS)
{
	printRSSHeaderLink("AlbumsRSS", "Recent gallery uploads");
}
else if ($railGeelongRSS)
{
	echo '<link rel="alternate" type="application/rss+xml" title="Recently updated pages" href="/rss" />';
}
else if ($newsRSS)
{
	printZenpageRSSHeaderLink('News', '', 'Recent news updates', null);
}
?>
</head>
<body>
<div id="container">
<div id="header">
	<div id="sitename"><h1><a href="/" alt="Home" title="Home">Rail Geelong</a></h1></div>
	<div id="sitedesc">A history of the railways of Geelong and District.</div>
	<div style="clear:both;">
</div>
<?php printAdminToolbox(); ?>
<div id="contentwrapper">
<div id="content">
<div id="main">