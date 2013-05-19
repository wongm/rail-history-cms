 <?php $pageTitle = "Search results";
include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <? echo $pageTitle; ?></div>
	<div class="search"><? drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("common/midbar.php"); ?>
<h3><? echo $pageTitle; ?></h3>
<p>You are currently searching this site via Google: you can also <a href="/gallery/page/search/?words=<?=$_GET['q']?>">search the Gallery</a>.</p>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript" src="/common/js/googlesearch.js"></script>
<div id="results">Loading...</div>
<input style="display:none" id="hidden-input" />
<?php
include_once("common/footer.php"); 
?>