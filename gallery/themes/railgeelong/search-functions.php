<?php

/******************************************************************************
// Search and show updates functions for the ZenPhoto gallery that I need
//
// Old school (non integrated) search and for use in 404 pages
//
// For railgeelong.com and wongm.railgeelong.com
//
// V 1.0.0

	Requires:
	GALLERY_PATH
	UPDATES_URL_PATH
	MAXIMAGES_PERPAGE

//
//*****************************************************************************/


function galleryPageNavigationLinks($index, $totalimg, $url)
{
	$page = $index/MAXIMAGES_PERPAGE;
	$url = fixNavigationUrl($url);
	
	if ($totalimg == MAXIMAGES_PERPAGE OR $index > 0)
	{
		?>
<table class="nextables"><tr><td>
	<?
	}
	if ($index > 0)
	{	
		if ($index-MAXIMAGES_PERPAGE < 0)
		{
			$index = 1;
		}
?>
<a class="prev" href="<? echo $url.($page) ?>" title="Previous Page"><span>&laquo;</span> Previous</a>
<?
	}
	if ($totalimg == MAXIMAGES_PERPAGE )
	{	?>
<a class="next" href="<? echo $url.($page+2) ?>" title="Next Page">Next <span>&raquo;</span></a>
	<?
	}
	if ($totalimg == MAXIMAGES_PERPAGE OR $index > 0)
	{
		?>
</td></tr></table>
	<?
	}
} // end function


