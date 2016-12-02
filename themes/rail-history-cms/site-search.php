 <?php $pageTitle = "Search results";
include_once("common/definitions.php");
include_once("common/formatting-functions.php");
include_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <?php echo $pageTitle; ?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("common/midbar.php"); ?>
<h3><?php echo $pageTitle; ?></h3>
<p>You are currently searching this site via Google: you can also <a href="<?php echo SEARCH_URL_PATH ?>?words=<?php echo $_GET['q']?>">search the Gallery</a>.</p>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $_zp_themeroot ?>/js/googlesearch.js"></script>
<div id="results">Loading...</div>
<input style="display:none" id="hidden-input" />
<?php
include_once("common/footer.php"); 
?>