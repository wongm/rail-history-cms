<? include_once("dbConnection.php");
//PHP SCRIPT: getlocations.php
Header("content-type: application/x-javascript");	

$lineguide = $_REQUEST["lineguide"];

//This function gets the file names of all images in the current directory
//and ouputs them as a JavaScript array
function returnlocations() 
{
	if (is_numeric($_REQUEST["lineguide"]))
	{
		$bit = 'AND line_id = '.$_REQUEST["lineguide"];
	}
	else
	{
		$bit = '';
	}
	
	$sqlQuery = "SELECT * FROM locations l, location_types lt, locations_raillines lr 
	WHERE lt.type_id = l.type AND l.location_id = lr.location_id
	AND name != '' AND `long` != '' AND `long` != '0' AND display != 'tracks' ".$bit;
	$result = MYSQL_QUERY($sqlQuery, locationDBconnect());
	$numberOfRows = MYSQL_NUM_ROWS($result);
	
	if ($numberOfRows > 0) 
	{	
		for($i = 0; $i < $numberOfRows; $i++)
		{
			$name = stripslashes(MYSQL_RESULT($result,$i,"name")); 
			$id = stripslashes(MYSQL_RESULT($result,$i,"l.location_id")); 
			$typeName = stripslashes(MYSQL_RESULT($result,$i,"lt.basic"));
			$coords = split (', ', stripslashes(MYSQL_RESULT($result,$i,"long"))); 
			$photos = stripslashes(MYSQL_RESULT($result,$i,"photos")); 
			$events = stripslashes(MYSQL_RESULT($result,$i,"events")); 
			$length = strlen((MYSQL_RESULT($result,$i,"description"))); 
			$type = stripslashes(MYSQL_RESULT($result,$i,"type")); 
			$lines = stripslashes(MYSQL_RESULT($result,$i,"line_id")); 
			
			if (sizeOf($coords) == 2)
			{
				echo "loc[0][$i]=\"$coords[0]\";\n";
				echo "loc[1][$i]=\"$coords[1]\";\n";
				$infoBox = '<a href=\"/locations.php?id='.$id.'\" onclick=\"o(this.href); return false;\" class=\"infobox\" target=\"_blank\" ><h5>'.$name .'</h5><br/>'.$typeName.'<br/>';
				
				if ($photos == 1)
				{
					$infoBox = $infoBox.'<img src=\"./images/photos.gif\" alt=\"Photos\" title=\"Photos\" />';
				}
				if ($events == 1)
				{
					$infoBox = $infoBox.'<img src=\"./images/events.gif\" alt=\"Events\" title=\"Events\" />';
				}
				if ($length > 100)
				{
					$infoBox = $infoBox.'<img src=\"/images/details.gif\" alt=\"Detailed History\" title=\"Detailed History\" />';
				}
				$infoBox = $infoBox.'</a>';
				echo "loc[2][$i]=\"$name\";\n";
				echo "loc[3][$i]=\"$type\";\n";
				echo "loc[4][$i]=\"$lines\";\n";
				echo "loc[5][$i]=\"$id\";\n";
				echo "loc[6][$i]=\"$photos\";\n";
				echo "loc[7][$i]=\"$events\";\n";
				echo "loc[8][$i]=\"$length\";\n";
			}
		}
	}	// end if
}
  

echo "var loc = new Array();\n"; //Define array in JavaScript
echo "loc[0] = new Array();\n";
echo "loc[1] = new Array();\n";
echo "loc[2] = new Array();\n";
echo "loc[3] = new Array();\n";
echo "loc[4] = new Array();\n";
echo "loc[5] = new Array();\n";
echo "loc[6] = new Array();\n";
echo "loc[7] = new Array();\n";
echo "loc[8] = new Array();\n\n";
returnlocations(); //Output the array elements containing the image file names
?>

var map;
var xmlHttp
var numbers = '';
var lines = '';
var types = '';
var center;
var mapzoom;

<?	// set up the inital values if given as params
$types = $_REQUEST["types"];
$lines = $_REQUEST["lines"];

if ($types != '' OR $lines != '')
{	?>
function initialcustom()
{	<?
	$typesarray = split(',', $types);
	$linesarray = split(',', $lines);
	
	// get the types of locations we want
	for ($i = 0; $i < sizeOf($typesarray);  $i++)
	{
		switch ($typesarray[$i]) 
		{
			case 's':	/*	stations	*/	?>	
	document.getElementById('s').checked = true;
	numbers = numbers+'15,37,';
	types = types+"s,";		
	<?
				break;	
			case 'i':	/*	industry	*/	?>	
	document.getElementById('i').checked = true;
	numbers = numbers+'30,';
	types = types+"i,";		
	<?
				break;	
			case 'b':	/*	signal boxes	*/	?>		
	document.getElementById('b').checked = true;
	numbers = numbers+'29,';
	types = types+"b,";		
	<?
				break;	
			case 'r':	/*	roads	*/		?>	
	document.getElementById('r').checked = true;
	numbers = numbers+'1,2,3,4,5,6,7,8,9,10,11,12,13,14,';
	types = types+"r,";	
	<?
				break;	
			case 'm':	/*	misc	*/		?>
	document.getElementById('m').checked = true
	numbers = numbers+'27,31,33,34,36,';
	types = types+"m,";	
	<?
				break;
			default:
		}	// end switch
	}
	/* end of for loop */	?>
	types = (types+",").replace(',,','');	
	numbers = (numbers+",").replace(',,','');	
	<?
	
	
	// get the lines we want
	for ($j = 0; $j < sizeOf($linesarray);  $j++)
	{	?>
	document.getElementById('<? echo $linesarray[$j]; ?>').checked = true;
	lines=lines+<? echo $linesarray[$j]; ?>+",";	<?
	}	/* end for loop */	?>
	lines = (lines+",").replace(',,','');
}
	<?	// end javascript 'initialcustom()' function
}		// end php 'returnlocations($lineguide)' function