function drawImageGallery($galleryResult, $type='')
{
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);

	if ($numberOfRows>0) 
	{
		echo "<p>$text</p>\n";
		echo '<table class="centeredTable">';

		$i=0;
		$j=0;
		
		if ($numberOfRows == '4')
		{
			$j=1;
		}
		else
		{
			$style = 'width="30%" ';
		}
		
		while ($i<$numberOfRows)// AND $i<29)
		{
			echo "<tr>\n";
			
			while ($j < 3 AND $i<$numberOfRows)
			{
				$photoTitle = stripslashes(MYSQL_RESULT($galleryResult,$i,"zen_images.title"));
				$photoUrl = MYSQL_RESULT($galleryResult,$i,"zen_images.filename");
				$photoDesc = stripslashes(MYSQL_RESULT($galleryResult,$i,"zen_images.desc"));
				$photoId = MYSQL_RESULT($galleryResult,$i,"zen_images.id");
				$photoPath = MYSQL_RESULT($galleryResult,$i,"zen_albums.folder");
				$photoAlbumTitle = stripslashes(MYSQL_RESULT($galleryResult,$i,"zen_albums.title"));
				$photoDate = stripslashes(MYSQL_RESULT($galleryResult,$i,"zen_images.date"));
				$photoDate = strftime(TIME_FORMAT,strtotime($photoDate));
				
				if ($type == 'ratings')
				{
					$wins = MYSQL_RESULT($galleryResult,$i,"zen_images.ratings_win");
					$views = MYSQL_RESULT($galleryResult,$i,"zen_images.ratings_view");
					$score = MYSQL_RESULT($galleryResult,$i,"zen_images.ratings_score");
					$photoStatsText = formatRatingCounter(array($wins, $views, $score));
				}
				// any other type of popular / recent pages
				else
				{
					$hitsAll = MYSQL_RESULT($galleryResult,$i,"zen_images.hitcounter");
					$hitsMonth = MYSQL_RESULT($galleryResult,$i,"zen_images.hitcounter_month");
					$hitsWeek = MYSQL_RESULT($galleryResult,$i,"zen_images.hitcounter_week");
					$photoHitcounter = array($hitsAll, $hitsMonth, $hitsWeek, $type);
					
					if ( zp_loggedin() )
					{
						$id = MYSQL_RESULT($galleryResult,$i,"zen_images.id");
						$hitCounterWeekLastReset = MYSQL_RESULT($galleryResult,$i,"zen_images.hitcounter_week_reset");
						$hitCounterMonthLastReset = MYSQL_RESULT($galleryResult,$i,"zen_images.hitcounter_month_reset");
						$updatedHitCounter = updateHitCounter($hitsAll, $hitsMonth, $hitsWeek, $hitCounterMonthLastReset, $hitCounterWeekLastReset);
												 
						// only reset the monthly and weekly totals if and admin, and counter are past the date
						if ($updatedHitCounter['admin'] != '') 
						{
							query("UPDATE zen_images SET ".$updatedHitCounter['admin']." WHERE `id` = $id");
							$photoHitcounter = array($updatedHitCounter['hitCounterAllTime'], $updatedHitCounter['hitCounterMonth'], $updatedHitCounter['hitCounterWeek'], $type);
						}
					}
					$photoStatsText = formatHitCounter($photoHitcounter);
				}
				
				if ($photoDesc == '')
				{
					$photoDesc = $photoTitle;
				}
				else
				{
					$photoDesc = 'Description: '.$photoDesc;
				}
				
				// for when URL rewrite is on
				/* <a href="/gallery/<? echo $photoPath; ?>/<? echo $photoUrl; ?>.html" target="new" ><img src="/gallery/cache/<? echo $photoPath; ?>/<? echo $photoUrl; ?>_<?php echo $thumbsize; ?>.jpg" alt="<? echo $photoTitle; ?>" title="<? echo $photoTitle; ?>" /></a>*/
				// non rewrite
				/* <a href="/gallery/index.php?album=<? echo $photoPath; ?>&amp;image=<? echo $photoUrl; ?>"><img src="/gallery/cache/<? echo $photoPath; ?>/<? echo $photoUrl; ?>_<?php echo $thumbsize; ?>.jpg" alt="<? echo $photoTitle; ?>" title="<? echo $photoTitle; ?>" /></a> */
				
				$imagePageLink = GALLERY_PATH."/$photoPath/$photoUrl.html";
				$albumPageLink = GALLERY_PATH."/$photoPath/";
				$imageUrl = GALLERY_PATH."/$photoPath/image/thumb/$photoUrl";
?>
<td class="i" <?=$style ?>><a href="<?=$imagePageLink?>"><img src="<?=$imageUrl ?>" alt="<? echo $photoDesc; ?>" title="<? echo $photoDesc; ?>" /></a>
	<h4><a href="<?=$imagePageLink; ?>"><?=$photoTitle; ?></a></h4>
	<small><?=$photoDate?><?=$photoStatsText?></small><br/>
	In Album: <a href="<?=$albumPageLink; ?>"><?=$photoAlbumTitle; ?></a>
</td>
<?
				$j++;
				$i++;
		
			}	//end while for cols
			$j=0;
			
			echo "<tr>\n";
		}	//end while for rows
		
		echo "</table>\n";
	}	// end if for non zero
}	

function drawImageOrAlbumSearchForm()
{
	if ($_REQUEST['type'] == 'albums')
	{
		$albumChecked = 'checked="checked"';
	}
	else
	{
		$imageChecked = 'checked="checked"';
	}
	?>
<h4>Search For:</h4>
<div class="album">
<form name="SearchForm" id="SearchForm" method="get" action="<?=GALLERY_PATH?>/search/"><p>
<input type="text" name="search" id="search" size="40" value="<?=$search?>" />
<input type="submit" value="Search" /><br/>
<table><tr><td><label for="type2">Albums: </label></td><td><input type="radio" name="type" id="type" value="albums" <?=$albumChecked?>/></td>
<td><label for="type1">Images: </label></td><td><input type="radio" name="type" id="type" value="images" <?=$imageChecked?>/></td></tr></table>
</form>
</div>
<h4>Recent Uploads:</h4>
<p><a href="<?=UPDATES_URL_PATH?>">View recently uploaded photos</a></p>
<?

}

