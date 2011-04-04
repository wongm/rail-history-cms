<?php 
global $editablelinkforadmin, $startTime;

// start timer
$startTime = explode(' ',microtime());
$startTime = $startTime[1] + $startTime[0];

// don't display errors
$server = $_SERVER['HTTP_HOST'];
if ($server == 'z' OR $server == 'localhost' OR isset($_GET['wongm']))
{
	$editablelinkforadmin = true;
	//error_reporting(E_ALL);
}
else
{
	$editablelinkforadmin = false;
	error_reporting(0);
}

// header stuff
if($pageTitle == '')
{
	$pageTitle = "Geelong and District, Past and Present";
}
if($pageHeading == "")
{
	$pageHeading = $pageTitle;
}	

if (strlen($pageHeading) > 35)
{
	$pageHeading = str_replace('Line Guide', '', $pageHeading);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Rail Geelong - <?php echo $pageTitle;?></title>
<link rel="stylesheet" type="text/css" href="/common/style.css" media="all" title="Normal" />
<? if ($googleHeader == 'article') { ?>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>
<?=$googleHeaderKMLscript?>
<? } else if ($googleHeader) { ?>
<link rel="stylesheet" type="text/css" href="/common/aerialstyle.css" />
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>
<script src="/common/aerialfunctions.js" type="text/javascript"></script></head>
<script src="/common/aerialjavascript.php?lineguide=<?=$line["lineId"]; ?>&link=<?=$line["lineLink"]; ?>" type="text/javascript"></script>
<? } ?>
<script src="/common/lightbox.js" type="text/javascript"></script>
<script src="/common/functions.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
<link rel="alternate" type="application/rss+xml" title="Recently updated pages" href="/rss" />
</head>
<? if ($googleHeader) { ?>
<body onload="loadLineguideAll()" onunload="GUnload()">
<? } else { ?>
<body>
<? } ?>
<div id="container">
<div id="header">
<div id="sitename">
	<h1><a href="/" alt="Home" title="Home">Rail Geelong</a> - <?php echo $pageHeading; ?></h1>
</div>
<div id="sitedesc">A history of the railways of Geelong and District.</div>
<div style="clear:both;"></div>
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
<div id="content">