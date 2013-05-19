<?php 
global $editablelinkforadmin, $startTime;

// $pageTitle
// $pageHeading
// $pageNavigation
// $googleHeader
// $googleHeaderKMLscript

// start timer
$startTime = explode(' ',microtime());
$startTime = $startTime[1] + $startTime[0];

// don't display errors
$server = $_SERVER['HTTP_HOST'];
if ($server == 'z' OR $server == 'localhost' OR isset($_GET['wongm']))
{
	$editablelinkforadmin = true;
	error_reporting(E_ALL - E_NOTICE);
}
else
{
	$editablelinkforadmin = false;
	error_reporting(0);
}

// header stuff
if ($pageTitle == '')
{
	$pageTitle = "Geelong and District, Past and Present";
}
if ($pageHeading == "")
{
	$pageHeading = $pageTitle;
}
if (strlen($pageHeading) > 35)
{
	$pageHeading = str_replace('Line Guide', '', $pageHeading);
}

//extra header items when displaying Google maps
if ($googleHeader == 'article')
{
	$googleArticle = true;	
}

// need bits in the body tag as well
if (strlen($googleHeader))
{
	$bodyExtra = ' onload="loadLineguideAll()" onunload="GUnload()"';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Rail Geelong - <?php echo $pageTitle;?></title>
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all" title="Normal" />
<?php if ($googleArticle) { ?>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>
<?php echo $googleHeaderKMLscript ?>
<?php } else if ($googleHeader) { ?>
<link rel="stylesheet" type="text/css" href="/common/css/aerialstyle.css" />
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>
<script src="/common/js/aerialfunctions.js" type="text/javascript"></script></head>
<script src="/common/aerialjavascript.php?lineguide=<?=$line["lineId"]; ?>&link=<?=$line["lineLink"]; ?>" type="text/javascript"></script>
<?php } ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="/common/js/lightbox.js"></script>
<script type="text/javascript" src="/common/js/functions.js"></script>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="A history of the railways of Geelong and District" />
<meta name="keywords" content="railways trains history geelong victoria australia transport" />
<link rel="alternate" type="application/rss+xml" title="Recently updated pages" href="/rss" />
</head>
<body<?php echo $bodyExtra ?>>
<div id="container">
<div id="header">
	<div id="sitename"><h1><a href="/" title="Home">Rail Geelong</a></h1></div>
	<div id="sitedesc">A history of the railways of Geelong and District.</div>
	<div style="clear:both;"></div>
</div>
<?php 
// hack in stuff for zenphoto generated pages
if (function_exists('printAdminToolbox'))
{
	global $editablelinkforadmin;
	$editablelinkforadmin = zp_loggedin();
		
	//assume this function exists
	if (zp_loggedin()) 
	{
		printAdminToolbox();
		zenJavascript();
	}
} ?>
<div id="contentwrapper">