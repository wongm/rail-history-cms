<?php

if (isset($_REQUEST['region']))
{
	$pageSql = " WHERE line_id = -1";	
	$pageType = 'region';
	$pageLink = '?region=';
	
}
else if (!isset($_REQUEST['all']))
{
	$pageSql = " WHERE line_id = 0";	
	$pageType = 'article';
}

$pageTitle = ucfirst($pageType . 's');

include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT  * FROM articles $pageSql";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0) 
{
	$i=0;
?>
<a href="insertNewArticles.php<?php echo $pageLink ?>">Add new <?php echo $pageType ?></a>

<TABLE class="linedTable">
	<TR>
		<th>Title</th>
		<th>Description</th>
		<th>Content</th>
	</TR>
<?
	while ($i<$numberOfRows)
	{
		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

		$thisId = MYSQL_RESULT($result,$i,"article_id");
		$thisLink = stripslashes(MYSQL_RESULT($result,$i,"link"));
		$thisTitle = stripslashes(MYSQL_RESULT($result,$i,"title"));
		$thisDescription = stripslashes(MYSQL_RESULT($result,$i,"description"));
		$thisContent = stripslashes(MYSQL_RESULT($result,$i,"content"));
?>
	<TR class="<? echo $bgColor; ?>">
		<TD><a href="editArticles.php?id=<? echo $thisId; ?>" style="white-space: nowrap" alt="<? echo $thisLink; ?>" title="<? echo $thisLink; ?>">
			<?php echo $thisTitle ?>
		</a></TD>
		<TD><? echo $thisDescription; ?></TD>
		<TD><? if($thisContent != '') { echo 'Yes'; } ?></TD>
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