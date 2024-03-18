</div></div>
<div id="navigation">
<?php require_once("nav.php"); ?>
</div>
<div id="footer">
<a href="/">Home</a> :: <a href="<?php echo SITEMAP_URL_PATH ?>">Sitemap</a> :: <a href="<?php echo COPYRIGHT_URL_PATH ?>">Copyright</a> :: <a href="<?php echo CONTACT_URL_PATH ?>">Contact</a><br/>
<?php 	
//display page generation time
// start $time = round(microtime(), 3);
global $time;
$generation = number_format(microtime(true) - $time, 2);
echo "Page Generation: $generation seconds.<br/>";?>
Copyright 2005 - <?php echo date('Y')?> &copy; <a href="http://wongm.com">Marcus Wong</a> except where otherwise noted.
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