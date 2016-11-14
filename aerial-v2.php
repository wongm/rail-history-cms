<?php
include_once("common/aerial-functions.php");
include_once("common/formatting-functions.php");
$id = $_REQUEST["id"];
$section = $_REQUEST["section"];
$preset = $_REQUEST["preset"];
$center = $_REQUEST["center"];
$zoom = $_REQUEST["zoom"];
$view = $_REQUEST["view"];
$lines = $_REQUEST["lines"];
$types = $_REQUEST["types"];

//drawAllMap($center, $zoom, $types, $lines);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<html>
<head>
<!-- Description: <?php echo $pageTitle;?> -->
<!-- Author: Marcus Wong -->
<title>Rail Geelong - <?php echo $pageTitle;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all" title="Normal" />
<link rel="stylesheet" type="text/css" href="/common/css/aerialstyle.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo GOOGLE_KEY?>" type="text/javascript"></script>
<script src="/common/js/functions.js" type="text/javascript"></script>
<script src="/common/js/aerialfunctions.js" type="text/javascript"></script></head>
<script type="text/javascript">
var markers = new Array(); 

function clearMarkersForLine(line) {
    for(var i = 0; i < markers.length; i++) {
          if (markers[i].line == line) {
            markers[i].hide();
          }
    }
}
function showMarkersForLine(line) {
    for(var i = 0; i < markers.length; i++) {
          if (markers[i].line == line) {
            markers[i].show();
          }
    }
}
function clearMarkersForType(type) {
    for(var i = 0; i < markers.length; i++) {
          if (markers[i].type == type) {
            markers[i].hide();
          }
    }
}
function showMarkersForType(type) {
    for(var i = 0; i < markers.length; i++) {
          if (markers[i].type == type) {
            markers[i].show();
          }
    }
}

function update()
{
	console.log('start');
	$('map').css('color', 'red');
	
	var lines = $('#customlines').find(':checkbox');
	var types = $('#customtypes').find(':checkbox');
	for(var x=0; x < lines.length; x++) {
		if(lines[x].checked) {
			showMarkersForLine(lines[x].id);			
		}
	}
	for(var x=0; x < types.length; x++) {
		if(types[x].checked) {
			showMarkersForType(types[x].id);			
		}
	}
	
	for(var x=0; x < lines.length; x++) {
		if(!lines[x].checked) {
			clearMarkersForLine(lines[x].id);			
		}
	}
	for(var x=0; x < types.length; x++) {
		if(!types[x].checked) {
			clearMarkersForType(types[x].id);			
		}
	}
	$('map').css('color', 'blue');
	console.log('done');
}

function initialize() {
	if (GBrowserIsCompatible()) 
	{
        map = new GMap2(document.getElementById("map"));
       	var marker, point;
        new GKeyboardHandler(map);
        map.enableScrollWheelZoom();
        map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		
		$.get('http://z/json.php', function(data, success) {
	        var locations = JSON.parse(data);
	
	        for(var x=0; x < locations.length; x++) {
				point = new GLatLng(locations[x].lat, locations[x].long);
				marker = createMarker(point, getIcon(locations[x].type.id), getInfoxboxText(locations[x]), locations[x].type.id, locations[x].line);
				map.addOverlay(marker);
	        }
	    }, 'text');
		
		
		GEvent.addListener(map, "moveend", function() {
         	//center = map.getCenter();
          	//updatecustom();
        });
		GEvent.addListener(map, "zoomend", function() {
         	//mapzoom = map.getZoom();
          //	updatecustom();
        });
		
		//center = map.getCenter();
		//mapzoom = map.getZoom();
		
		
        map.setCenter(new GLatLng(-38.14454755370596, 144.3548154830932), 13, G_HYBRID_MAP);
	}
}
function createMarker(point, icon, html, type, line) {
	var marker = new GMarker(point, icon);
    marker.line = line;
    marker.type = type;
    markers.push(marker);
	GEvent.addListener(marker, "click", function() 
		{ marker.openInfoWindowHtml(html); });
  	return marker;
}

