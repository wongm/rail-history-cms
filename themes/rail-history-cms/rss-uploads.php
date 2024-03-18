<?php
$_zp_script_timer['start'] = microtime();
// force UTF-8 Ø

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('next_DailySummaryItem')) {
	exit();
}

// hack to show large images
setOption('image_size', '', false);

global $_zp_db;
$lastModifiedImageDateSQL = "SELECT mtime FROM " . $_zp_db->prefix('images') . " ORDER BY mtime DESC LIMIT 0, 1";
$lastModifiedImageDate = $_zp_db->querySingleRow($lastModifiedImageDateSQL)['mtime'];

header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModifiedImageDate).' GMT', true, 200);
header('Content-Type: application/xml');
$locale = getOption('locale');
$validlocale = strtr($locale,"_","-");
$host = htmlentities($_SERVER["HTTP_HOST"], ENT_QUOTES, 'UTF-8');
$protocol = SERVER_PROTOCOL;
$albumname = "Recent updates by upload date";

NewDailySummary(getOption('RSS_items'));
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title>Rail Geelong - Uploaded images</title>
<link>http://www.railgeelong.com</link>
<atom:link href="<?php echo $protocol; ?>://<?php echo html_encode($_SERVER["HTTP_HOST"]); ?><?php echo html_encode($_SERVER["REQUEST_URI"]); ?>" rel="self"	type="application/rss+xml" />
<description>Recently uploaded images in the Rail Geelong gallery</description>
<language>en-AU</language>
<pubDate><?php echo date("r", time()); ?></pubDate>
<lastBuildDate><?php echo date("r", time()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>Rail Geelong RSS Generator</generator>
<?php while (next_DailySummaryItem()) { 
	global $_zp_current_DailySummaryItem;
	makeImageCurrent($_zp_current_DailySummaryItem->getDailySummaryThumbImage());
	$imagePath = getDefaultSizedImage();
?>
<item>
    <title><?php echo getDailySummaryTitleAndDesc(); ?></title>
    <link><![CDATA[<?php echo $protocol . '://' . $host . getDailySummaryUrl(); ?>]]></link>
    <description><![CDATA[<img border="0" src="<?php echo $protocol . '://' . $host . $imagePath; ?>" alt="<?php echo getDailySummaryTitle() ?>" /><br><?php echo getDailySummaryDesc(); ?>]]></description>
    <guid><![CDATA[<?php echo $protocol . '://' . $host . getDailySummaryUrl(); ?>]]></guid>
    <pubDate><?php echo getDailySummaryDate("%a, %d %b %Y %H:%M:%S %z"); ?></pubDate>
</item>
<?php } ?>
</channel>
</rss>