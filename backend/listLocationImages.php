<?php

$pageFilterType = $_REQUEST['type'];
$pageTitle = 'List location header images';
include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT * FROM locations l WHERE ".SQL_NEXTABLE. " ORDER BY modified DESC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0) 
{
?>
<div class="headbar">
	<a href="listLocationImages.php?type=with">With images</a> :: 
	<a href="listLocationImages.php?type=without">Without images</a> :: 
	<a href="listLocationImages.php">All</a>
</div>
<TABLE class="linedTable">
	<TR>
		<th>ID</th>
		<th align="left">Name</th>
		<th width="350px"></th>
	</TR>
<?
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

		$name = stripslashes(MYSQL_RESULT($result,$i,"name"));
		$id = stripslashes(MYSQL_RESULT($result,$i,"location_id"));
		$headerpic = strtolower("/images/location/$id.jpg");
		$filePath = $_SERVER['DOCUMENT_ROOT'].$headerpic;
		
		if (file_exists($filePath) AND ($pageFilterType != 'without'))
		{
			$image = "<img src=\"$headerpic\" alt=\"$name\" title=\"$name\" />";
			$display = true;
		}
		else if ($pageFilterType == 'without' OR $pageFilterType == '')
		{
			$image = $filePath;
			$display = true;
		}
		else
		{
			$image = '';
			$display = false;
		}
			
		if ($display)
		{
?>
	<TR class="<? echo $bgColor; ?>">
		<TD align="center"><?=$id ?></TD>
		<TD><a href="editLocations.php?location=<? echo $id; ?>"><?=$name ?></a></td>
		<td><a href="/location/<?=$id ?>/" alt="View"><?=$image ?></a></td>
	</TR>
<?
		}	// end if
	} 		// end while loop
}			// end if
?>
</TABLE>
<?
include_once("common/footer.php");
?>