/**
 * Runs a search for a given term, for either image or album. supports paganation
 *
 * @param in $page - the number of the page to be viewed. Integer greater than 1
 */
function imageOrAlbumSearch($term, $type, $page)
{
	// setup search options
	//404 page version
	if ($page == 'error')
	{
		$maxImagesPerPage = 3;
		$index = 0;
	}
	// anything other than 404 page
	else
	{
		$maxImagesPerPage = MAXIMAGES_PERPAGE;
		// for paganation
		if ($page == '' OR $page <= 1 OR !is_numeric($page))
		{
			$index = 0;
		}
		else
		{
			$index = ($page*MAXIMAGES_PERPAGE)-MAXIMAGES_PERPAGE;
		}
	}
	
	// do the query
	if ($type == 'Image')
	{
		$searchSql = "SELECT * FROM zen_images, zen_albums WHERE zen_images.albumid = zen_albums.id AND (zen_images.title like '%$term%' OR zen_images.desc like '%$term%' OR zen_images.filename like '%$term%' ) ORDER BY zen_images.sort_order";
 		$limitedSearchSql = "$searchSql LIMIT $index,$maxImagesPerPage";
		$numberImagesFound = MYSQL_NUM_ROWS(MYSQL_QUERY($searchSql));
		$text2 = ' <a href="'.GALLERY_PATH.'/search/?search='.$term.'&type=albums">Search for \''.$term.'\' in albums instead?</a>';
		$galleryResult = MYSQL_QUERY($limitedSearchSql);
		$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	}
	elseif ($type == 'Album')
	{
		$searchSql = "SELECT * FROM zen_albums WHERE ( zen_albums.title LIKE '%$term%' OR zen_albums.desc LIKE '%$term%' OR zen_albums.folder LIKE '%$term%' )";
		$text2 = ' <a href="'.GALLERY_PATH.'/search/?search='.$term.'&type=image">Search for \''.$term.'\' in images instead?</a>';
		$galleryResult = MYSQL_QUERY($searchSql);
		$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
		$numberImagesFound = $numberOfRows;
	}
	
	// display infomation to the user
	// if this is being called from the 404 error page, display stripped down formatting
	if ($page != 'error')
	{
		if ($numberOfRows > 1)
		{
			$text = $numberImagesFound.' '.strtolower($type).'s matching \''.$term.'\' found.';
		}
		elseif($numberOfRows > 0)
		{
			$text = $numberImagesFound.' '.strtolower($type).' matching \''.$term.'\' found.';
		}
		else
		{
			$text = "No results found in ".strtolower($type)."s for '$term'.";
			echo "<p><b>$text</b></p>";
			drawImageOrAlbumSearchForm();
			return;
		}
			
		$text .= '</b>'.$text2.' <a href="/gallery/search">Or start a new search?</a>';
		echo "<p><b>$text</p>";
	}
	elseif($numberOfRows == 0)
	{
		// for no results found and on error page
		return;
	}
		
	// display results
	// if this is being called from the 404 error page, display stripped down formatting
	if ($type == 'Image')
	{
		if ($page == 'error')
		{
			drawImageGallery($galleryResult);
		}
		else
		{
			galleryPageNavigationLinks($index, $numberOfRows, $maxImagesPerPage, '/gallery/search/?search='.$term.'&page=');
			drawImageGallery($galleryResult);
			galleryPageNavigationLinks($index, $numberOfRows, $maxImagesPerPage, '/gallery/search/?search='.$term.'&page=');
			
			if ($numberOfRows == $maxImagesPerPage OR $index > 0)
			{
				drawPageNumberLinks($index, $numberImagesFound, $maxImagesPerPage, '/gallery/search/?search='.$term.'&page=');
			}
		}
	}
	elseif ($type == 'Album')
	{
		drawAlbums($galleryResult, ($page == 'error'));
	}
	else
	{
		echo ("<p>No results found for '$term'");
	}
	return $numberOfRows;
}




