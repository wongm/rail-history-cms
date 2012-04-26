<ul class="sitemenu">
	<li class="menu"><a href="/news">News</a></li>
	<li class="menu"><a href="/updates">Updates</a></li>
</ul>
<ul class="sitemenu">
	<li class="menu"><a href="/lineguides"><b>Line Guides</b></a></li>
<? 

global $pageNavigation;

$regions = array(
		array('geelong', 'Geelong Region'), 
		array('melbourne', 'Melbourne Region'), 
		array('south-west', 'South West Victoria'), 
		array('western', 'Western Victoria'));

foreach ($regions as $region)
{
	echo '<li class="menu"><a href="/region/' . $region[0] . '">' . $region[1] . '</a></li>';
	
	//echo '<pre>';
	//print_r($pageNavigation);
	
	// if the region for the current line is the same as the one displayed
	if ($region[0] == $pageNavigation['regions'][0])
	{
		// loop through all lines
		// skip the region field
		for ($i = 0; $i < sizeof($pageNavigation) - 1; $i++)
		{
			echo '<li class="submenu menu"><a href="' . $pageNavigation[$i]['url'] . '">' . $pageNavigation[$i]['title'] . '</a></li>';
		}
		
		// loop through all sub pages
		// minus 2 for URL and title attribs
		for ($i = 0; $i < sizeof($pageNavigation[0]) - 2; $i++)
		{
			echo '<li class="subsubmenu menu"><a href="' . $pageNavigation[0][$i]['url'] . '">' . $pageNavigation[0][$i]['title'] . '</a></li>';
		}
	}
}
/*
	<li class="menu lmenu"><a href="/lineguide/geelong">Geelong Line</a></li>
	<li class="menu lmenu"><a href="/location/maribyrnong-river-junction">Maribyrnong River Line</a></li>
	<li class="menu lmenu"><a href="/location/power-station-and-oil-lines">Power Station and Oil Lines</a></li>
	<li class="menu lmenu"><a href="/location/brooklyn-line">Brooklyn Line</a></li>
	<li class="menu lmenu"><a href="/location/williamstown-line">Williamstown Line</a></li>
	<li class="menu lmenu"><a href="/lineguide/altona">Altona Line</a></li>
	<li class="menu lmenu"><a href="/lineguide/corio-independent-goods-line">CIGL / GRAIP</a></li>
	<li class="menu lmenu"><a href="/lineguide/fyansford">Fyansford Line</a></li>
	<li class="menu lmenu"><a href="/lineguide/cunningham-pier">Cunningham Pier</a></li>
	<li class="menu lmenu"><a href="/lineguide/queenscliff">Queenscliff Line</a></li>
	<li class="menu lmenu"><a href="/lineguide/geelong-racecourse">Geelong Racecourse</a></li>
	<li class="menu lmenu"><a href="/lineguide/mortlake">Mortlake</a></li>*/?>
</ul>
<ul class="sitemenu">
	<li class="menu"><a href="/locations"><b>Locations</b></a></li>
	<li class="menu"><a href="/locations/stations">Stations</a></li>
	<li class="menu"><a href="/locations/industries">Industries</a></li>
	<li class="menu"><a href="/locations/signalboxes">Signal Boxes</a></li>
	<li class="menu"><a href="/locations/yards">Yards</a></li>
	<li class="menu"><a href="/locations/misc">Miscellaneous</a></li>
</ul>
<ul class="sitemenu">
	<li class="menu"><a href="/articles"><b>Articles</b></a></li>
</ul>
<ul class="sitemenu">
	<li class="menu"><a href="/aerial.php"><b>Aerial View</b></a></li>
	<li class="menu"><a href="/aerial.php?section=overview">Help</a></li>
</ul>
<ul class="sitemenu">
	<li class="menu"><a href="/gallery/"><b>Gallery</b></a></li>
	<li class="menu"><a href="/gallery/recent">Recent uploads</a></li>
</ul>
<ul class="sitemenu">
	<li class="menu"><a href="/contact.php">Contact</a></li>
	<li class="menu"><a href="/credits.php">Credits</a></li>
	<li class="menu"><a href="/sources.php">Sources</a></li>
	<li class="menu"><a href="/sitemap.php">Sitemap</a></li>
</ul>