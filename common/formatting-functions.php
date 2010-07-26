<?php

define('SITE_NAME', 'Rail Geelong');

function status_header( $header ) {
	if ( 200 == $header )
		$text = 'OK';
	elseif ( 301 == $header )
		$text = 'Moved Permanently';
	elseif ( 302 == $header )
		$text = 'Moved Temporarily';
	elseif ( 304 == $header )
		$text = 'Not Modified';
	elseif ( 404 == $header )
		$text = 'Not Found';
	elseif ( 410 == $header )
		$text = 'Gone';

	@header("HTTP/1.1 $header $text");
	@header("Status: $header $text");
}

function drawAdminEditableLink($link, $title)
{
	global $editablelinkforadmin;
	if ($editablelinkforadmin)
	{
		echo "<b>Edit: </b><a href=\"$link\" target=\"_new\">$title</a><br/>\n";
	}
}

function convertToLink($text)
{
	return strtolower(eregi_replace(' ', '-', $text));
}

function getLocationName($name, $type)
{
	switch ($type) 
	{
		// is a station
		case TYPE_STATION:
			$name .= ' Station'; 
			break;
		// is a signal box
		case TYPE_SIGNAL_BOX:
			$name .= ' Signal Box'; 
			break;
	}
	
	return $name;
}

// fix for raillines where the name ends with line
function getLineName($pageTitle)
{
	$pageTitle = str_replace("Line Line", "Line", $pageTitle." Line");
	$pageTitle = str_replace("Tramways Line", "Tramways", $pageTitle);
	return str_replace("Lines Line", "Lines", $pageTitle);
}

function getPageTitle($pageTitle)
{
	$toreturn = SITE_NAME;
	$size = sizeof($pageTitle);
	
	for ($i = 0; $i < $size; $i++)
	{
		if ($pageTitle[$i][0] != '')
		{
			$toreturn = "$toreturn - ".$pageTitle[$i][0];
		}
	}
		
	return $toreturn;
}

function getPageBreadcrumbs($pageTitle)
{
	$toreturn = '<a href="/" title="'.SITE_NAME.' Home">Home</a>';
	$size = sizeof($pageTitle);
	
	for ($i = 0; $i < $size; $i++)
	{
		$url = $pageTitle[$i][1];
		$title = $pageTitle[$i][0];
		
		if ($url != '')
		{
			$toreturn = "$toreturn &raquo; <a href=\"$url\" title=\"$title\">$title</a>";
		}
		else if ($title != '')
		{
			$toreturn = "$toreturn &raquo; $title";
		}
	}
		
	return $toreturn;
	
} 

function drawAllArticles($type)
{
	$pageTitle = ucfirst($type).'s Listing';
	include_once("common/header.php");
	
	$articles = MYSQL_QUERY("SELECT * FROM articles WHERE link != '' AND `line_id` = '0'", locationDBconnect());
	
	for ($i = 0; $i < MYSQL_NUM_ROWS($articles); $i++)
	{
		echo '<h4><a href="/'.$type.'/'.stripslashes(MYSQL_RESULT($articles,$i,"link")).'">'.stripslashes(MYSQL_RESULT($articles,$i,"title")).'</a></h4>';
		echo '<p class="details">'.stripslashes(MYSQL_RESULT($articles,$i,"description")).'</p>';
	}
	include_once("common/footer.php");
}

function fixParagraphs($text)
{
	// fix old crap and remove
	$description = eregi_replace('\[\]', ' ', $text);
	$description = eregi_replace('<br/><br/>', ' ', $description);
	return eregi_replace('<br/>', ' ', $description);
}

