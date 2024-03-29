<?php
require_once("definitions.php");


/*
 * get extra lineguide pages
 * these are stored in the articles table
 * if no articles is found for the given URL
 * then false is returned
 *
 */
function getLineguideExtraPage($line, $section)
{
	global $_zp_db;
	$extrasPageSQL = sprintf("SELECT * FROM articles WHERE `line_id` = '%s' AND `link` = %s", 
		($line['lineId']), $_zp_db->quote($section));
	$extras = $_zp_db->queryFullArray($extrasPageSQL);
	$extrasLength = sizeof($extras);
				
	if ($extrasLength == 1)
	{
		$line["header"] = $extras[0]["title"];
		
		// overwrite line data for subpages
		$line["description"] = stripslashes($extras[0]["content"]);
		$line["caption"] = stripslashes($extras[0]["caption"]);
		$line["photos"] = stripslashes($extras[0]["photos"]);
		
		return $line;
	}
	else
	{
		return false;
	}
}

function getLine($lineToDisplay, $yearToDisplay)
{
	global $_zp_db;
    $filter = "";
	// show if admin when page is in edit mode
    if ( !zp_loggedin() ) {
        $filter = " AND todisplay != 'hide'";
    }
    
	//	fix up names and line IDs
	$lineResultSQL = "SELECT r.*, r.link AS pagelink, r.name AS pagetitle, r.description as pagecontent, 
			DATE_FORMAT(r.modified, '%M %e, %Y') AS fdate, 
			count(lr.location_id) AS line_locations, 'page' AS type
			FROM raillines r
			LEFT OUTER JOIN locations_raillines lr ON lr.line_id = r.line_id
			WHERE r.link = ".$_zp_db->quote($lineToDisplay)." 
			$filter
			GROUP BY lr.line_id
		UNION ALL 
			SELECT r.*, a.link AS pagelink, a.title AS pagetitle, a.content as pagecontent, 
			DATE_FORMAT(a.modified, '%M %e, %Y') AS fdate, 0 AS line_locations, 'subpage' AS type
			FROM raillines r
			LEFT OUTER JOIN articles a ON a.line_id = r.line_id
			WHERE r.link = ".$_zp_db->quote($lineToDisplay)." 
			$filter";
			
	$lineResult = $_zp_db->queryFullArray($lineResultSQL);
	$numberOfPageResults = sizeof($lineResult);
	
	if ($numberOfPageResults > 0)
	{
		// get basic details
		$line = getLineBasicDetails($lineResult, 0);
		
		// get all of the regions
		$line['regions'] = getRegionsForLine($line["lineId"]);
		
		// get locations details
		$line["trackDiagramNote"] = stripslashes($lineResult[0]["trackdiagramnote"]);
		$line["safeworkingDiagramNote"] = stripslashes($lineResult[0]["safeworkingdiagramnote"]);
		$line["credits"] = stripslashes($lineResult[0]["credits"]);
		$line["description"] = stripslashes($lineResult[0]["description"]);
		$line["caption"] = stripslashes($lineResult[0]["imagecaption"]);
		$line["photos"] = stripslashes($lineResult[0]["photos"]);
		$line["updated"] = $lineResult[0]["fdate"]; 
		
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
		$line["trackDiagramTabs"] = stripslashes($lineResult[0]["trackdiagramtabs"]);
		$line["safeworkingYears"] = stripslashes($lineResult[0]["safeworkingyears"]);
		$line["trackYears"] = stripslashes($lineResult[0]["trackyears"]);
		$line["safeworkingDefault"] = $safeworkingdefault = $lineResult[0]["safeworkingdefault"];
		$line["trackDefault"] = $trackdefault = $lineResult[0]["trackdefault"];
		
		//	fix up dates for opening and closing, as well as for filtering
		$line['openYear'] = $lineResult[0]["opened"];
		$line['closeYear'] = $lineResult[0]["closed"];
		
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
			$pageTitle = stripslashes($lineResult[$i]["pagetitle"]);
			$pageLink = strToLower(stripslashes($lineResult[$i]["pagelink"]));
			$pageContent = stripslashes($lineResult[$i]["pagecontent"]);
			
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
	$line["lineId"] = stripslashes($result[$j]["line_id"]);
	$line["lineName"] = stripslashes($result[$j]["name"]);
	$line["lineLink"] = stripslashes($result[$j]["link"]);
	$line["trackSubpage"] = stripslashes($result[$j]["tracksubpage"]);
	$line["trackSubpageCount"] = sizeof(explode(';', $line["trackSubpage"]));
	$line["lineLocations"] = $result[$j]["line_locations"];
	$line["todisplay"] = $result[$j]["todisplay"];
	$todisplay = $line["todisplay"];
	
	// show everything if admin and page is in edit mode
	if ($todisplay == 'hide' && zp_loggedin())
	{
    	$line["showTrack"] = true;
    	$line["showSafeworking"] = true;
    	$line["showEvents"] = true;
    	$line["showLocations"] = true;
	}
	else
	{
    	$line["showTrack"] = substr($todisplay, 4, 1) == 1;
    	$line["showSafeworking"] = substr($todisplay, 3, 1) == 1;
    	$line["showEvents"] = substr($todisplay, 2, 1) == 1;
    	$line["showLocations"] = substr($todisplay, 1, 1) == 1;
	}
	
	$line["googleMapUrl"] = '/images/kml/lineguide-'.$line["lineLink"].'.kml';
	$line["showGoogleMap"] = file_exists($_SERVER['DOCUMENT_ROOT'].$line["googleMapUrl"]) == 1;
	
	return $line;
}

function getRegionsForLine($lineid)
{
	global $_zp_db;
	$regionResultSQL = "SELECT region.link FROM railline_region rr
			LEFT OUTER JOIN articles region ON region.article_id = rr.article_id
			WHERE rr.line_id = '$lineid'";
			
	$regionResult = $_zp_db->queryFullArray($regionResultSQL);
	
	for ($i = 0; $i < sizeof($regionResult); $i++)
	{
		$regions[] = stripslashes($regionResult[$i]["link"]);
	}
	
	return $regions;
}
?>