/* creates an apropriate icon for a given type of location */ ?>

function getIcon(t)
{
	var rIcon = new GIcon(G_DEFAULT_ICON, '/images/maps/brown_MarkerR.png');
	var sIcon = new GIcon(G_DEFAULT_ICON, "/images/maps/orange_MarkerS.png");
	var iIcon = new GIcon(G_DEFAULT_ICON, "/images/maps/darkgreen_MarkerI.png");
	var bIcon = new GIcon(G_DEFAULT_ICON, "/images/maps/purple_MarkerS.png");
	var mIcon = new GIcon(G_DEFAULT_ICON, "/images/maps/red_MarkerM.png");
	var jIcon = new GIcon(G_DEFAULT_ICON, "/images/maps/yellow_MarkerJ.png");
	
	var mapIcon = new GIcon();
	mapIcon.image = "/images/maps/mapupdated.gif";
	mapIcon.shadow = "";
	mapIcon.iconSize = new GSize(148, 49);
	mapIcon.shadowSize = new GSize(0, 0);
	mapIcon.iconAnchor = new GPoint(6, 20);
	mapIcon.infoWindowAnchor = new GPoint(5, 1);
	
	if(t == "map")
	{
		return mapIcon;
	}
	else if(t == 1 || t == 2 || t == 3 || t == 4 || t == 5 || t == 6 || t == 7 || t == 8 || t == 9 || t == 10 || t == 11 || t == 12 || t == 13 || t == 14)
	{
		return rIcon;
	}
	else if(t == 15 || t == 37)
	{
		return sIcon;
	}
	else if(t == 30)
	{
		return iIcon;
	}
	else if( t == 29)
	{
		return bIcon;
	}
	else if( t == 27)
	{
		return jIcon;
	}
	else
	{
		return mIcon;
	}
}

<? /* creates new google map markers */ ?>
function createMarker(point, html, type) 
{
	var marker = new GMarker(point, getIcon(type));
	GEvent.addListener(marker, "click", function() 
		{ marker.openInfoWindowHtml(html); });
  	return marker;
}

<? /* main google map function */ ?>
function loadall(long, lat, zoom)
{
	var bounds = new GLatLngBounds();
<?
		if ($lineguide != '')
		{	?>
var geoXml = new GGeoXml("http://120.17.28.56/railgeelong/images/kml/kml-<?=$lineguide;?>.kml", function() {
	   			if (geoXml.loadedCorrectly()) {
		 			geoXml.gotoDefaultViewport(map);
		 			alert('loaded');
	   			}
	   			else
	   			{
		   			alert('notloaded');
	   			}
      		});
<? } ?>
<? /*	
GDownloadUrl("myfile.txt", function(data, responseCode) {
//  alert(data);
});
<?*/
if ($types != '' OR $lines != '')	/* select document checkboxes */
{}
elseif($lineguide == '')
{ ?>
	selectAll(document.getElementById("customlines"), 'all');
	selectAll(document.getElementById("customtypes"), 'all');
<?
}	?>
		
	if (GBrowserIsCompatible()) 
	{
        map = new GMap2(document.getElementById("map"));
       	var marker, point, html;
        new GKeyboardHandler(map);
        map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		<?
		if ($lineguide != '')
		{	?>
		
		map.setCenter(new GLatLng(-38.14454755370596, 144.3548154830932), 13, G_SATELLITE_MAP);
<?		}
		else if (long != "" AND lat != "" AND zoom != "")
		{?>
			map.setCenter(new GLatLng(long, lat), zoom, G_SATELLITE_MAP);
		<?}
		// for lineguide map
		else
		{	?>
			map.setCenter(new GLatLng(-38.14454755370596, 144.3548154830932), 13, G_SATELLITE_MAP);
<?		}
		
		if (($types != '' OR $lines != '') AND $lineguide == '')
{	?>
initialcustom();
drawcustom();
	<?
}
else
{	?>
		for (var i = 0; i < loc[0].length; i++)
		{
			point = new GLatLng(loc[0][i], loc[1][i]);
			bounds.extend(point);
			marker = createMarker(point, loc[2][i], loc[3][i]);
			map.addOverlay(marker);
		}
		<?
		if ($lineguide != '')
		{	?>
		map.setZoom(map.getBoundsZoomLevel(bounds));
         map.setCenter(bounds.getCenter());
         map.addOverlay(geoXml);
         map.setZoom(map.getBoundsZoomLevel(geoXml.getDefaultBounds()));
         map.setCenter(geoXml.getDefaultCenter());

<?	}	
}

if($lineguide == '')
{ ?>
		GEvent.addListener(map, "moveend", function() {
         	center = map.getCenter();
          	updatecustom();
        });
		GEvent.addListener(map, "zoomend", function() {
         	mapzoom = map.getZoom();
          	updatecustom();
        });
		
		center = map.getCenter();
		mapzoom = map.getZoom();
		<?	}	?>
	}
	else
	{
		alert('Sorry! Your browser is not compatible with Google Maps. Please upgrade your browser to a more recent version to use this feature.');
	}
}

