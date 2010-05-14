<?php

$type = $_REQUEST['type'];
$pageTitle = 'List location header images';
include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT * FROM locations l WHERE ".SQL_NEXTABLE. " ORDER BY modified DESC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0) 
{
?>
<a href="listLocationImages.php?type=images">Locations with images</a><br>
<a href="listLocationImages.php?type=required">Locations without images</a><br>
<a href="listLocationImages.php">All locations</a><hr/>
<TABLE CELLPADDING="5" WIDTH="100%">
	<TR>
		<th>ID</th>
		<th align="left">Name</th>
		<th width="350px"></th>
		<th>Edit</th>
		<th>Delete</th>
	</TR>
<?
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		if (($i%2)==0) { $bgColor = "#FFFFFF"; } else { $bgColor = "#F5F7F5"; }

		$name = stripslashes(MYSQL_RESULT($result,$i,"name"));
		$id = stripslashes(MYSQL_RESULT($result,$i,"location_id"));
		$headerpic = strtolower("/images/location/$id.jpg");
		
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$headerpic) AND ($type != 'required'))
		{
			$image = "<img src=\"$headerpic\" alt=\"$name\" title=\"$name\" />";
			$display = true;
		}
		else if ($type == 'required' OR $type == '')
		{
			$image = '';
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
	<TR BGCOLOR="<? echo $bgColor; ?>">
		<td><?=$id ?></TD>
		<TD><a href="/location/<?=$id ?>/"><?=$name ?></a></td>
		<td align="center"><?=$image ?></td>
		<TD><a href="editLocations.php?location=<? echo $id; ?>">Edit</a></TD>
		<TD><a href="confirmDeleteLocations.php?location=<? echo $id; ?>">Delete</a></TD>
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