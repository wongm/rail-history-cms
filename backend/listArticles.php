<?php
$pageTitle = 'Articles';
include_once("common/dbConnection.php");
include_once("common/header.php");

if (isset($_REQUEST['region']))
{
	$extra = " WHERE line_id = -1";	
}
else if (!isset($_REQUEST['all']))
{
	$extra = " WHERE line_id = 0";	
}

$sql = "SELECT  * FROM articles $extra";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0) {

	$i=0;
?>
<a href="enterNewArticles.php">Add new article</a><hr/>
<a href="<?=$_SERVER['PHP_SELF']?>">Articles Only</a> :: <a href="<?=$_SERVER['PHP_SELF']."?region="?>">Regions Only</a> :: <a href="<?=$_SERVER['PHP_SELF']."?all="?>">All Articles</a><hr/>

<TABLE CELLSPACING="0" CELLPADDING="3" BORDER="0" WIDTH="100%">
	<TR>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=link&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Link</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=title&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Title</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=description&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Description</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=content&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Content</B>
			</a>
</TD>
	</TR>
<?
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "#FFFFFF"; } else { $bgColor = "#C0C0C0"; }

	$thisId = MYSQL_RESULT($result,$i,"article_id");
	$thisLink = stripslashes(MYSQL_RESULT($result,$i,"link"));
	$thisTitle = stripslashes(MYSQL_RESULT($result,$i,"title"));
	$thisDescription = stripslashes(MYSQL_RESULT($result,$i,"description"));
	$thisContent = stripslashes(MYSQL_RESULT($result,$i,"content"));

?>
	<TR BGCOLOR="<? echo $bgColor; ?>">
		<TD><? echo $thisLink; ?></TD>
		<TD><? echo $thisTitle; ?></TD>
		<TD><? echo $thisDescription; ?></TD>
		<TD><? if($thisContent != '') { echo 'Yes'; } ?></TD>
	<TD><a href="editArticles.php?id=<? echo $thisId; ?>">Edit</a></TD>
	<TD><a href="confirmDeleteArticles.php?id=<? echo $thisId; ?>">Delete</a></TD>
	</TR>
<?
		$i++;

	} // end while loop
?>
</TABLE>
<?
} // end of if numberOfRows > 0 

include_once("common/footer.php");
?>