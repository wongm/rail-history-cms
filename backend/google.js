function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        new GKeyboardHandler(map);
        map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());

        map.setCenter(new GLatLng(-38.14454755370596, 144.3548154830932), 13, G_HYBRID_MAP);
        
        // Creates a marker at the given point with the given number label
		function createMarker(point) {
  		var marker = new GMarker(point);
  		GEvent.addListener(marker, "click", function() {
    		marker.openInfoWindowHtml("Clickzor: <br>"+point.toString());
  		});
  		return marker;
}

        
         GEvent.addListener(map, "click", function(marker, point) {
		  if (marker) {
		    map.removeOverlay(marker);
		    map.closeInfoWindow();
		  } else {
		    var marker = createMarker(point);
		    map.addOverlay(marker);
		    //document.iframe.pointUpdateField.pointField.value = point.toString();
		    //document.getElementById("pointFiel").pointUpdateForm.pointField.value = point.toString();
		    //document.getElementById("update").src="frame_c.htm"
		    window.frames['update'].document.pointUpdateForm.pointField.value = point.toString();
		    marker.openInfoWindowHtml("Add marker: <br>"+point.toString());
		  }
});
      }
    }