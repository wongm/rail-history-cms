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
$result = query_full_array($sql);
$numberOfRows = sizeof($result);

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
<?php
	while ($i<$numberOfRows)
	{
		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

		$thisId = $result[$i]["article_id"];
		$thisLink = stripslashes($result[$i]["link"]);
		$thisTitle = stripslashes($result[$i]["title"]);
		$thisDescription = stripslashes($result[$i]["description"]);
		$thisContent = stripslashes($result[$i]["content"]);
?>
	<TR class="<?php echo $bgColor; ?>">
		<TD><a href="editArticles.php?id=<?php echo $thisId; ?>" style="white-space: nowrap" alt="<?php echo $thisLink; ?>" title="<?php echo $thisLink; ?>">
			<?php echo $thisTitle ?>
		</a></TD>
		<TD><?php echo $thisDescription; ?></TD>
		<TD><?php if($thisContent != '') { echo 'Yes'; } ?></TD>
		<TD><a href="confirmDeleteArticles.php?id=<?php echo $thisId; ?>">Delete</a></TD>
	</TR>
<?php
		$i++;

	} // end while loop
?>
</TABLE>
<?php
} // end of if numberOfRows > 0 

include_once("common/footer.php");
?>