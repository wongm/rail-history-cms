</div></div>
<div id="navigation">
<?php include_once("nav.php"); ?>
</div>
<div id="footer">
<a href="/index.php">Home</a> :: <a href="/sitemap.php">Sitemap</a> :: <a href="/copyright.php">Copyright</a> :: <a href="/contact.php">Contact</a><br/>
<?php 	//display page generation time
	// start $time = round(microtime(), 3);
$time2 = round(microtime(), 3);
$generation = str_replace('-', '', $time2 - $time);
echo "Page Generation: $generation seconds.<br/>";?>
Copyright 2005 - <?php echo date('Y') ?> &copy; <a href="http://wongm.com">Marcus Wong</a> except where otherwise noted.
</div>
</div>
</body>
</html>
