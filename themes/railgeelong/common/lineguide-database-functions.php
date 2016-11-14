<?php
include_once(dirname(__FILE__) . "/../common/dbConnection.php");


/*
 * get extra lineguide pages
 * these are stored in the articles table
 * if no articles is found for the given URL
 * then false is returned
 *
 */
function getLineguideExtraPage($line, $section)
{
	$extrasPageSQL = sprintf("SELECT * FROM articles WHERE `line_id` = '%s' AND `link` = '%s'", 
		mysql_real_escape_string($line['lineId']), mysql_real_escape_string($section));
	$extras = MYSQL_QUERY($extrasPageSQL, locationDBconnect());
	$extrasLength = MYSQL_NUM_ROWS($extras);
				
	if ($extrasLength == 1)
	{
		$line["header"] = MYSQL_RESULT($extras,'0',"title");
		
		// overwrite line data for subpages
		$line["description"] = stripslashes(MYSQL_RESULT($extras,'0',"content"));
		$line["caption"] = stripslashes(MYSQL_RESULT($extras,'0',"caption"));
		$line["photos"] = stripslashes(MYSQL_RESULT($extras,'0',"photos"));
		
		return $line;
	}
	else
	{
		return false;
	}
}

function getLine($lineToDisplay, $yearToDisplay)
{
	//	fix up names and line IDs
	$lineResultSQL = "SELECT r.*, r.link AS pagelink, r.name AS pagetitle, r.description as pagecontent, 
			DATE_FORMAT(r.modified, '%M %e, %Y') AS fdate, 
			count(lr.location_id) AS line_locations, 'page' AS type
			FROM raillines r
			LEFT OUTER JOIN locations_raillines lr ON lr.line_id = r.line_id
			WHERE r.link = '".mysql_real_escape_string($lineToDisplay)."' 
			AND todisplay != 'hide'
			GROUP BY lr.line_id
		UNION ALL 
			SELECT r.*, a.link AS pagelink, a.title AS pagetitle, a.content as pagecontent, 
			DATE_FORMAT(a.modified, '%M %e, %Y') AS fdate, 0 AS line_locations, 'subpage' AS type
			FROM raillines r
			LEFT OUTER JOIN articles a ON a.line_id = r.line_id
			WHERE r.link = '".mysql_real_escape_string($lineToDisplay)."' 
			AND todisplay != 'hide'";
			
	$lineResult = MYSQL_QUERY($lineResultSQL, locationDBconnect());
	$numberOfPageResults = MYSQL_NUM_ROWS($lineResult);
	
	if ($numberOfPageResults > 0)
	{
		// get basic details
		$line = getLineBasicDetails($lineResult, 0);
		
		// get all of the regions
		$line['regions'] = getRegionsForLine($line["lineId"]);
		
		// get locations details
		$line["trackDiagramNote"] = stripslashes(MYSQL_RESULT($lineResult,0,"trackdiagramnote"));
		$line["safeworkingDiagramNote"] = stripslashes(MYSQL_RESULT($lineResult,0,"safeworkingdiagramnote"));
		$line["credits"] = stripslashes(MYSQL_RESULT($lineResult,0,"credits"));
		$line["description"] = stripslashes(MYSQL_RESULT($lineResult,0,"description"));
		$line["caption"] = stripslashes(MYSQL_RESULT($lineResult,0,"imagecaption"));
		$line["photos"] = stripslashes(MYSQL_RESULT($lineResult,0,"photos"));
		$line["updated"] = MYSQL_RESULT($lineResult,0,"fdate"); 
		
		// fix up dates for opening and closing, as well as for filtering
		if(!is_numeric($yearToDisplay) OR $yearToDisplay == "")
		{
			$yearToDisplay = date('Y');
		}
		
		// normal occurance for years
		$line['yearStart'] = $yearToDisplay.'-12-31';
		$line['yearEnd'] = $yearToDisplay.'-12-01';
		$line['yearHeader'] = $yearToDisplay;
		$line['yearToDisplay'] = $yearToDisplay;
		
		// stuff for diagrams
		$line["trackDiagramTabs"] = stripslashes(MYSQL_RESULT($lineResult,0,"trackDiagramTabs"));
		$line["safeworkingYears"] = stripslashes(MYSQL_RESULT($lineResult,0,"safeworkingyears"));
		$line["trackYears"] = stripslashes(MYSQL_RESULT($lineResult,0,"trackyears"));
		$line["safeworkingDefault"] = $safeworkingdefault = MYSQL_RESULT($lineResult,0,"safeworkingdefault");
		$line["trackDefault"] = $trackdefault = MYSQL_RESULT($lineResult,0,"trackdefault");
		
		//	fix up dates for opening and closing, as well as for filtering
		$line['openYear'] = MYSQL_RESULT($lineResult,0,"opened");
		$line['closeYear'] = MYSQL_RESULT($lineResult,0,"closed");
		
		//special stuff for closed lines
		if(!is_numeric($yearToDisplay) OR !isset($_REQUEST['year']) OR $_REQUEST['year'] == "")
		{
			if (!isset($_REQUEST['section']))
			{
				$line['yearEnd'] = '';
				$line['yearStart'] = '';
				$line['yearToDisplay'] = '';
				$line['yearHeader'] = '';
			}
			elseif ($_REQUEST['section'] == 'safeworking')
			{
				if ($safeworkingdefault == 0)
				{
					$line['yearEnd'] = '0000-01-01';
					$line['yearStart'] = '9999-12-31';
					$line['yearToDisplay'] = 'All Locations';
					$line['yearHeader'] = 'All Locations';
				}
				elseif ($safeworkingdefault != '9999')
				{
					$line['yearEnd'] = $safeworkingdefault.'-01-01';
					$line['yearStart'] = $safeworkingdefault.'-12-31';
					$line['yearToDisplay'] = $safeworkingdefault;
					$line['yearHeader'] = $safeworkingdefault;
				}
			}
			elseif ($_REQUEST['section'] == 'diagram')
			{
				if ($trackdefault == 0)
				{
					$line['yearEnd'] = '0000-01-01';
					$line['yearStart'] = '9999-12-31';
					$line['yearToDisplay'] = 'All Locations';
					$line['yearHeader'] = 'All Locations';	
				}
				elseif ($trackdefault != '9999')
				{
					$line['yearEnd'] = $trackdefault.'-01-01';
					$line['yearStart'] = $trackdefault.'-12-31';
					$line['yearToDisplay'] = $trackdefault;
					$line['yearHeader'] = $trackdefault;
				}
			}
		}
		
		for ($i = 0; $i < $numberOfPageResults; $i++)
		{
			$pageTitle = stripslashes(MYSQL_RESULT($lineResult,$i,"pagetitle"));
			$pageLink = strToLower(stripslashes(MYSQL_RESULT($lineResult,$i,"pagelink")));
			$pageContent = stripslashes(MYSQL_RESULT($lineResult,$i,"pagecontent"));
			
			$line['pageNameArray'][] = array($pageLink, $pageTitle, $pageTitle);
			$line['pageContentArray'][] = $pageContent;
		}
			
	}
	else
	{
		$line["lineId"] = "";
		$line["lineName"] = "";
		$line["diagramNote"] = "";
	}
	
	return $line;
}	// end function