/* 
 * pass it an SQL result set
 * used by album search
 * and by the frontpage recently updated search
 */
function drawAlbums($galleryResult, $error = false)
{	
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	
	if ($error)
	{
		if ($numberOfRows > 3)
		$numberOfRows = 3;
	}
	
	if ($numberOfRows>0) 
	{	
		echo '<table class="centeredTable">';
		$i=0;
		$j=0;
			
		while ($i<$numberOfRows AND $i<29)
		{
			echo "<tr>\n";
			while ($j < 3 AND $i<$numberOfRows)
			{
				$photoPath = MYSQL_RESULT($galleryResult,$i,"zen_albums.folder");
				$photoAlbumTitle = stripslashes(MYSQL_RESULT($galleryResult,$i,"zen_albums.title"));
				$albumId = MYSQL_RESULT($galleryResult,$i,"zen_albums.id");
				$albumDate = strftime(TIME_FORMAT, strtotime(MYSQL_RESULT($galleryResult,$i,"zen_albums.date")));
				
				// get an image to display with it
				$imageSql = "SELECT filename, id FROM zen_images WHERE zen_images.albumid = '$albumId' LIMIT 0,1 ";
				$imageResult = MYSQL_QUERY($imageSql);
				$numberOfImages = MYSQL_NUM_ROWS($imageResult);
				if ($numberOfImages > 0)
				{
					$photoUrl = MYSQL_RESULT($imageResult,0,"filename");
					$photoId = MYSQL_RESULT($imageResult,0,"id");
					$photoUrl = GALLERY_PATH."/$photoPath/image/thumb/$photoUrl";
				}
				else
				{
					$photoUrl = GALLERY_PATH."/foldericon.gif";
				}
					
				if ($photoDesc == '')
				{
					$photoDesc = $photoTitle;
				}
				else
				{
					$photoDesc = 'Description: '.$photoDesc;
				}
?>
<td class="i"><a href="<?=GALLERY_PATH ?>/<? echo $photoPath; ?>/"><img src="<?=$photoUrl ?>" alt="<? echo $photoAlbumTitle; ?>" title="<? echo $photoAlbumTitle; ?>" /></a>
	<br/><div class="imagetitle"><h4><a href="<?=GALLERY_PATH ?>/<? echo $photoPath; ?>/"><? echo $photoAlbumTitle; ?></a></h4>
	<small><?=$albumDate?></small></div></td>
<?
				$j++;
				$i++;
			
			}	//end while for cols
			$j=0;
			echo "</tr>\n";
		}	//end while for rows
		echo "</table>\n";
	}	// end if for non zero
}		// end function


/*
 * prints a pretty dodad that lists the total number of pages in a set
 * give it the index you are up to,
 * the total number of items,
 * the number  to go per page,
 * and the URL to link to
 */
function drawSearchPageNumberLinks($index, $totalimg, $max, $url)
{
	$total = floor(($totalimg)/$max)+1;
	$current = $index/$max;
	$url = fixNavigationUrl($url);
	
	echo '<p>';
  
  	if ($total > 0)
  	{
		echo 'Page: ';
	}
	
	if ($current > 3 AND $total > 7)
	{
		$url1 = $url."1";
		echo "\n <a href=\"$url1\" alt=\"First page\" title=\"First page\">1</a>&nbsp;"; 
		
		if ($current > 4)
		{
			echo "...&nbsp;";
		}
	}
	
	for ($i=($j=max(1, min($current-2, $total-6))); $i <= min($total, $j+6); $i++) 
	{
		if ($i == $current+1)
		{
			echo $i;
		}
		else
		{
			echo '<a href="'.$url.$i.'" alt="Page '.$i.'" title="Page '.$i.'">'.($i).'</a>';
		}
		echo "&nbsp;";
	}
	if ($i <= $total) 
	{
		if ($current < $total-5)
		{
			echo "...&nbsp;";
		}
		
		echo "<a href=\"$url$total\" alt=\"Last page\" title=\"Last page\">" . $total . "</a>"; 
	}
	echo '</p>';
}	// end function

