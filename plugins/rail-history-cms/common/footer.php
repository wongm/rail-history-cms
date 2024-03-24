</div></div>
<div id="navigation">
<?php include_once("nav.php"); ?>
</div>
<div id="footer">
<a href="/index.php">Home</a> :: <a href="/sitemap.php">Sitemap</a> :: <a href="/copyright.php">Copyright</a> :: <a href="<?php echo CONTACT_URL_PATH ?>">Contact</a><br/>
<?php 	
//display page generation time
global $time;
$generation = number_format(microtime(true) - $time, 2);
echo "Page Generation: $generation seconds.<br/>";?>
Copyright 2005 - <?php echo date('Y') ?> &copy; <a href="http://wongm.com">Marcus Wong</a> except where otherwise noted.
</div>
</div>
<?php zp_apply_filter('theme_body_close'); ?>
</body>
</html>
