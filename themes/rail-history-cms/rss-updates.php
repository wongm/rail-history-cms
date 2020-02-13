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
<atom:link href="<?php echo $baseUrl; ?>/page/rss-updates" rel="self" type="application/rss+xml" />
<description>Recent updates to lineguides, locations and articles at Rail Geelong</description>
<language>en-AU</language>
<pubDate><?php echo date("r", time()); ?></pubDate>
<lastBuildDate><?php echo date("r", time()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>Rail Geelong RSS Generator</generator>
<?php
$j = 0;
$pastitemLink = -1;
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
		$itemLink = $updatedLocations[$i]["object_id"];
		$locationlink = $updatedLocations[$i]["link"];
		$name = stripslashes($updatedLocations[$i]["name"]);
		$objectTypeAbbr = $updatedLocations[$i]["object_type"];
		$locationtype = $updatedLocations[$i]["type"];
		$date = date("r", strtotime($date) + (60*60*19));
		$objectTypeDescription = ' page';
		
		switch ($objectTypeAbbr)
		{
			case 'L':
				$objectType = 'location';
				$name = getLocationName($name, $locationtype);
				if (strlen($locationlink) > 0) {
					$itemLink = $locationlink;
				}
				break;
			case 'RL':
				$objectType = 'lineguide';
				$name = getLineName($name);
				break;
			case 'A':
				$objectType = 'article';
				$objectTypeDescription = '';
				break;	
			case 'R':
				$objectType = 'region';
				break;
		}
		
		$addedDate = explode(" ", $updatedLocations[$i]["added"])[0];
		$modifiedDate = explode(" ", $updatedLocations[$i]["modified"])[0];
		$newItem = ($addedDate == $modifiedDate && $addedDate != "");
		$addedText = $newItem ? "Added new " : "Updated ";
		
		if ($newItem) {
			$desc = "A new page for the $name $objectType has been created";
		} else {
			$desc = "The $name $objectType $objectTypeDescription has been updated";
		}
		
		$title = "$addedText$objectType: $name";
		$urlText = "$baseUrl/".strtolower($objectType).'/'.$itemLink;
		
		// skip location if has already been displayed, when on multiple lines it has the same ID
		if ($itemLink != $pastitemLink)
		{
			$j++;
?>
<item>
	<title><?php echo $title; ?></title>
	<link><![CDATA[<?php echo $urlText; ?>]]></link>
	<description><![CDATA[<?php echo $desc ?>]]></description>
	<category><?php echo $objectType ?></category>
	<guid><![CDATA[<?php echo $urlText; ?>]]></guid>
	<pubDate><?php echo $date; ?></pubDate>
</item>
<?php 	}
		
		$pastitemLink = $itemLink;
	}	// end for loop
?>
</channel>
</rss>