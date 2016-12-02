<ul class="sitemenu">
	<li class="lead"><a href="/">Home</a></li>
	<li><a href="/news/">News</a></li>
	<li><a href="/updates/">Updates</a></li>
</ul>
<ul class="sitemenu">
	<li class="lead"><a href="/lineguides/">Line Guides</a></li>
<?php 

global $pageNavigation;

$regions = array(
		array('geelong', 'Geelong Region'), 
		array('melbourne', 'Melbourne Region'), 
		array('south-west', 'South West Victoria'));//, 
		//array('western', 'Western Victoria'));

foreach ($regions as $region)
{
	echo '<li><a href="/region/' . $region[0] . '/">' . $region[1] . "</a></li>\n";
	
	// if the region for the current line is the same as the one displayed
	if ($region[0] == $pageNavigation['regions'][0])
	{
		echo "<ul class=\"submenu\">\n";
		
		// loop through all lines
		// skip the region field
		for ($i = 0; $i < sizeof($pageNavigation) - 1; $i++)
		{
			echo '<li><a href="' . $pageNavigation[$i]['url'] . '">' . $pageNavigation[$i]['title'] . "</a></li>\n";
		}
				
		echo "<ul class=\"subsubmenu\">\n";
		
		// loop through all sub pages
		// minus 2 for URL and title attribs
		for ($i = 0; $i < sizeof($pageNavigation[0]) - 2; $i++)
		{
			echo '<li><a href="' . $pageNavigation[0][$i]['url'] . '">' . $pageNavigation[0][$i]['title'] . "</a></li>\n";
		}
		// end class subsubmenu
		echo "</ul>\n";
		
		// end class submenu
		echo "</ul>\n";
	}
} ?>
</ul>
<ul class="sitemenu">
	<li class="lead"><a href="/locations/">Locations</a></li>
	<li><a href="/locations/stations/">Stations</a></li>
	<li><a href="/locations/industries/">Industries</a></li>
	<li><a href="/locations/signalboxes/">Signal Boxes</a></li>
	<li><a href="/locations/yards/">Yards</a></li>
	<li><a href="/locations/misc/">Miscellaneous</a></li>
</ul>
<ul class="sitemenu">
	<li class="lead"><a href="/articles/">Articles</a></li>
</ul>
<ul class="sitemenu">
	<li class="lead"><a href="/aerial.php">Aerial View</a></li>
</ul>
<ul class="sitemenu">
	<li class="lead"><a href="/gallery/">Gallery</a></li>
	<li><a href="/gallery/recent/">Recent uploads</a></li>
</ul>
<ul class="sitemenu">
	<li><a href="<?php echo CONTACT_URL_PATH ?>">Contact</a></li>
	<li><a href="<?php echo CREDITS_URL_PATH ?>">Credits</a></li>
	<li><a href="<?php echo SOURCES_URL_PATH ?>">Sources</a></li>
	<li><a href="<?php echo SITEMAP_URL_PATH ?>">Sitemap</a></li>
</ul>