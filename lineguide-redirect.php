<?php

$line = str_replace(' ', '-', $_REQUEST['line']);
$line = str_replace('%20', '-', $line);
$sort = $_REQUEST['sort'];
$page = $_REQUEST['page'];
$year = substr($_REQUEST['year'], 0, 4);
$section = $_REQUEST['section'];

if ($year != '')
{
	
	if ($page != '')
	{
		$url = "/lineguide/$line/$section/page-$page/year-$year";
	}
	else
	{
		$url = "/lineguide/$line/$section/year-$year";
	}
}
elseif ($section != '')
{
	$url = "/lineguide/$line/$section";
	
	if ($sort != '')
	{
		$url = $url."/by-".$sort;
	}
}
else
{
	$url = "/lineguide/$line";
}

header("Location: ".$url,TRUE,301);

?>