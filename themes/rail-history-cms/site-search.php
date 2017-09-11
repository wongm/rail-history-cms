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
<script>
  (function() {
    var cx = '013092749367948215647:livsqef13p8';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search>
<?php
include_once("common/footer.php"); 
?>