function getInfoxboxText(location) {
	infoBox = '<a href=\"/location/' + location.id + '\" onclick=\"o(this.href); return false;\" class=\"infobox\" target=\"_blank\" ><h5>' + location.name + '</h5><br/>' + location.type.name + '<br/>' + location.line;
	
	// photos
	if (location.photos == 1) {
		infoBox += '<img src=\"./images/photos.gif\" alt=\"Photos\" title=\"Photos\" />';
	}
	// events
	if (location.events == 1) {
		infoBox += '<img src=\"./images/events.gif\" alt=\"Events\" title=\"Events\" />';
	}
	// histry
	if (location.length > 100) {
		infoBox += '<img src=\"/images/details.gif\" alt=\"Detailed History\" title=\"Detailed History\" />';
	}
	infoBox += '</a>';
	
	return infoBox;
}

function getIcon(t) {
	var rIcon = new GIcon(G_DEFAULT_ICON, '/images/maps/brown_MarkerR.png');
	var sIcon = new GIcon(G_DEFAULT_ICON, '/images/maps/orange_MarkerS.png');
	var iIcon = new GIcon(G_DEFAULT_ICON, '/images/maps/darkgreen_MarkerI.png');
	var bIcon = new GIcon(G_DEFAULT_ICON, '/images/maps/purple_MarkerS.png');
	var mIcon = new GIcon(G_DEFAULT_ICON, '/images/maps/red_MarkerM.png');
	var jIcon = new GIcon(G_DEFAULT_ICON, '/images/maps/yellow_MarkerJ.png');
	
	var mapIcon = new GIcon();
	mapIcon.image = "/images/maps/mapupdated.gif";
	mapIcon.shadow = "";
	mapIcon.iconSize = new GSize(148, 49);
	mapIcon.shadowSize = new GSize(0, 0);
	mapIcon.iconAnchor = new GPoint(6, 20);
	mapIcon.infoWindowAnchor = new GPoint(5, 1);
	
	if(t == "map") {
		return mapIcon;
	}
	else if(t == 'r') {
		return rIcon;
	}
	else if(t == 's') {
		return sIcon;
	}
	else if(t == 'i') {
		return iIcon;
	}
	else if( t == 'b') {
		return bIcon;
	}
	else if( t == 'j') {
		return jIcon;
	}
	else {
		return mIcon;
	}
}

</script>



<body style="width: 98%; height: 100%; margin: 1%; padding: 0;" onload="initialize()" onunload="GUnload()">
<div name="map" id="map" style="position: absolute; width: 98%; height: 85%; top: 100px;"></div>

<form name="customtypes" id="customtypes">
<label for="s">Stations: </label><input type="checkbox" onclick="update()" id="s" name="s" /> 
<label for="i">Industries: </label><input type="checkbox" onclick="update()" id="i" name="i" /> 
<label for="b">Signal Boxes: </label><input type="checkbox" onclick="update()" id="b" name="b" /> 
<label for="r">Bridges and Crossings: </label><input type="checkbox" onclick="update()" id="r" name="r" /> 
<label for="m">Other: </label><input type="checkbox" onclick="update()" id="m" name="m" />
<a href="#" onclick="selectAll(this.parentNode,'all')" alt="Select All" title="Select All">[A]</a> <a href="#" onclick="selectAll(this.parentNode,'none')" alt="Unselect All" title="Unselect All">[N]</a>
</form>

<form name="customlines" id="customlines"><?php 
$result = MYSQL_QUERY("SELECT * FROM raillines WHERE todisplay != 'hide' ORDER BY name", locationDBconnect());
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows>0) 
{
	for ($i = 0;$i<$numberOfRows; $i++)
	{
		$thisLine_id = stripslashes(MYSQL_RESULT($result,$i,"line_id"));
		$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));	?>
<label for="<?php echo $thisLine_id?>"><?php echo $thisName?>: </label><input onclick="update()" type="checkbox" name="<?php echo $thisLine_id?>" id="<?php echo $thisLine_id?>" />
<?php } // end while loop
}	?>
<a href="#" onclick="selectAll(this.parentNode,'all')" alt="Select All" title="Select All">[A]</a> <a href="#" onclick="selectAll(this.parentNode,'none')" alt="Unselect All" title="Unselect All">[N]</a>
</form>



</body>
</html>