<?php

include_once(dirname(__FILE__) . "/../common/dbConnection.php");

function parseDescriptionForMap($description)
{
	if (strstr($description, "[[kml:"))
	{
		$segements = split ("\[\[kml:", $description);
		$segements = split ("]]", $segements[1]);
		return $segements[0];
	}
	
	return false;
}

function generateKMLScript($mapKML)
{
	$mapKMLlocation = "http://" . $_SERVER['HTTP_HOST'] . "/images/kml/$mapKML?session=" . rand(100000000,900000000);
	
	$mapHTML = "<script type=\"text/javascript\">\n";
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
	$mapHTML .= "        map.addControl(new GSmallZoomControl());\n";
	$mapHTML .= "        map.addControl(new GMenuMapTypeControl());\n";
	$mapHTML .= "        map.addMapType(G_PHYSICAL_MAP);\n";
	$mapHTML .= "        map.setCenter(new GLatLng(-38.14454755370596, 144.3548154830932),7);\n";
	$mapHTML .= "        map.addOverlay(geoXml);\n";
	$mapHTML .= "        map.setZoom(map.getBoundsZoomLevel(geoXml.getDefaultBounds()));\n";
	$mapHTML .= "        map.setCenter(geoXml.getDefaultCenter());\n";
	$mapHTML .= "      }\n";
	$mapHTML .= "    }\n";
	return $mapHTML . "</script>\n";
}

function insertMapElement($description, $mapKML)
{	
	$mapHTML .= '<div id="map" class="inlinemap"></div>';
	
	return str_replace("[[kml:$mapKML]]", $mapHTML, $description);
}
	
	

?>