<?php 
/*
 * Does the actual ranking of photos
 * - showing pair of images
 * - accepting input
 * - updating the DB
 * - showing results
 *
 */ 
$startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Rate my photos';
$votePromptText = "Cast your vote";
include_once('header.php');
?>
<div id="headbar">
	<div class="link"><?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; <a href="<?=DO_RATINGS_URL_PATH?>">Rate my photos</a></div>
	<div class="search"><? printSearchBreadcrumb(); ?></div>
</div>
<?php 

include_once('midbar.php');

if (isset($_POST['option']))
{	
	$option = $_POST['option'];
	$selected = $_POST['image'];
	$rejected = $_POST['other'];
	$session = md5($selected + $rejected);
	
	$multiplyScoreForWinner = $_POST['multiplyscore'];
	$noPenaltyForLoser = ($_POST['nopenalty'] != '');
	
	if ($session != $_POST['session'])
	{
		echo "<p class=\"error\">Form post error!<br/>\n";
		echo 'Go back to the <a href="'.DO_RATINGS_URL_PATH.'">ratings page</a> and try again!</a></p>';
	}
	else
	{
		// update selected image
		$sql = "SELECT i.*, a.folder, a.title AS albumtitle FROM zen_images i
			INNER JOIN zen_albums a ON a.id = i.albumid
			WHERE i.id = ".mysql_real_escape_string($selected);
		$resultWinner = query_single_row($sql);
		
		$winnerRatings_win = $resultWinner['ratings_win'];
		$winnerRatings_view = $resultWinner['ratings_view'];
				
		//multiply scores if admin flag set
		if ($multiplyScoreForWinner != '')
		{
			for ($i = 1; $i < $multiplyScoreForWinner; $i++)
			{
				$winnerRatings_view++;
				$winnerRatings_win++;
			}
		}
		
		$winnerRatings_view++;
		$winnerRatings_win++;
		$winnerRatings_score = calculateScore($winnerRatings_win, $winnerRatings_view);
		
		// save to DB
		$sql = "UPDATE zen_images 
			SET ratings_win = '$winnerRatings_win', ratings_score = '$winnerRatings_score', ratings_view = '$winnerRatings_view' 
			WHERE id = '$selected'";
		query($sql);
			
		// update rejected image		
		if (!$noPenaltyForLoser)
		{
			$sql = "SELECT ratings_win, ratings_view FROM zen_images WHERE id = ".mysql_real_escape_string($rejected);
			$resultRejectedImage = query_single_row($sql);
			
			$loserRatings_win = $resultRejectedImage['ratings_win'];
			$loserRatings_view = $resultRejectedImage['ratings_view'];
			
			$loserRatings_view++;
			$loserRatings_score = calculateScore($loserRatings_win, $loserRatings_view);
			
			// save to DB
			$sql = "UPDATE zen_images 
				SET ratings_score = '$loserRatings_score', ratings_view = '$loserRatings_view'
				WHERE id = '$rejected'";
			query($sql);
		}
		
		// display winner
		$urlThumbnailImage = "/".$resultWinner['folder'].'/image/thumb/'.$resultWinner['filename'];
		$urlImagePage = "/".$resultWinner['folder'].'/'.$resultWinner['filename'].'.html';//?p=full';
		$alt = $resultWinner['albumtitle'].' - '.$resultWinner['title'];
		$resultText = "So far it has ".pluralNumberWord($winnerRatings_win, 'vote')." out of ".pluralNumberWord($winnerRatings_view, 'view');
		
		if (zp_loggedin())
		{
			$resultText .= " for a score of $winnerRatings_score.";
		}
		else
		{
			$resultText .= ".";
		}
		$votePromptText = "Vote again";
?>
<div class="topbar"><h3>Results</h3></div>
<table class="indexalbums"><tr class="album">
<td class="albumthumb">
	<a href="<?=$urlImagePage?>" target="new">
	<img src="<?=$urlThumbnailImage?>" title="<?=$alt?>" alt="<?=$alt?>" /></a>
</td><td class="albumdesc">
	<h4>Option <?=$option?> - <?=$resultWinner['title']?></h4>
	<p><small><?=zpFormattedDate(getOption('date_format'), strtotime($resultWinner['date']));?></small></p>
	<p>Album: <?=$resultWinner['albumtitle']?></p>
	<p><i><?=$resultText?></i></p>
	<p><a href="<?=RATINGS_URL_PATH?>">See the highest rated photos</a></p>
</td></tr></table>
<?
	} 	// end successful submit
}		// end any submit
?>
<div class="topbar"><h3><?=$votePromptText?></h3></div>
<?
$imageOptions = array(getRandomImages(), getRandomImages());
$titleOptions = array('A', 'B');
$reverseOptions = array($imageOptions[0], $imageOptions[1], $imageOptions[0]);
$i = 0;
?>
<table class="indexalbums">
<?