<? /* run this to update the 'link' field. pass "draw" as the param, and the map will update too */ ?>
function updatecustom(param)
{
	numbers = '';
	lines = '';
	types = '';

	if (document.getElementById('s').checked == true)
	{
		numbers = numbers+'15,37,';
		types = types+"s,";
	}
	if (document.getElementById('i').checked == true)
	{
		numbers = numbers+'30,';
		types = types+"i,";
	}
	if (document.getElementById('b').checked == true)
	{
		numbers = numbers+'29,'
		types = types+"b,";
	}
	if (document.getElementById('r').checked == true)
	{
		numbers = numbers+'1,2,3,4,5,6,7,8,9,10,11,12,13,14,';
		types = types+"r,";
	}
	if (document.getElementById('m').checked == true)
	{
		numbers = numbers+'27,31,33,34,36,';
		types = types+"m,";
	}
	types = (types+",").replace(',,','');
	
	var y=document.getElementById('customlines');
	for (var j=0;j<y.length;j++)
	{
		if (y.elements[j].checked == true)
		{
			lines=lines+y.elements[j].name+',';
		}
	}
	lines = (lines+",").replace(',,','');
	
<?
if ($lineguide != '')
{	?>
	// set direct link
	var directlink = "http://railgeelong.com/lineguide.php?line=<?=$lineguide;?>&section=map&center="+center.toUrlValue()+"&zoom="+mapzoom;
<?
}
else
{	?>
	// set direct link
	var directlink = "http://railgeelong.com/aerial.php?center="+center.toUrlValue()+"&zoom="+mapzoom;
<?
}	?>
	
	if (lines != ",")
	{
		directlink += '&lines='+lines;
	}
	
	if (types != ",")
	{
		directlink += '&types='+types;
	}
	
	document.getElementById('directlink').value = directlink;
		
	if (param == 'draw')
	{
		drawcustom()
	}
}

<? /* redraw the google map, besed on entered data */ ?>
function drawcustom()
{
	var typeArray = new Array();
	var lineArray = new Array();
	typeArray = numbers.split(',');
	lineArray = lines.split(',');
	
	map.clearOverlays();
	
	// loop thru markers array
	for (var i = 0; i < loc[0].length; i++)
	{
		var infoBox = '';
		var insertL = false;
		var insertT = false;
		
		for (j = 0; j < typeArray.length; j++)
		{
			if (typeArray[j] == loc[3][i])
			{
				insertT = true;
				break;
			}
		}
			
		for (k = 0; k < lineArray.length; k++)
		{
			if (lineArray[k] == loc[4][i])
			{
				insertL = true;
				break;
			}
		}
		
		if ((insertL == true) && (insertT == true))
		{
			point = new GLatLng(loc[0][i]);
			infoBox = '<a href=\"/locations.php?id=' + loc[5][i] + '\" onclick=\"o(this.href); return false;\" class=\"infobox\" target=\"_blank\" ><h5>' + loc[2][i] + '</h5><br/>' + loc[2][i] + '<br/>';
			
<? /* photos */ ?>
			if (loc[6][i] == 1)
			{
				infoBox += '<img src=\"./images/photos.gif\" alt=\"Photos\" title=\"Photos\" />';
			}
<? /* events */ ?>
			if (loc[7][i] == 1)
			{
				infoBox += '<img src=\"./images/events.gif\" alt=\"Events\" title=\"Events\" />';
			}
<? /* events */ ?>
			if (loc[8][i] > 100)
			{
				infoBox += '<img src=\"/images/details.gif\" alt=\"Detailed History\" title=\"Detailed History\" />';
			}
			infoBox += '</a>';
			
			marker = createMarker(point, infoBox, loc[3][i]);
			map.addOverlay(marker);
		}
	}
	var mapmarker = new GMarker(map.getCenter(), getIcon('map'));
	GEvent.addListener(map, "click", function() 
		{ map.removeOverlay(mapmarker); });
	GEvent.addListener(map, "dragstart", function() 
		{ map.removeOverlay(mapmarker); });
	map.addOverlay(mapmarker);
}