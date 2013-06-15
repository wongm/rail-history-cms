<?php

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

function fixParagraphs($text)
{
	// fix old crap and remove
	$description = eregi_replace('\[\]', ' ', $text);
	$description = eregi_replace('<br/><br/>', ' ', $description);
	return eregi_replace('<br/>', ' ', $description);
}

function getFormattedText($text, $simple=false)
{
	$toreturn = '';
	
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

?>