function getLineBasicDetails($result, $j)
{	
	$line["lineId"] = stripslashes(MYSQL_RESULT($result,$j,"line_id"));
	$line["lineName"] = stripslashes(MYSQL_RESULT($result,$j,"name"));
	$line["lineLink"] = stripslashes(MYSQL_RESULT($result,$j,"link"));
	$line["trackSubpage"] = stripslashes(MYSQL_RESULT($result,$j,"trackSubpage"));
	$line["trackSubpageCount"] = sizeof(explode(';', $line["trackSubpage"]));
	$line["lineLocations"] = MYSQL_RESULT($result,$j,"line_locations");
	$line["todisplay"] = MYSQL_RESULT($result,$j,"todisplay");
	$todisplay = $line["todisplay"];
	$line["showTrack"] = substr($todisplay, 4, 1) == 1;
	$line["showSafeworking"] = substr($todisplay, 3, 1) == 1;
	$line["showEvents"] = substr($todisplay, 2, 1) == 1;
	$line["showLocations"] = substr($todisplay, 1, 1) == 1;
	
	$line["googleMapUrl"] = '/images/kml/kml-'.$line["lineId"].'.kml';
	$line["showGoogleMap"] = file_exists($_SERVER['DOCUMENT_ROOT'].$line["googleMapUrl"]) == 1;
	
	return $line;
}

function getRegionsForLine($lineid)
{
	$regionResultSQL = "SELECT region.link FROM railline_region rr
			LEFT OUTER JOIN articles region ON region.article_id = rr.article_id
			WHERE rr.line_id = '$lineid'";
			
	$regionResult = MYSQL_QUERY($regionResultSQL, locationDBconnect());
	
	for ($i = 0; $i < MYSQL_NUM_ROWS($regionResult); $i++)
	{
		$regions[] = stripslashes(MYSQL_RESULT($regionResult,$i,"region.link"));
	}
	
	return $regions;
}
?>