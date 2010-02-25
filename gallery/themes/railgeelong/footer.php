</div>
</div></div>
<div id="navigation">
<?php include_once($_SERVER["DOCUMENT_ROOT"] . "/common/nav.php"); ?>
</div>
<div id="footer">
<a href="/index.php">Home</a> :: <a href="/sitemap.php">Sitemap</a> :: <a href="/copyright.php">Copyright</a> :: <a href="/contact.php">Contact</a><br/>
<?php 	//display page generation time
	// start $time = round(microtime(), 3);
$time2 = round(microtime(), 3);
$generation = str_replace('-', '', $time2 - $time);
echo "Page Generation: $generation seconds.<br/>";?>
Copyright 2006 - 2007 &copy; Marcus Wong except where otherwise noted.
</div>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7118898-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>