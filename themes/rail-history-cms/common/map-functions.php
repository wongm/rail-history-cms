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

function generateKMLScript($mapKML)
{
	$mapKMLlocation = "http://" . $_SERVER['HTTP_HOST'] . "/images/kml/$mapKML?session=" . rand(100000000,900000000);
	
	$mapHTML =  "<script src=\"http://maps.google.com/maps?file=api&v=2&key=" . GOOGLE_KEY_v3 . "\" type=\"text/javascript\"></script>\n";
	$mapHTML .= "<script type=\"text/javascript\">\n";
	$mapHTML .= "window.onload = loadLineguideAll;\n";
	$mapHTML .= "function loadLineguideAll()\n";
	$mapHTML .= "    {\n";
	$mapHTML .= "      var map;\n";
	$mapHTML .= "      var geoXml = new GGeoXml(\"$mapKMLlocation\", function() {\n";
	$mapHTML .= "	   if (geoXml.loadedCorrectly()) {\n";
	$mapHTML .= "		 geoXml.gotoDefaultViewport(map);\n";
	$mapHTML .= "	   }\n";
	$mapHTML .= "      });\n";
	$mapHTML .= "	if (GBrowserIsCompatible())\n";
	$mapHTML .= "      {\n";
	$mapHTML .= "        map = new GMap2(document.getElementById(\"map\"));\n";
	$mapHTML .= "        map.addMapType(G_PHYSICAL_MAP);\n";
	$mapHTML .= "        map.addOverlay(geoXml);\n";
	$mapHTML .= "        map.setZoom(map.getBoundsZoomLevel(geoXml.getDefaultBounds()));\n";
	$mapHTML .= "        map.setCenter(geoXml.getDefaultCenter());\n";
	$mapHTML .= "      }\n";
	$mapHTML .= "    }\n";
	return $mapHTML . "</script>\n";
}

function generateMapElement()
{
	return '<div id="map" class="inlinemap"></div>';
}

function replaceMapElement($description, $mapKML)
{
	$mapHTML = generateMapElement();
	
	return str_replace("[[kml:$mapKML]]", $mapHTML, $description);
}

?>