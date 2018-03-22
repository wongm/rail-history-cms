<?php
// force UTF-8 Ø
require_once(dirname(dirname(dirname(__FILE__))).'/zp-core/global-definitions.php');
require_once(dirname(dirname(dirname(__FILE__)))."/".ZENFOLDER . "/template-functions.php");

setCustomPhotostream("", "i.albumid, DATE(i.date)", "i.date DESC");

$host = htmlentities($_SERVER["HTTP_HOST"], ENT_QUOTES, 'UTF-8');
$protocol = SERVER_PROTOCOL;
$baseUrl = $protocol . '://' . $host;

header('Content-Type: application/xml');
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title>Rail Geelong - Uploaded images</title>
<link>http://www.railgeelong.com</link>
<atom:link href="http://www.railgeelong.com/rss.php" rel="self" type="application/rss+xml" />
<description>Recently uploaded images</description>
<language>en-AU</language>
<pubDate><?php echo date("r", time()); ?></pubDate>
<lastBuildDate><?php echo date("r", time()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>Rail Geelong RSS Generator</generator>
<?php
for ($albumCount = 1; $albumCount < 7; $albumCount++) {
	next_photostream_image();
	$imagePath = getDefaultSizedImage();
	$title = "New photos in the " . getAlbumTitleForPhotostreamImage() . " album";
?>
<item>
	<title><?php echo $title; ?></title>
	<link><![CDATA[<?php echo $baseUrl . getAlbumURL(); ?>]]></link>
	<description><![CDATA[<img border="0" src="<?php echo $protocol . '://' . $host . $imagePath; ?>" alt="<?php echo getAlbumTitleForPhotostreamImage() ?>" /><br><?php echo $title; ?>]]></description>
	<guid><![CDATA[<?php echo getAlbumURL() . printImageDate(); ?>]]></guid>
	<pubDate><?php echo printImageDate(); ?></pubDate>
</item>
<?php 
}
?>
</channel>
</rss>