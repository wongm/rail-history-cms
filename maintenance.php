<?php
error_reporting(0);
include_once("common/formatting-functions.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Rail Geelong - Undergoing Maintenance</title>
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all" title="Normal" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="/common/js/functions.js"></script>
<script type="text/javascript" src="/common/js/frontpage.js"></script>
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
<tr><td valign="top">
<div id="content">
<div id="headerpane" class="photo-right">
<img id="randomimage" height="267" width="400" alt="Random image" title="Random image" />
<span id="randomcaption">Random image</span>
</div>
<?php

$file = filemtime(".htaccess");
$start = date("l, dS F, Y @ h:ia", $file);
$end = date("h:00a", $file + (24*60*60)+(30*60));
?>
<h3 class="intro">Rail Geelong - hopefully everything you ever wanted to know about the history of the railways of Geelong and District, and then some.</h3>
<h3 class="intro" align="center"><font color="red">Sorry, the site is currently offline for maintenance.</font></h3>
<h4 class="intro" align="center">Work started on <?=$start?> and should hopefully be complete with a few hours, hopefully by <?=$end?> at the latest.</h4>
</div>
<div id="footer">
Last updated <?=$start?>
<br/>Copyright 2005 - <?=date('Y') ?> © Marcus Wong except where otherwise noted.
</div>
</td></tr>
</table>
</body>
</html>