// draw out the image options
foreach ($imageOptions as $random)
{
	global $_zp_current_image;
	$_zp_current_image = $random;
	$id = $random->get('id');
	$imageTitle = $random->getTitle();
	$albumTitle = $random->getAlbum()->getTitle();
	$date = zpFormattedDate(getOption('date_format'), strtotime($random->getDateTime()));
	$alt = "$albumTitle - $imageTitle";
	
	$optionA = $reverseOptions[$i]->get('id');
	$optionB = $reverseOptions[$i+1]->get('id');
	
	// create session variable to reduce hacking
	$session = md5($optionB + $optionA);
	$title = $titleOptions[$i];
?>
<tr class="album">
<td class="albumthumb">
<?
	echo "<form method=\"post\" action=\"" . DO_RATINGS_URL_PATH . "\" id=\"dorating\">\n";
	echo "<input id=\"option\" name=\"option\" type=\"hidden\" value=\"$title\" />\n";
	echo "<input id=\"image\" name=\"image\" type=\"hidden\" value=\"$optionA\" />\n";
	echo "<input id=\"other\" name=\"other\" type=\"hidden\" value=\"$optionB\" />\n";
	echo "<input id=\"session\" name=\"session\" type=\"hidden\" value=\"$session\" />\n";
	echo "<input class=\"button\" type=\"submit\" value=\"Option $title\" />\n";
	
	if (zp_loggedin())
	{
		echo "<br/><br/><input id=\"multiplyscore\" name=\"multiplyscore\" type=\"textbox\" size=\"2\" />\n";
		echo '<label for="multiplyscore">Multiply score</label>';
		echo "<br/><input id=\"nopenalty\" name=\"nopenalty\" type=\"checkbox\" />\n";
		echo '<label for="nopenalty">No penalty for loser</label>';
	}
	echo "</form>\n";
?>
<h4><?=$imageTitle?></h4>
<p><small><?=$date; ?></small></p>
<p>Album: <?=$albumTitle ?></p>
</td><td class="albumdesc">
<?
	echo "<p><img src=\"" . htmlspecialchars(getDefaultSizedImage()) . "\" alt=\" $alt \" title=\" $alt \" /></p>";
	$i++;
?>
</td></tr>
<?
	if ($i == 1)
	{
?>
<tr class="album"><td class="albumthumb">
<?
echo "<form method=\"post\" action=\"" . DO_RATINGS_URL_PATH . "\" id=\"dorating\">\n";
echo "<input class=\"button\" type=\"submit\" value=\"Neither\" />\n";
echo "</form>\n";
?>
</td><td class="albumdesc"><p>I can't decide - give me 2 new photos</p></td></tr>
<?
	}
}
?>
</table>
<?
include_once('footer.php'); 

function calculateScore($ratings_win, $ratings_view)
{
	// for never voted images
	if ($ratings_win == 0 AND $ratings_view > 0)
	{
		// don't penalise if only viewed a few times
		if ($ratings_view < 2)
		{
			return 0;
		}
		// penalise if many views but no votes
		else if ($ratings_view > 3)
		{
			return ($ratings_view * -1);
		}
		// penalise if many views but no votes
		else if ($ratings_view > 6)
		{
			return ($ratings_view * -2);
		}
		else 
		{
			return ($ratings_view * -0.5);
		}
	}
	else
	{
		if ($ratings_view == 1)
		{
			$multiplier = 0.5;
		}
		else if ($ratings_view == 2)
		{
			$multiplier = 0.75;
		}
		else
		{
			$multiplier = (1.1 * $ratings_win);
		}
		return (($ratings_win / $ratings_view) * $multiplier);
	}
}
?>