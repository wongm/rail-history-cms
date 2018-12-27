

function initialize() {	
	if (lines == '' && lines == '' ) {
		selectAll(document.getElementById("customlines"), 'all');
		selectAll(document.getElementById("customtypes"), 'all');
	} else {
		initialiseCustomFilters();
	}

	updateCustomFilters();
	renderMap(types, lines);
	$('input').bind('change', updateMapOnClick);
}

function updateMapOnClick() {
	updateCustomFilters();
	updateDirectLink();
	updateMapFromCustomFilters();
}

function updateMapFromCustomFilters() {
	if (types.length > 0 || lines.length > 0) {
		markers.forEach(setMarkerVisibility);
	}
}

function setMarkerVisibility(marker) {
	var typeSelected = (types.length == 0 || (types.length > 0 && types.includes(marker.type)));
	var lineSelected = (lines.length == 0 || (lines.length > 0 && lines.includes(marker.line)));
	marker.setVisible(typeSelected && lineSelected);
}

function updateCustomFilters() {
	var directlink;
	types = [];
	lines = [];
	typeNames = [];
	
	if (document.getElementById('s').checked == true) {
		types.push(15,37);
		typeNames.push("s");
	}
	if (document.getElementById('i').checked == true) {
		types.push(30);
		typeNames.push("i");
	}
	if (document.getElementById('b').checked == true) {
		types.push(29);
		typeNames.push("b");
	} 
	if (document.getElementById('r').checked == true) {
		types.push(1,2,3,4,5,6,7,8,9,10,11,12,13,14);
		typeNames.push("r");
	}
	if (document.getElementById('m').checked == true) {
		types.push(27,31,33,34,36);
		typeNames.push("m");
	}
	var y=document.getElementById('customlines');
	for (var j=0;j<y.length;j++) {
		if (y.elements[j].checked == true) {
			lines.push(parseInt(y.elements[j].name));
		}
	}
}

function updateDirectLink() {
	directlink = "https://www.railgeelong.com/aerial.php?center="+map.getCenter().toUrlValue()+"&zoom="+map.getZoom();
	
	if (lines != ",") {
		directlink += '&lines='+lines;
	}
	
	if (typeNames != ",") {
		directlink += '&types='+typeNames;
	}
	
	document.getElementById('directlink').value = directlink;
}

function renderMap()
{
	map = new google.maps.Map(document.getElementById("map-canvas"));
	var mapDataUrl = "aerialdata.php";

	// get data and use it
	$.getJSON( mapDataUrl , function( data ) {
		$.each( data.locations, function( index, location ) {
			addMarker(location.name, location.id, location.lat, location.lng, location.type, location.line, location.icon, location.infoBox);
		});
		
		map.fitBounds(bounds);
		updateDirectLink();
	});
	
	map.addListener('center_changed', updateDirectLink);
	map.addListener('zoom_changed', updateDirectLink);
}

function addMarker(name, id, lat, lng, type, line, icon, infoBox) {
	var latlngs = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
	bounds.extend(latlngs);	
	var marker = new google.maps.Marker({
		position: latlngs,
		map: map,
		icon: icon,
		name: name,
		line: line,
		type: type,
	});
	setMarkerVisibility(marker);
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(infoBox);
		infowindow.open(map, this);
	});
	markers.push(marker);
}

//selects all checkboxes when link clicked
function selectAll(theElement, type)
{
	var theForm = theElement, z = 0;
	for(z=0; z<theForm.length;z++)
	{
		if(type =='all')
		{
			theForm[z].checked = true;
		}
		else
		{
			theForm[z].checked = false;
		}
	}
	updateMapOnClick();
}

//opens url in original window
function o(url)
{
	opener.location.href = url;
	opener.focus();
}