<?php
$pageTitle = 'Sources';
include_once("common/dbConnection.php");
include_once("common/header.php");

$updated = $_REQUEST['updated'];

$sql = "SELECT   * FROM sources ORDER BY name ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0) {

	$i=0;
	
	if ($updated != '')
	{
?>
<script type="text/javascript">
  	jQuery(function( $ ){
  		 $("#fade-message").fadeTo(5000, 1).fadeOut(1000);
  		 $("#fade-message2").fadeTo(5000, 1).fadeOut(1000);
  		 $('.tooltip').tooltip();
  		 $('#mainmenu > ul').tabs();
  	});
</script>
<?  		
		echo '<p class="messagebox" id="fade-message">Source "<i>'.stripslashes($updated).'</i>" has been updated</p>';
	}
	
	drawSourceHelpText();
?>
<a href="enterNewSources.php">Add new source</a><hr/>

<TABLE CELLSPACING="0" CELLPADDING="3" BORDER="0" WIDTH="100%">
	<TR>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=id&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Source_id</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=name&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Name</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=details&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Details</B>
			</a>
</TD>
	</TR>
<?
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "#FFFFFF"; } else { $bgColor = "#C0C0C0"; }

	$thisSource_id = stripSlashes(MYSQL_RESULT($result,$i,"source_id"));
	$thisName = stripSlashes(MYSQL_RESULT($result,$i,"name"));
	$thisDetails = stripSlashes(MYSQL_RESULT($result,$i,"details"));

?>
	<TR BGCOLOR="<? echo $bgColor; ?>">
		<TD><? echo $thisSource_id; ?></TD>
		<TD><? echo $thisName; ?></TD>
		<TD><? echo $thisDetails; ?></TD>
	<TD><a href="editSources.php?id=<? echo $thisSource_id; ?>">Edit</a></TD>
	<TD><a href="confirmDeleteSources.php?id=<? echo $thisSource_id; ?>">Delete</a></TD>
	</TR>
<?
		$i++;

	} // end while loop
?>
</TABLE>

<br>
<?
if ($_REQUEST['startLimit'] != "")
{
?>

<a href="<? echo  $_SERVER['PHP_SELF']; ?>?startLimit=<? echo $previousStartLimit; ?>&limitPerPage=<? echo $limitPerPage; ?>&sortBy=<? echo $sortBy; ?>&sortOrder=<? echo $sortOrder; ?>">Previous <? echo $limitPerPage; ?> Results</a>....
<? } ?>
<?
if ($numberOfRows == $limitPerPage)
{
?>
<a href="<? echo $_SERVER['PHP_SELF']; ?>?startLimit=<? echo $nextStartLimit; ?>&limitPerPage=<? echo $limitPerPage; ?>&sortBy=<? echo $sortBy; ?>&sortOrder=<? echo $sortOrder; ?>">Next <? echo $limitPerPage; ?> Results</a>
<? } ?>

<br><br>
<a href="enterNewSources.php">Enter new Source</a>
<?
} // end of if numberOfRows > 0 
 ?>

<?php
include_once("common/footer.php");
?>