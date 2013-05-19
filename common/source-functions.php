<?php

/*

Functions relating to sources and credits

*/



/*
 * returns a string with the credits for an object (location or railline
 * a formatted unordered list items
 *
 */
function getObjectSources($type, $id, $credits)
{
	$toreturn = '';
	
	// format passed credits initially
	if ($credits != '')
	{
		$toreturn = "<h4 id=\"sources\">Sources</h4><hr/>\n<ul>\n";
	}
	
	// then overwrite credits with sources formated from DB if found
	if ($id != '')
	{
		// gets the sources for this location
		$sql = sprintf("SELECT * FROM object_sources, sources 
			WHERE object_sources.source_id = sources.source_id 
			AND %s_id = '%s' ORDER BY name", mysql_real_escape_string($type), mysql_real_escape_string($id));
		$result = MYSQL_QUERY($sql, locationDBconnect());
		$numberOfRows = MYSQL_NUM_ROWS($result);
		
		if ($numberOfRows >= 1)
		{
			//start of sting
			$toreturn = "<h4 id=\"sources\">Sources</h4><hr/>\n<ul>\n";
			
			for ($i = 0; $i < $numberOfRows; $i++)
			{
				$toreturn .= getFormattedSource($result,$i);
			}
		}
	}
	
	// add old style credits onto the end
	if ($credits != '')
	{
		$credits = fixParagraphs($credits);
		$credits = eregi_replace("\r\n", '</li><li>', $credits);
		$credits = eregi_replace("\n", '</li><li>', $credits);
		$credits = eregi_replace("</p><p>", '</li><li>', $credits);
		$credits = eregi_replace("</p>\n<p>", "</li>\n<li>", $credits);
		
		$credits = "<li>".$credits."</li>\n</ul>";
		$toreturn .= eregi_replace("<li></li>", '', $credits);
	}
	else if ($toreturn != '')
	{
		//end of string
		$toreturn = $toreturn."</ul>\n";
	}
	return $toreturn;
}	// end of function

/*
 * returns a string of text for the relevant source
 * requires to be passes a SQL result set
 *
 */
function getFormattedSource($result, $i)
{
	$sourceName = stripslashes(MYSQL_RESULT($result,$i,"name"));
	$extra = stripslashes(MYSQL_RESULT($result,$i,"extra"));
	$sourceId = MYSQL_RESULT($result,$i,"source_id");
	$date = stripslashes(MYSQL_RESULT($result,$i,"date"));
	$page = stripslashes(MYSQL_RESULT($result,$i,"page"));
	$url = stripslashes(MYSQL_RESULT($result,$i,"url"));
	$url_title = stripslashes(MYSQL_RESULT($result,$i,"url_title"));	
	
	if ($page != '')
	{
		$extra .= " page $page";
	}
	
	if ($url != '' AND $url_title != '')
	{
		if ($extra != '') {
			$extra = $extra." - ";
		}
		$extra .= "<a href=\"$url\">$url_title</a>";
	}
	
	if ($url_title != '')
	{
		if ($extra != '') {
			$extra = $extra." - ";
		}
		$extra .= $url_title;
	}
	
	if ($date != '')
	{
		$extra .= " ($date)";
	}
	
	// skip the link if it is just a website
	if (strtolower($sourceName) == 'website')
	{
		return 
		"<li>$extra</li>\n";
	}
	else
	{
		if ($extra != '') {
			$extra = " - ".$extra;
		}
		return '<li><a href="/sources.php#id'.$sourceId.'">'.$sourceName.'</a>'.$extra."</li>\n";
	}
}
?>