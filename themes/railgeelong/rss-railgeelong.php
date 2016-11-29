<?php
// force UTF-8 Ø
require_once(dirname(__FILE__).'/zp-core/global-definitions.php');
define('OFFSET_PATH', 0);
require_once(dirname(__FILE__)."/".ZENFOLDER . "/template-functions.php");
require_once(ZENFOLDER . "/class-rss.php");
startRSSCache();

require_once("common/dbConnection.php");
require_once("common/updates-functions.php");
require_once("common/formatting-functions.php");

$updatedPages = getUpdatedPages(0, 20);
$updatedLocations = $updatedPages["result"];
header('Content-Type: application/xml');
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title>Rail Geelong - Updated content</title>
<link>http://www.railgeelong.com</link>
<atom:link href="http://www.railgeelong.com/rss.php" rel="self" type="application/rss+xml" />
<description>Recent updates to lineguides, locations and articles</description>
<language>en-AU</language>
<pubDate><?php echo date("r", time()); ?></pubDate>
<lastBuildDate><?php echo date("r", time()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>Rail Geelong RSS Generator</generator>
<?php

for ($i = 0; $i < MYSQL_NUM_ROWS($updatedLocations); $i++)
	{	
		if ($j%2 == '0')
		{
			$style = 'class="odd"';
		}
		else
		{
			$style = 'class="even"';
		}
		
		$date = MYSQL_RESULT($updatedLocations,$i,"fdate");
		$id = 	MYSQL_RESULT($updatedLocations,$i,"link");
		$name = stripslashes(MYSQL_RESULT($updatedLocations,$i,"name"));
		$objecttype = MYSQL_RESULT($updatedLocations,$i,"object_type");
		$locationtype = MYSQL_RESULT($updatedLocations,$i,"type");
		$length = MYSQL_RESULT($updatedLocations,$i,"length");
		$events = MYSQL_RESULT($updatedLocations,$i,"events");
		$desc = stripslashes(MYSQL_RESULT($updatedLocations,$i,"length"));
		
		$desc = strip_tags(getFormattedText(truncateString($desc, 500), true));
		$date = date("r", strtotime($date) + (60*60*19));
		
		switch ($objecttype)
		{
			case 'L':
				$path = 'Location';
				$name = getLocationName($name, $locationtype);
				break;
			case 'RL':
				$path = 'Lineguide';
				$name = getLineName($name);
				break;
			case 'A':
				$path = 'Article';
				break;	
			case 'R':
				$path = 'Region';
				break;
		}
		
		$urlText = "http://www.railgeelong.com/".strtolower($path).'/'.$id;
		
		// skip location if has already been displayed, when on multiple lines it has the same ID
		if ($id != $pastId)
		{
			$j++;
?>
<item>
	<title><?php echo "$path - $name"; ?></title>
	<link><![CDATA[<?php echo $urlText; ?>]]></link>
	<description><![CDATA[<?php echo $desc ?>]]></description>
	<category><?php echo $path ?></category>
	<guid><![CDATA[<?php echo $urlText; ?>]]></guid>
	<pubDate><?php echo $date; ?></pubDate>
</item>
<?php 	}
		
		$pastId = $id;
	}	// end for loop
?>
</channel>
</rss>
<?php endRSSCache();?>