function drawDiagramTabs($diagramData)
{
?>
<div class="centeredTable">
<ul id="maintab" class="shadetabs">
<?	
	// draw the tabs headers for all diagram tabs
	for ($i = 0; $i < sizeof($diagramData); $i++)
	{
		if ($i == sizeof($diagramData)-1) {
			$selected = ' class="selected"';
		}
		else {
			$selected = '';
		}
?>
	<li<?=$selected ?>><a href="#year<? echo $diagramData[$i][2]; ?>" rel="year<? echo $diagramData[$i][2]; ?>" ><? echo $diagramData[$i][2]; ?></a></li>
<?	}	
?>
</ul>
<div class="tabcontentstyle">
<?
	// draw the diagram tabs themselves
	for ($i = 0; $i < sizeof($diagramData); $i++)
	{	
?>
<div id="year<? echo $diagramData[$i][2]; ?>" name="year<? echo $diagramData[$i][2]; ?>" class="tabcontent">
	<div id="tabtitle<? echo $i; ?>" ><br/><h5><? echo $diagramData[$i][2]; ?></h5> <a href="#diagrams" class="credit">Back to Year Listing</a></div>
	<img src="/t/<? echo $diagramData[$i][0].'.gif'; ?>" alt="<? echo $name.' '.$diagramData[$i][1]; ?>" title="<? echo $name.' '.$diagramData[$i][1]; ?>" />
</div>
<?	
	}	
?>
</div>
<? 
	/* fixes which tab is open */ 
?>
<script type="text/javascript">
initializetabcontent("maintab")
</script>
</div>
<? 	/* end tabs div */	
	
} // end function

/*
 * PUBLIC
 *
 * Gets description for the text
 * formatted correctly with <p> tags between paragraphs
 * and subsheadings too
 *
 * {{LINE TO LINK TO}}
 * or {{LOCATION TO LINK TO|nice name}}
 *
 * [[LOCATION TO LINK TO]]
 * or [[LOCATION TO LINK TO|nice name]]
 *
 * [[Image:IMAGE URL TO LINK TO]]
 * or [[Image:IMAGE URL TO LINK TO|caption]]
 * or [[Image:IMAGE URL TO LINK TO|thumb|caption]]
 * 
 */
function drawFormattedText($text)
{	
	echo getFormattedText($text);
}
	
function getFormattedText($text, $simple=false)
{
	// parse links
	$description = fixParagraphs($text);
	$description = parseLinks($description, $simple);
	
	// split it and start to display it
	$description = split ("==", $description);
	$size = sizeof($description);
	
	// check for if the first part is not a heading markup, so just spit it out
	if (substr($text, 0, 2) != '==')
	{
		$toreturn .= getParagraph($description[0], $simple)."\n";
	}
	
	// loop though each heading and associated text
	for ($i = 1; $i < $size; $i++)
	{
		if ($i % 2 == 0)
		{
			$toreturn .= getParagraph($description[$i], $simple)."\n";
			
			if (!$simple)
			{
				$toreturn .= "<p><a href=\"#top\" class=\"credit\">Top</a></p>\n";
			}
		}
		else if (!$simple)
		{
			$toreturn .= "<h4 id=\"".convertToLink($description[$i])."\">".$description[$i]."</h4>\n<hr/>";
		}
	}
	
	return $toreturn;
}	//end function

/*
 * PRIVATE
 */
function getParagraph($text, $simple=true)
{
	$toreturn = "";
	$section = split("\n",$text);
	$sectionRows = sizeof($section);
	
	for ($j = 0; $j < $sectionRows; $j++)
	{
		$section[$j] = eregi_replace("\n", "", $section[$j]);
		
		// test for HTML tags
		if (substr(ltrim($section[$j]), 0, 1) == '<')
		{
			// check for inline formatting
			$formattting = substr(ltrim($section[$j]), 0, 3);
			if ($formattting == '<i>' OR $formattting == '<b>' OR $formattting == '<a ' OR $formattting == '<im')
			{
				$toreturn .= "<p>".$section[$j]."</p>\n";
			}
			else
			{
				$toreturn .= $section[$j]."\n";
			}
		}
		// test to see if not empty
		else if (strlen($section[$j]) > 1)
		{
			$toreturn .= "<p>".$section[$j]."</p>\n";
		}
	}
	
	return $toreturn;
}	// end function

/*
 * PRIVATE
 * give it a bit of text with  "==HEADING==" formating
 * and creates a string with a HTML unordered list of subtitles
 * and links to go to subheadings
 */
function getDescriptionTitles($text, $toReturn=NULL)
{
	$description = split ("==", $text);
	$size = sizeof($description);
	$i = 1; 
	
	while ($i < $size)
	{
		$toReturn[] = '<a href="#'.convertToLink($description[$i]).'">'.$description[$i].'</a>';
		$i = $i+2;
	}
	
	return $toReturn;
}	//end function

