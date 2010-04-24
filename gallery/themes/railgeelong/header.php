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
require_once("functions-railgeelong.php");
include_once('functions-gallery-formatting.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Rail Geelong - <?php printGalleryTitle(); echo($pageTitle); ?></title>
<link rel="stylesheet" type="text/css" href="/common/style.css" media="all" title="Normal" />
<link rel="stylesheet" href="<?= $_zp_themeroot ?>/zen.css" type="text/css" />
<script type="text/javascript" src="/common/lightbox.js"></script>
<?php zenJavascript(); ?>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
<? printRSSHeaderLink("Gallery", "Recent uploads"); ?>
</head>
<body>
<div id="container">
<div id="header">
<h1><a href="/" alt="Home" title="Home">Rail Geelong</a> - Gallery</h1>
</div>
<?php printAdminToolbox(); ?>
<div id="contentwrapper">
<div id="content">
<div id="main">