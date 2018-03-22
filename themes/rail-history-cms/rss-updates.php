<?php
// force UTF-8 Ø
require_once(dirname(dirname(dirname(__FILE__))).'/zp-core/global-definitions.php');
require_once(dirname(dirname(dirname(__FILE__)))."/".ZENFOLDER . "/template-functions.php");

require_once("common/definitions.php");
require_once("common/updates-functions.php");
require_once("common/formatting-functions.php");

$updatedPages = getUpdatedPages(0, 20);
$updatedLocations = $updatedPages["result"];

$host = htmlentities($_SERVER["HTTP_HOST"], ENT_QUOTES, 'UTF-8');
$protocol = SERVER_PROTOCOL;
$baseUrl = $protocol . '://' . $host;

header('Content-Type: application/xml');
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title>Rail Geelong - Updated content</title>
<link>http://www.railgeelong.com</link>
<atom:link href="<?php echo $baseUrl; ?>/rss.php" rel="self" type="application/rss+xml" />
<description>Recent updates to lineguides, locations and articles</description>
<language>en-AU</language>
<pubDate><?php echo date("r", time()); ?></pubDate>
<lastBuildDate><?php echo date("r", time()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>Rail Geelong RSS Generator</generator>
<?php
$j = 0;
$pastItemLink = -1;
for ($i = 0; $i < sizeof($updatedLocations); $i++)
	{	
		if ($j%2 == '0')
		{
			$style = 'class="odd"';
		}
		else
		{
			$style = 'class="even"';
		}
		
		$date = $updatedLocations[$i]["fdate"];
		$objectid = $updatedLocations[$i]["object_id"];
		$locationlink = $updatedLocations[$i]["link"];
		$name = stripslashes($updatedLocations[$i]["name"]);
		$objecttype = $updatedLocations[$i]["object_type"];
		$locationtype = $updatedLocations[$i]["type"];
		$length = $updatedLocations[$i]["length"];
		$events = $updatedLocations[$i]["events"];
		$desc = stripslashes($updatedLocations[$i]["length"]);
		
		$itemlink = $objectid;
		
		$desc = strip_tags(getFormattedText(truncateString($desc, 500), true));
		$date = date("r", strtotime($date) + (60*60*19));
		
		switch ($objecttype)
		{
			case 'L':
				$path = 'Location';
				$name = getLocationName($name, $locationtype);
				if (strlen($locationlink) > 0) {
					$itemlink = $locationlink;
				}
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
		
		$urlText = "$baseUrl/".strtolower($path).'/'.$itemlink;
		
		// skip location if has already been displayed, when on multiple lines it has the same ID
		if ($itemlink != $pastItemLink)
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
		
		$pastItemLink = $itemlink;
	}	// end for loop
?>
</channel>
</rss>