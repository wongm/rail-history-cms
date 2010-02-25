<?php

/*
 * ---------------------------------
 * config settings for this file
 * ---------------------------------
 */
 
// the name of the directory that the theme being used lives in
$theme = 'railgeelong';

require_once("railgeelong-functions.php");
include_once("search-functions.php");

/*
 * ---------------------------------
 * page setup stuff
 * ---------------------------------
 */
$search = $_REQUEST['search'];
$page = $page = $_REQUEST['page'];

$len = strlen($search);
if (substr($search, ($len-1), $len) == '/')
{
	$search = substr($search, 0, ($len-1));
}

$pageTitle = ' - Search';

if (isset($_REQUEST['recent']))
{
	//$headerstuff = ' <a href="/gallery/recent">Recent updates</a>';
	$headerstuff = " Recent updates";
	$pageTitle = 'Gallery Recent Updates';
}
else
{
	$headerstuff = " Search";
	//$headerstuff = ' <a href="/gallery/search">Search</a>';
}

include_once("header.php");
	?>
<link rel="stylesheet" href="/gallery/themes/<?=$theme?>/zen.css" type="text/css" media="all" title="Normal" />
<table class="headbar">
    <tr><td><a href="/">Rail Geelong</a> &raquo; <a href="/gallery/">Gallery</a> &raquo; <?=$headerstuff?></td>
    <td id="righthead"><? printSearchBreadcrumb(); ?></td></tr>
</table>
<div class="topbar">
<?	

if (isset($_REQUEST['recent']))
{
	include_once('../common/dbConnection.php');
	galleryDBconnect();
	
	echo COPYRIGHT."</div>";
	printRecentPage($_REQUEST['recent']);
}
elseif ($search != '')
{
	include_once('../common/dbConnection.php');
	galleryDBconnect();
	
	if($_REQUEST['type'] == 'albums')
	{
		$type = 'Album';
	}
	else
	{
		$type = 'Image';
	}
	
	echo COPYRIGHT."</div>";
	imageOrAlbumSearch($search, $type, $page);
}
// welcome page
else
{
	echo COPYRIGHT."</div>";
	drawImageOrAlbumSearchForm();
}
echo "</div>";
include_once("../common/footer.php"); 
?>