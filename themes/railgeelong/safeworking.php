 <?php $pageTitle = "Safeworking";
include_once("common/header.php");
include_once("common/formatting-functions.php");
include_once("common/dbConnection.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/articles/">Articles</a> &raquo; <?php echo $pageTitle?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("midbar.php"); ?>
<h3><?php echo $pageTitle?></h3>
<p><strong>Safeworking Systems</strong> are a system of rules and equipment used to prevent conflict between trains.</p>
<p>Track is divided into <strong>Sections</strong> upon which only one one train is permitted. The end points of these sections may be a a place where trains may pass (such as a Station or Crossing Loop), a place where trains leave the main line (a Siding) or just a specially marked location (a Block Point).</p>
<p>Permission for a train to enter a section is refered to as an <strong>Authority</strong>. Each form of safeworking goes about the granting of these Authorities to trains in a different way.</p>
<p>Here is a short overview of the different types of safeworking used on the Victorian Railways.</p>
<?php

$sql = "SELECT * FROM safeworking_types WHERE details != '' ORDER BY ordered ASC";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);

if ($numberOfRows==0)
{
?>
Sorry. No records found !!
<?php
}
else if ($numberOfRows>0)
{
	?>
<h4>Contents</h4>
<hr/>
<ul>
<?php for ($i = 0; $i < $numberOfRows; $i++)
	{
		$thisName = $result[$i]["name"];
		$thisLink = $result[$i]["link"];
		$thisDetails = stripslashes($result[$i]["details"]);

		$safeworking[] = array($thisName, $thisLink, $thisDetails);
		?>
<li><a href="#<?php echo $thisLink; ?>"><?php echo $thisName; ?></a></li>
<?php
	}	//end loop
	?>
</ul>
<?php
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		?>
<h4 id="<?php echo $safeworking[$i][1]; ?>"><?php echo $safeworking[$i][0]; ?></h4>
<hr/>
<?php
		drawFormattedText($safeworking[$i][2]);
?>
<p><a href="#top" class="credit">Top</a></p>
<?php
	}	// end loop
}		// end if

include_once("common/footer.php"); ?>