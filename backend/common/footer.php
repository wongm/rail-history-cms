</div><div id="footer">
<a href="/index.php">Home</a> :: <a href="/sitemap.php">Sitemap</a> :: <a href="/copyright.php">Copyright</a> :: <a href="/contact.php">Contact</a><br/>
<?php 	//display page generation time
	// start $time = round(microtime(), 3);
$time2 = round(microtime(), 3);
$generation = str_replace('-', '', $time2 - $time);
echo "Page Generation: $generation seconds.<br/>";?>
Copyright 2006 - 2007 &copy; Marcus Wong except where otherwise noted.<br/><br/>
</div>
<div id="navigation">
<!-- http://www.projectseven.com/tutorials/css/uberlinks/index.htm -->
<?php 
if ($_SESSION['authorised'])
{	
	include_once("nav.php");
}
else
{
	echo '<ul class="sitemenu"><li class="menu"><a href="/">Home</a></li></ul>';
}
?>
</div>
</td></tr>
</table>
</body>
</html>
