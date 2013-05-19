<?php 

//start timer
$time = round(microtime(), 3);

// start session
session_start();

// test for localhost
$server = $_SERVER['HTTP_HOST'];
if ($server == 'z' OR $server == 'localhost')
{
	$localhost = true;
	$_SESSION['authorised'] = true;
}

if (!$_SESSION['authorised'])
{
	$url = "/backend/index.php";
	$url = "http://".$_SERVER['HTTP_HOST'].$url;
	header("Location: ".$url,TRUE,302);
}

if($pageTitle == '')
{
	$pageTitle = "Rail Geelong Site Management";
}
if($pageHeading == "")
{
	$pageHeading = $pageTitle;
}

$pageHeading = substr($pageHeading, 0, 40)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Rail Geelong - <?php echo $pageTitle;?></title>
<link rel="stylesheet" type="text/css" href="/common/css/style.css" />
<link rel="stylesheet" type="text/css" href="/backend/common/style.css" media="all" title="Normal" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="/common/js/functions.js" type="text/javascript"></script>
<script src="/backend/common/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script src="/backend/common/jquery.dimensions.js" type="text/javascript"></script>
<script src="/backend/common/jquery.tooltip.js" type="text/javascript"></script>
<script src="/backend/common/jquery.tabs.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
</head>
<body>
<div id="container">
<div id="header">
<h1><a href="/backend/admin.php" alt="Admin Home" title="Admin Home">RG</a> - <?php echo $pageHeading; ?></h1>
<div id="user_info"><p>
<?
if ($localhost)
{
	echo '<a href="/phpmyadmin" target="_blank">phpMyAdmin</a> &nbsp; | &nbsp; ';
}
else
{
	echo '<a href="http://www.railgeelong.com:2082/3rdparty/phpMyAdmin/" target="_blank">phpMyAdmin</a> &nbsp; | &nbsp; ';
}
?>
<a href="/gallery/zp-core/admin.php" target="_blank">Gallery</a> &nbsp; | &nbsp; 
<a href="/backend/index.php">Logout</a></p> 
</div>
</div>
<div id="contentwrapper">
<div id="content">