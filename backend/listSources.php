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

<TABLE class="linedTable">
	<TR>
		<th>Short</th>
		<th>Name</th>
	</TR>
<?
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

	$thisSource_id = stripSlashes(MYSQL_RESULT($result,$i,"source_id"));
	$thisName = stripSlashes(MYSQL_RESULT($result,$i,"name"));
	$thisShort = stripSlashes(MYSQL_RESULT($result,$i,"short"));
	$thisDetails = stripSlashes(MYSQL_RESULT($result,$i,"details"));

?>
	<TR class="<? echo $bgColor; ?>">
		<TD><a href="editSources.php?id=<? echo $thisSource_id; ?>"><? echo $thisShort; ?></a></TD>
		<TD><? echo $thisName; ?></TD>
		<TD><a href="confirmDeleteSources.php?id=<? echo $thisSource_id; ?>">Delete</a></TD>
	</TR>
<?
		$i++;

	} // end while loop
?>
</TABLE>
<?
} // end of if numberOfRows > 0 
 ?>

<?php
include_once("common/footer.php");
?>