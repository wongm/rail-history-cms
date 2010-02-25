 <?php $pageTitle = "Safeworking";
include_once("common/header.php");
include_once("common/formatting-functions.php");
include_once("common/dbConnection.php"); 

echo drawFormattedText(getConfigVariable('safeworking'), '<p>', '</p>');

$sql = "SELECT * FROM safeworking_types WHERE details != '' ORDER BY ordered ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows==0) 
{  
?>
Sorry. No records found !!
<?
}
else if ($numberOfRows>0)
{
	?>
<h4>Contents</h4>
<hr/>
<ul>
<?	for ($i = 0; $i < $numberOfRows; $i++)
	{
		$thisName = MYSQL_RESULT($result,$i,"name");
		$thisLink = MYSQL_RESULT($result,$i,"link");
		$thisDetails = stripslashes(MYSQL_RESULT($result,$i,"details"));
	
		$safeworking[] = array($thisName, $thisLink, $thisDetails);
		?>
<li><a href="#<? echo $thisLink; ?>"><? echo $thisName; ?></a></li>
<?
	}	//end loop
	?>
</ul>
<?
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		?>
<h4 id="<? echo $safeworking[$i][1]; ?>"><? echo $safeworking[$i][0]; ?></h4>
<hr/>
<?
		drawFormattedText($safeworking[$i][2]);
?>
<p><a href="#top" class="credit">Top</a></p>
<?		
	}	// end loop
}		// end if

include_once("common/footer.php"); ?>