function addDescriptionTitles($toReturn, $text)
{
	$toReturn[] = '<a href="#'.convertToLink($text).'">'.ucfirst($text).'</a>';
	return $toReturn;
}	//end function

// get pretty header photo
// return boolean states if it has beeen drawn or not
function drawHeaderPic($type, $link, $pageTitle, $caption='')
{		
	$headerpic = strtolower("/images/$type/$link.jpg");
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$headerpic))
	{
		if ($caption == '')
		{
			$caption = $pageTitle;
		}
		
		$imgsize = getimagesize($_SERVER['DOCUMENT_ROOT'].$headerpic);
		echo "<img class=\"photo-right\" src=\"$headerpic\" alt=\"$caption\" title=\"$caption\" $imgsize[3] \>\n";
		return true;
	}
	return false;
}

/*
 * PUBLIC
 * give it a bit of text with  "==HEADING==" formating
 * and it displays an unordered list with of a table of contents
 */
function printDescriptionTitles($descriptionTabs)
{
	if (sizeof($descriptionTabs) > 1)
	{
		echo "<h3 id=\"top\">Contents</h3>\n";
		echo "<ul>\n";
		
		for ($i = 0; $i < sizeof($descriptionTabs); $i++)
		{
			echo "<li>".$descriptionTabs[$i]."</li>\n";
		}
		
		echo "</ul>\n";
	}
}

function draw404InvalidSubpage($pageUrlRoot, $subpage='subpage')
{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	
	$pageTitle = "404 Page Not Found";
	include_once("common/header.php");
	echo "<p class=\"error\">Error - Invalid $subpage!</p>\n";
	echo "<a href=\"/$pageUrlRoot\">Return</a>";
	include_once("common/footer.php");
	return;
}

/*
 * PUBLIC
 * give it a bit of text with  "==HEADING==" formating
 * and it returns how many headings there are
 */
function getDescriptionSize($text)
{
	$description = split ("==", $text);
	$size = sizeof($description)/2;
	return $size-1;
}	//end function

/*
 * PRIVATE
 * parses text for links and returns with HTML
 * using wiki style formatting
 * [[LOCATION TO LINK TO]]
 * or [[LOCATION TO LINK TO|nice name]]
 *
 * [[Image:IMAGE URL TO LINK TO]]
 * or [[Image:IMAGE URL TO LINK TO|caption]]
 * 
 */
function parseLinks($text, $simple=false)
{
	// check if the first bit of text is a link
	if (substr($text, 0, 1) == '[[')
	{
		$firstlink = true;
	}
	
	// fixes for lineguide links
	$description = str_replace('{{', '[[lineguide:', $text);
	$description = str_replace('}}', '[[', $description);
	
	$description = str_replace(']]', '[[', $description);
	$description = split ("\[\[", $description);
	$size = sizeof($description);
	
	if($size > 1)
	{
		// if fist bit isn't a link, append it to output;
		if($firstlink == false)
		{
			$toreturn .= $description[0];
		}
		
		$i = 1;
		while( $i < $size-1)
		{
			// check that is it is a image link
			$tocheck = substr($description[$i], 0, 6);
			
			if ($tocheck == 'Image:' OR $tocheck == 'image:')
			{
				// test for optional link title
				$description[$i] = str_replace('.html', '', $description[$i]);
				$description[$i] = str_replace('.htm', '', $description[$i]);
				$title = split ("\|", $description[$i]);
				$title[0] = str_replace('Image:', '', $title[0]);
				$title[0] = str_replace('image:', '', $title[0]);
				
				// custom title found - set it
				if(sizeof($title) == 3)
				{
					$linktitle = $title[2];
					$imgsize = '150_cw150_ch150';
				}
				elseif(sizeof($title) == 2)
				{
					$linktitle = $title[1];
					$imgsize = '640';
				}
				else
				{
					$linktitle = 'Image';
				}
				
				$toreturn .= '<a href="/gallery/'.$title[0].'.html">';
				$toreturn .= '<img src="/gallery/cache/'.$title[0].'_'.$imgsize.'.jpg" title="'.$linktitle.'" alt="'.$linktitle.'" /></a>';
				$toreturn .= '<p class="credit">'.$linktitle.'</p>';
				$toreturn .= $description[$i+1];
			}
			else
			{
				$currentSection = $description[$i];
				
				// check for line:xxx, article:yyy, region:zzz type links
				$type = split ("\:", $currentSection);
								
				if (sizeof($type) > 1)
				{
					$currentSection = $type[1];
					
					switch ($type[0])
					{
						case 'line':
						case 'lineguide':
						case 'lineguides':
						$linkType = 'lineguide';
						break;
						case 'region':
						case 'regions':
						$linkType = 'region';
						break;
						case 'article':
						case 'articles':
						$linkType = 'article';
						break;
						default:
						$linkType = 'location';
						break;
					}
				}
				else
				{
					$currentSection = $type[0];
					$linkType = 'location';
				}
			
				// test for optional link title
				$title = split ("\|", $currentSection);
				
				// custom title found - set it
				if (sizeof($title) > 1)
				{
					$linktitle = $title[1];					
				}
				else
				{
					$linktitle = $title[0];
				}
				
				// output URL of location
				if ($simple)
				{
					$toreturn .= $linktitle;
				}
				else
				{
					$toreturn .= "<a href=\"/$linkType/".convertToLink($title[0]).'">'.$linktitle.'</a>';
				}
				
				// output rest of text
				$toreturn .= $description[$i+1];
			}
			$i = $i+2;
		}
	}
	// if no links found
	else
	{
		$toreturn = $text;
	}
	return $toreturn;
}