function getGalleryUploadsResults($pageType, $pageTypeModifier, $nextURL, $start, $count, $currentImageResultIndex)
{
	if ($count == '')
	{
		$count = MAXIMAGES_PERPAGE;
	}
	
	if ($count < MAXIMAGES_PERPAGE)
	{
		$dontDoTotalCount = true;
	}
	
	if ($pageTypeModifier == 'double')
	{
		if (!($count > 1))
		{
			$count = 45;
		}
		if (!is_numeric($start))
		{
		$start = 0;
		}
		
		$nextURL .= "/?double=&count=$count&start=$start&page=";
	
		$sql = "SELECT * FROM zen_images
			INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id 
			WHERE zen_images.filename IN (
				SELECT filename 
				FROM (
					SELECT filename, count(id) AS duplicates 
					FROM zen_images
					GROUP BY filename) AS inner_query 
				WHERE duplicates > 1)
			ORDER BY zen_images.date DESC
			LIMIT $start, $count";
	}
	else
	{
		$captionLimitSql = "zen_images.title REGEXP '_[0-9]{4}' OR zen_images.title REGEXP 'DSCF[0-9]{4}'";
		$captiona = $captionb = '';
		$order = " ORDER BY zen_images.date DESC ";
		
		//show all images with bad captions
		if ($pageTypeModifier == 'images')
		{
			$nextURL .= "/?caption=images&page=";
			$captiona = " AND ($captionLimitSql)";
			$captionb = "";
		}
		//show only albums that have one or more images with bad captions
		else if ($pageTypeModifier == 'albums')
		{
			$nextURL .= "/?caption=albums&page=";
			$captiona = " AND ($captionLimitSql)";
			$captionb = " GROUP BY albumid ";
		}
		//change to order by how popular
		else
		{
			if ($pageTypeModifier == 'this-month')
			{
				$order = " ORDER BY zen_images.hitcounter_month DESC";
				$where = " AND zen_images.hitcounter_month > 0 ";
			}
			else if ($pageTypeModifier == 'this-week')
			{
				$order = " ORDER BY zen_images.hitcounter_week DESC";
				$where = " AND zen_images.hitcounter_week > 0 ";
			}
			else if ($pageTypeModifier == 'all-time')
			{
				$order = " ORDER BY zen_images.hitcounter DESC";
			}
			else if ($pageTypeModifier == 'ratings')
			{
				$order = " ORDER BY zen_images.ratings_score DESC, zen_images.hitcounter DESC";
				$where = " AND zen_images.ratings_view > 0 ";
			}
			
			$nextURL .= "/";
		}
		
		$sql = "SELECT * FROM zen_images, zen_albums 
			WHERE zen_images.albumid = zen_albums.id $captiona $captionb $where
			$order
			LIMIT $currentImageResultIndex,$count";
	}
	
	if (!$dontDoTotalCount) {
	$toreturn['maxImagesCount'] = MYSQL_RESULT(MYSQL_QUERY("SELECT count(*) 
		FROM zen_images, zen_albums 
		WHERE zen_images.albumid = zen_albums.id $captiona $captionb $where"), 0, 'count(*)');
	}
	
	$toreturn['galleryResult'] = MYSQL_QUERY($sql);
	$toreturn['galleryResultCount'] = MYSQL_NUM_ROWS($toreturn['galleryResult']);
	$toreturn['nextURL'] = $nextURL;	
	return $toreturn;
}

function fixNavigationUrl($url)
{
	if (strrpos($url, "=") > 0)
	{
		return $url;
	}
	else
	{
		return $url.'page/';
	}
	
}

?>