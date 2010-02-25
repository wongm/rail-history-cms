<?php
error_reporting(0);
include_once("common/formatting-functions.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Rail Geelong - Undergoing Maintenance</title>
<link rel="stylesheet" type="text/css" href="/common/style.css" media="all" title="Normal" />
<script src="/common/functions.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
</head>
<body>
<table id="container">
<tr><td id="header" colspan="2">
	<h1><a href="/" alt="Home" title="Home">Rail Geelong</a> - Undergoing Maintenance</h1>
</td></tr>
<tr><td id="big" valign="top">
<div id="content">
<?php
$caption = "Random image - Collect them all!";
drawHeaderPic('headers', rand(1,23), $caption);

$file = filemtime(".htaccess");
$start = date("l, dS F, Y @ h:ia", $file);
$end = date("h:00a", $file + (10*60*60)+(30*60));
?>
<h3 class="intro">Rail Geelong - hopefully everything you ever wanted to know about the history of the railways of Geelong and District, and then some.</h3>
<h3 class="intro" align="center"><font color="red">Sorry, the site is currently offline for maintenance.</font></h3>
<h4 class="intro" align="center">Work started on <?=$start?> and should hopefully be complete with a few hours, hopefully by <?=$end?> at the latest.</h4>
</div>
<div id="footer">
<a href="/index.php">Home</a> :: <a href="/sitemap.php">Sitemap</a> :: <a href="/copyright.php">Copyright</a> :: <a href="/contact.php">Contact</a>
<br/><br/>Last updated <?=$start?>
<br/>Copyright 2005 - <?=date('Y') ?> © Marcus Wong except where otherwise noted.
</div>
</td></tr>
</table>
</body>
</html>