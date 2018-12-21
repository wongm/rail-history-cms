<?php 

//start timer
$time = round(microtime(), 3);

if(!isset($pageTitle) || $pageTitle == '')
{
	$pageTitle = "Site Management";
}
if(!isset($pageHeading) || $pageHeading == '')
{
	$pageHeading = $pageTitle;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Rail History CMS - <?php echo $pageTitle;?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $_zp_themeroot ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" media="all" title="Normal" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="<?php echo $_zp_themeroot ?>/js/functions.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<?php zp_apply_filter('theme_head') ?>
</head>
<body>
<div id="container">
<div id="header">
	<div id="sitename">
		<h1><a href="index.php" alt="Admin Home" title="Admin Home">Rail History CMS</a> - <?php echo $pageHeading; ?></h1>
	</div>
</div>
<div id="contentwrapper">
<div id="content">