</div></div>
<div id="navigation">
<?php 
require_once("nav.php"); ?>
</div>
<div id="footer">
<a href="/index.php">Home</a> :: <a href="/sitemap.php">Sitemap</a> :: <a href="/copyright.php">Copyright</a> :: <a href="/contact.php">Contact</a>
<br/>
<?php 		
//display page generation time
global $startTime;
$endTime = explode(' ',microtime());
$endTime = $endTime[1] + $endTime[0];
$generation = round($endTime - $startTime, 3);
?>
Page Generation: <?php echo $generation?> seconds.<br/>
<?php
if ($lastUpdatedDate != '')
{
	echo "Last updated $lastUpdatedDate<br/>\n";
}
?>
Copyright 2005 - <?php echo date('Y') ?> &copy; <a href="http://wongm.com">Marcus Wong</a> except where otherwise noted.
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