/*
 * PRIVATE
 * parses text for links and returns with HTML
 * using wiki style formatting
 * {{LINE TO LINK TO}}
 * or {{LOCATION TO LINK TO|nice name}}
 *
function parseLineguideLinks($text)
{
	// check if the first bit of text is a link
	if (substr($text, 0, 1) == '{{')
	{
		$firstlink = true;
	}
	
	$description = str_replace('{{', '}}', $text);
	$description = split ("}}", $description);
	$size = sizeof($description);
	
	if($size > 1)
	{
		// if fist bit isn't a lik, append it to output;
		if($firstlink == false)
		{
			$toreturn .= $description[0];
		}
		
		$i = 1;
		while( $i < $size-1)
		{
			// test for optional link title
			$title = split ("\|", $description[$i]);
			
			// custom title found - set it
			if(sizeof($title) > 1)
			{
				$linktitle = $title[1];
			}
			else
			{
				$linktitle = $title[0];
			}
			
			// output URL of line
			$toreturn .= '<a href="/lineguide/'.convertToLink($title[0]).'">'.$linktitle.'</a>';
			
			// output rest of text
			$toreturn .= $description[$i+1];
			$i = $i+2;
			
		}
	}
	// if no links found
	else
	{
		$toreturn = $text;
	}
	return $toreturn;
}
 */

function drawNextAndBackLinks($index, $totalimg, $max, $url)
{
	$page = $index/$max;
	
	if ($index > 0 OR $totalimg >= $max)
	{	?>
<table class="nextables"><tr><td>
<?
		if ($index > 0)
		{
			if ($index - $max < 0)
			{
				$index = $max;
			}
?>
<a class="prev" href="<? echo $url.($page) ?>" title="Previous Page"><span>&laquo;</span> Previous</a>
<?
		}
		
		if ( ($totalimg - $index) >= $max)
		{	
?>
<a class="next" href="<? echo $url.($page+2) ?>" title="Next Page">Next <span>&raquo;</span></a>
<?
		}
?>
</td></tr></table>
<?
	}
} // end function

function drawNumberCurrentDispayedRecords($maxRecordsPerPage,$numberOfRecords,$searchPageNumber)
{
	if ($numberOfRecords != $totalNumberOfRecords)
	{
		$lowerBound = ($maxRecordsPerPage*$searchPageNumber)+1;
		$upperBound = $lowerBound+$numberOfRecords-1;
		$extraBit = "$lowerBound to $upperBound shown on this page";
	}
	return $extraBit;
}

/*
 * prints a pretty dodad that lists the total number of pages in a set
 * give it the index you are up to,
 * the total number of items,
 * the number  to go per page,
 * and the URL to link to
 */
