<?php

require_once("definitions.php");

function parseDescriptionForMap($description)
{
	if (strstr($description, "[[kml:"))
	{
		$segements = explode("[[kml:", $description);
		$segements = explode("]]", $segements[1]);
		return $segements[0];
	}
	
	return false;
}

function generateKMLScript($mapKMLpath, $lineId)
{
	global $_zp_themeroot;
	$mapKMLlocation = "https://" . $_SERVER['HTTP_HOST'] . "$mapKMLpath?session=" . rand(100000000,900000000);
	
	$mapHTML =  "<script src=\"https://maps.googleapis.com/maps/api/js?key=" . getOption('gmap_api_key') . "\" type=\"text/javascript\"></script>\n";
	
	if ($lineId > 0)
	{
    	$mapHTML .= "<script src=\"//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>\n";
    	$mapHTML .= "<script src=\"" . $_zp_themeroot . "/js/aerialfunctions.js?v=3245345345\" type=\"text/javascript\"></script>\n";
	}
	
	$mapHTML .= "<script type=\"text/javascript\">\n";
	$mapHTML .= "window.onload = loadLineguideAll;\n";
	$mapHTML .= "var map;\n";
	$mapHTML .= "var markers = [];\n";
	$mapHTML .= "var infowindow = new google.maps.InfoWindow();\n";
	$mapHTML .= "var bounds = new google.maps.LatLngBounds();\n";
	$mapHTML .= "function loadLineguideAll()\n";
	$mapHTML .= "	{\n";
	$mapHTML .= "		map = new google.maps.Map(document.getElementById('map'));\n";
	$mapHTML .= "		var geoXml = new google.maps.KmlLayer(\"$mapKMLlocation\", {\n";
	$mapHTML .= "		 suppressInfoWindows: false,\n";
	$mapHTML .= "		 preserveViewport: false,\n";
	$mapHTML .= "		 map: map\n";
	$mapHTML .= "		});\n";
	
	if ($lineId > 0)
	{
    	$mapHTML .= "var mapDataUrl = \"/aerialdata.php?line=" . $lineId . "\";\n";
    	$mapHTML .= "$.getJSON( mapDataUrl , function( data ) {\n";
    	$mapHTML .= "	$.each( data.locations, function( index, location ) {\n";
    	$mapHTML .= "		addMarker(location.name, location.id, location.lat, location.lng, location.type, location.line, location.icon, location.infoBox);\n";
    	$mapHTML .= "	});\n";
    	$mapHTML .= "	map.fitBounds(bounds);\n";
    	$mapHTML .= "});\n";
	}
	
	$mapHTML .= "   }\n";
	return $mapHTML . "</script>\n";
}


	//$mapHTML .= "		map.setZoom(map.getBoundsZoomLevel(geoXml.getDefaultBounds()));\n";
	//$mapHTML .= "		map.setCenter(geoXml.getDefaultCenter());\n";
function generateMapElement()
{
	return '<div id="map" class="inlinemap"></div>';
}

function replaceMapElement($description, $mapKML)
{
	$mapElement = generateMapElement();
	
	return str_replace("[[kml:$mapKML]]", $mapElement, $description);
}

/*
 *
 * Draw a Google Map for a specific location
 * Either map or satellite views
 *
 *
 *
 *
 */
function drawGoogleMapForSpecificLocation($view, $id)
{
	$pageTitle = "Aerial Explorer";
	
	// get data from DB
	$sql = "SELECT * FROM locations l
		INNER JOIN location_types lt ON lt.type_id = l.type 
		WHERE location_id = '".$id."'";
		
	$result = query_full_array($sql);
	
	if (sizeof($result) == 0)
	{
		echo '<p class="error">Error - Invalid Location!</p>';
	}
	else	// start zero result if
	{
		$name = stripslashes($result[0]["name"]); 
		$typeName = stripslashes($result[0]["basic"]);
		$coords = stripslashes($result[0]["long"]); 
		$photos = stripslashes($result[0]["photos"]); 
		$events = stripslashes($result[0]["events"]); 
		$length = strlen(($result[0]["description"]));	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html height="100%" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo getGalleryTitle();?> - <?php echo $pageTitle;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="<?php echo getGalleryDesc();?>" />
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all" title="Normal" />
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo getOption('gmap_api_key'); ?>&sensor=false"></script>
<script type="text/javascript">
<?php /* google map function - single location */ ?>
var map, marker, infowindow;
function initialize() {
	var locationLatlng = new google.maps.LatLng(<?php echo $coords; ?>);
	var mapOptions = {
		zoom: 15,
		center: locationLatlng,
		scaleControl: true,
		mapTypeControl: true,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
		zoomControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL
		},
<?php if ($view == 'map') { ?>
		mapTypeId: google.maps.MapTypeId.ROADMAP
<?php } else if ($view == 'satellite') { ?>
		mapTypeId: google.maps.MapTypeId.SATELLITE 
<?php } ?>
	};	
	map = new google.maps.Map(document.getElementById('map'), mapOptions);	
	infowindow = new google.maps.InfoWindow({
		position: locationLatlng,
		content: '<b><?php echo addslashes($name); ?></b><p><?php echo $typeName ?></p>'
	});
	marker = new google.maps.Marker({
		position: locationLatlng,
		map: map,
		title: '<?php echo addslashes($name); ?>'
	});
	marker.setIcon('https://maps.google.com/mapfiles/ms/icons/blue.png');	
	infowindow.open(map,marker);
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
};
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<noscript>
Sorry! Your browser is not compatible with Google Maps. Please upgrade your browser to a more recent version to use this feature.
</noscript>
</head>
<body style="margin: 0;">
<div name="map" id="map" style="padding: 0; position: absolute; width: 100%; height: 100%;"></div>
</body>
</html>
<?php
		} // end if
}	/*	end overview if	*/
?>