<?php
$pageTitle = 'Sources';
include_once("common/dbConnection.php");
include_once("common/header.php");

$updated = $_REQUEST['updated'];

$sql = "SELECT   * FROM sources ORDER BY name ASC";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);

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
<?php  		
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
<?php
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

	$thisSource_id = stripSlashes($result[$i]["source_id"]);
	$thisName = stripSlashes($result[$i]["name"]);
	$thisShort = stripSlashes($result[$i]["short"]);
	$thisDetails = stripSlashes($result[$i]["details"]);

?>
	<TR class="<?php echo $bgColor; ?>">
		<TD><a href="editSources.php?id=<?php echo $thisSource_id; ?>"><?php echo $thisShort; ?></a></TD>
		<TD><?php echo $thisName; ?></TD>
		<TD><a href="confirmDeleteSources.php?id=<?php echo $thisSource_id; ?>">Delete</a></TD>
	</TR>
<?php
		$i++;

	} // end while loop
?>
</TABLE>
<?php
} // end of if numberOfRows > 0 
 ?>

<?php
include_once("common/footer.php");
?>