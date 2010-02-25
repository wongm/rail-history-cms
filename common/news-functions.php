<?php

include_once('dbConnection.php');

function printNews()
{
	$sql = "SELECT DATE_FORMAT(post_date, '%M %e, %Y') AS fdate, post_content, post_title 
		FROM wp_posts WHERE post_status = 'publish' ORDER BY post_date DESC LIMIT 5";
	$result = MYSQL_QUERY($sql, newsDBconnect());
	$numberOfRows = MYSQL_NUM_ROWS($result);	?>
<table class="linedTable">	
<?		
	for ($i = 0; ($i < $numberOfRows AND $i < 10); $i++)
	{	
		if ($i%2 == '0')
		{
			$style = 'class="x"';
		}
		else
		{
			$style = 'class="y"';
		}
		
		$date = MYSQL_RESULT($result,$i,"fdate");
		//$content = stripslashes(eregi_replace("\n", '<br/>', MYSQL_RESULT($result,$i,"post_content")));
		$content = stripslashes(eregi_replace("\n", "\n\n", MYSQL_RESULT($result,$i,"post_content")));
		?>
<tr <? echo $style; ?>><td style="width:11em;" valign="top"><? echo $date; ?></td><td><? echo $content; ?></td></tr>
<?	}	// end for loop
?>
</table>
<?
}	// end function
?>	