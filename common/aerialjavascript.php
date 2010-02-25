<? include_once("dbConnection.php");
//PHP SCRIPT: getlocations.php
Header("content-type: application/x-javascript");	

$lineId = 	$_REQUEST["lineguide"];
$lineLink = $_REQUEST["link"];
$types = 	$_REQUEST["types"];
$lines = 	$_REQUEST["lines"];

//This function gets the file names of all images in the current directory
//and ouputs them as a JavaScript array
function returnlocations() 
{
	if (is_numeric($lineId))
	{
		$bit = " AND line_id = $lineId";
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
				echo "loc[9][$i]=\"$typeName\";\n";
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
echo "loc[8] = new Array();\n";
echo "loc[9] = new Array();\n\n";
returnlocations(); //Output the array elements containing the image file names
?>
var map;
var xmlHttp;
var numbers = '';
var lines = '';
var types = '';
var center;
var mapzoom;
var geoXml;
var bounds;
<?	// set up the inital values if given as params

if ($types != '' OR $lines != '')
{	?>
function initialcustom()
{	
<?
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
}		// end php 'returnlocations($lineId)' function

// for use with the map used on a tab of the lineguide
// include KML file /images/kml/kml-[LINEID.kml
// as well as all markers for the line
if ($lineId != '')
{	?>
function loadLineguideAll() {
	selectAll(document.getElementById("customtypes"), 'all');
	
	bounds = new GLatLngBounds();
	//geoXml = new GGeoXml("/images/kml/kml-<?=$lineId;?>.kml", function() {
	geoXml = new GGeoXml("http://www.vicsig.net/infrastructure/lineguide/kml/werribee.kml", function() {
		if (geoXml.loadedCorrectly()) {
		//	geoXml.gotoDefaultViewport(map);
		}
    });
    
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));
       	var marker, lines;
        new GKeyboardHandler(map);
        map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		map.setCenter(new GLatLng(-38.14454755370596, 144.3548154830932), 13, G_SATELLITE_MAP);

		for (var i = 0; i < loc[0].length; i++) {
			point = new GLatLng(loc[0][i], loc[1][i]);
			bounds.extend(point);
			marker = createMarker(point, loc[3][i], getInfoxboxText(i));
			map.addOverlay(marker);
		}
        map.setZoom(map.getBoundsZoomLevel(bounds));
        map.setCenter(bounds.getCenter());
        map.addOverlay(geoXml);
        
        GEvent.addListener(map, "moveend", function() {
         	center = map.getCenter();
          	updatecustom('<?=$lineLink?>', <?=$lineId?>);
        });
		GEvent.addListener(map, "zoomend", function() {
         	mapzoom = map.getZoom();
          	updatecustom('<?=$lineLink?>', <?=$lineId?>);
        });
		
		center = map.getCenter();
		mapzoom = map.getZoom();
	}
	else {
		alert('Sorry! Your browser is not compatible with Google Maps. Please upgrade your browser to a more recent version to use this feature.');
	}
}
<?
}
// end code for tab of the lineguide
	
			
// main Aerial Explorer google map function
// if drawing custom type based on query string
// select document checkboxes later on
/*	
GDownloadUrl("myfile.txt", function(data, responseCode) {
//  alert(data);
});
*/
if ($lineId == '')
{	
?>
function loadExplorerAll(long, lat, zoom) {
	var marker, point, html, bounds;
<? 
if ($types == '' AND $lines == '') { ?>
	selectAll(document.getElementById("customlines"), 'all');
	selectAll(document.getElementById("customtypes"), 'all');
<?	} ?>
	if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map"));
       	bounds = new GLatLngBounds();
        new GKeyboardHandler(map);
        map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		map.addMapType(G_SATELLITE_MAP);
<?	// decide on type of markers
	if ($types != '' OR $lines != '')	{	?>
		initialcustom();
		drawcustom();
<?	} else {	?>
		for (var i = 0; i < loc[0].length; i++) {
			point = new GLatLng(loc[0][i], loc[1][i]);
			bounds.extend(point);
			marker = createMarker(point, loc[3][i], getInfoxboxText(i));
			map.addOverlay(marker);
		}
<?	}	
	// end markers IF
	
	// NOW set the view and zoom options
	// via link, so based on params given...
	if (long != "" AND lat != "" AND zoom != "") {	?>
		map.setCenter(new GLatLng(long, lat), zoom, G_SATELLITE_MAP);
<?	}
	// set default view
	else
	{	?>
		map.setZoom(map.getBoundsZoomLevel(bounds));
        map.setCenter(bounds.getCenter());
<?	}	?>
		GEvent.addListener(map, "moveend", function() {
         	center = map.getCenter();
          	updatecustom('aerial', 'all');
        });
		GEvent.addListener(map, "zoomend", function() {
         	mapzoom = map.getZoom();
          	updatecustom('aerial', 'all');
        });
		
		center = map.getCenter();
		mapzoom = map.getZoom();
	}
	else {
		alert('Sorry! Your browser is not compatible with Google Maps. Please upgrade your browser to a more recent version to use this feature.');
	}
}
<?	
} // end don't display "loadExplorerAll()" function if
?>