function drawPageNumberLinks($index, $totalimg, $max, $url)
{
	$total = floor(($totalimg)/$max)+1;
	$current = $index/$max;
	
	echo '<p>';
  
  	if ($total > 0)
  	{
		echo 'Page: ';
	}
	
	if ($current > 3 AND $total > 7)
	{
		echo "\n <a href=\"$url\" alt=\"First page\" title=\"First page\">1</a>&nbsp;"; 
		
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


/*
 * input distance as a number
 * and the type keyword from the DB ($type)
 * and it gets formatted
 */
function formatDistance($km, $kmAccuracy)
{
	if ($kmAccuracy == 'exact')
	{
		return "$km km";
	}
	elseif ($kmAccuracy == 'approx')
	{
		$kmonly = split('\.',$km);
		if (sizeof($kmonly) > 1)
		{
			return '<i>'.$kmonly[0].' km</i>';
		}
		else
		{
			return '<i>'.$km.' km</i>';
		}
	}
	else
	{
		return '';
	}	
}

/*
 * input formatted date string (eg: "Monday, 16 November 1959 ") ($fdate)
 * and the type keyword from the DB ($type)
 * and it gets formatted
 */
function formatDate($fdate, $type)
{
	switch ($type)
	{
		case 'exact':
			return $fdate;
			break;
		case 'approx':
			return '<abbr title="By this date">('.$fdate.')</abbr>';
			break;
		case 'year':
			$str = split(' ', $fdate);
			return $str[3];
			break;
		case 'decade':
			$str = split(' ', $fdate);
			$period = substr($str[3], 3, 1);
			$str = substr($str[3], 0, 3)."0s";
			
			if ($period == 0)
			{}
			else if ($period < 5) 
			{
				$str = "Early $str";
			}
			else if ($period >= 5) 
			{
				$str = "Late $str";
			}
			return $str;
			break;
		case 'month':
			$str = split(' ', $fdate);
			return $str[2].' '.$str[3];
			break;
		default:
			return $fdate;
	}
}	// end function

/*
 * pass it a string ($name)
 * and a keyword ($keyword)
 * and it gets highlighted
 */
function highlight($keyword, $name)
{
	if ($keyword != '')
	{
		$bgcolor="#FFFF99";
		$start_tag = "<span style=\"background-color: $bgcolor\">";
		$end_tag = "</span>";
		$highlighted_results = $start_tag . $keyword . $end_tag;
		$highlightName = eregi_replace($keyword, $highlighted_results, $name);
		return $highlightName;
	}
	return $text;
}	// end function

function getLineguidePages($line, $type='list')
{
	if ($line['showTrack'])
	{
		if ($line['trackSubpageCount'] > 1)
		{
			if ($type != 'headbar')
			{
				$beyondFirstText = 'Track Diagram ';
				$elaborateText = 'part ';
			}
			
			$toreturn[] = array("diagram/page-1", "Track Diagram (".$elaborateText."1)", "Track Diagram (".$elaborateText."1)");
			
			for ($i = 2; $i <= $line['trackSubpageCount']; $i++)
			{
				$toreturn[] = array("diagram/page-$i", "$beyondFirstText($elaborateText$i)", "$beyondFirstText($elaborateText$i)");
			}
		}
		else
		{
			$toreturn[] = array('diagram', 'Track Diagram', 'Track Diagram');
		}
	}
	if ($line['showSafeworking'])
	{
		$toreturn[] = array('safeworking', 'Safeworking Diagram', 'Safeworking Diagram');
	}
	if ($line['showEvents'])
	{
		$toreturn[] = array('events', 'Events', 'Events Listing');
	}
	if ($line['showLocations'] AND $line['lineLocations'] > 0)
	{
		$toreturn[] = array('locations', 'Locations', 'Locations Listing');
	}
	if ($line['showGoogleMap'])
	{
		$toreturn[] = array('map', 'Google Map', 'Google Map');
	}
	
	// draw 'extra' page links...
	$extrasLength = sizeof($line['pageNameArray']);
	
	for ($i = 1; $i < $extrasLength; $i++)
	{
		$toreturn[] = $line['pageNameArray'][$i];
	}

	return $toreturn;
}

function drawLinedLocationsTable($locationData)
{
	$numberOfLocations = sizeof($locationData);
	$numberOfColummns = sizeof($locationData['headertitle'])-1;
	$numberOfSettingEntries = 5;
	
	if ($numberOfLocations > $numberOfSettingEntries)
	{
		echo $locationData['sorttext'];
?>
<table class="linedTable" id="locationTable">
<tr>
<?
		if ($locationData['pageurl'] != '')
		{
			for ($r = 0; $r < $numberOfColummns; $r++)
			{
	?>
	<th<?=$locationData['headerstyle'][$r]?>><a href="<?=$locationData['pageurl'] ?>/<?=$locationData['headerurl'][$r]?>"><?=$locationData['headertitle'][$r]?></a>
	</th>
<?
			}
			
			// one off for the actual link
?>
	<th<?=$locationData['headerstyle'][$numberOfColummns]?> align="left"><a href="<?=$locationData['pageurl'] ?>/<?=$locationData['headerurl'][$numberOfColummns]?>"><?=$locationData['headertitle'][$numberOfColummns]?></a>
	</th>
</tr>
<?
		}
		else
		{		
			for ($r = 0; $r < $numberOfColummns; $r++)
			{
	?>
	<th<?=$locationData['headerstyle'][$r]?>><?=$locationData['headertitle'][$r]?>
	</th>
<?
			}
			
			// one off for the actual link
?>
	<th<?=$locationData['headerstyle'][$numberOfColummns];?> align="left"><?=$locationData['headertitle'][$numberOfColummns];?></th>
</tr>
<?					
		}
		
		// fix number of rows by removing the initial ones containing settings
		$numberOfLocations-=$numberOfSettingEntries;
	}
	
	// skips the header cells
	$i = 0;
	
	while ($i < $numberOfLocations)
	{
		if ($i%2 == '0')
		{
			$style = 'class="x"';
		}
		else
		{
			$style = 'class="y"';
		}
		
		echo "<tr $style>\n";
		
		for ($c = 0; $c < $numberOfColummns; $c++)
		{
			echo "<td>".$locationData[$i][$c]."</td>\n";
		}
?>
	<td align="left"><a href="<? echo $locationData[$i][$numberOfColummns+1].'">'.$locationData[$i][$numberOfColummns].'</a>';?></td>
</tr>
<?
		$i++;
	}	
	// end while
	
	echo "</table>\n";
	return;	//end function
}

function getLocationDescriptionLengthImage($input, $events=0)
{
	$modifier = 150;
	
	//get length
	if (is_numeric($input))
	{
		$length = $input;
	}
	else
	{
		$length = strlen($input);
	}
	
	// fix for short text entries
	if ($length < 150 and $length > 50)
	{
		$length = 200;
	}
	// add one unit to the length if the location has events
	else if ($events)
	{
		$length += 200;
	}
	
	if ($length > 50*$modifier)
	{
		return '<img src="/images/rank5.gif" alt="Essay" title="Essay" />';
	}
	elseif ($length > 20*$modifier)
	{
		return '<img src="/images/rank4.gif" alt="Very Detailed" title="Very Detailed" />';
	}
	elseif ($length > 10*$modifier)
	{
		return '<img src="/images/rank3.gif" alt="Detailed" title="Detailed" />';
	}
	elseif ($length > 5*$modifier)
	{		
		return '<img src="/images/rank2.gif" alt="Beginning" title="Beginning" />';
	}
	elseif ($length > 1*$modifier)
	{
		return '<img src="/images/rank1.gif" alt="Basic" title="Basic" />';
	}
	else
	{
		return '';
	}
}

function showPhotos($text)
{
	return ($text != '0' AND $text != '');
}

function getLocationUrlBase($id, $name, $uniqueName)
{
	
	if (!$uniqueName OR strpos($name, '/') OR strpos($name, '-'))
	{
		return $id;
	}
	else
	{
		return convertToLink($name);
	}
}

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.

function truncateString($string, $limit, $break=".", $pad="...") 
{
	// return with no change if string is shorter than $limit
  	if(strlen($string) <= $limit) return $string; 

  	// is $break present between $limit and the end of the string?
  	if(false !== ($breakpoint = strpos($string, $break, $limit))) 
  	{ 
  		if($breakpoint < strlen($string) - 1)
  		{
  			$string = substr($string, 0, $breakpoint) . $pad; 
  		}
  	}    
	return $string;
}
?>