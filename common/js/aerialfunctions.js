function createMarker(point, type, html) {
	var marker = new GMarker(point, getIcon(type));
	GEvent.addListener(marker, "click", function() 
		{ marker.openInfoWindowHtml(html); });
  	return marker;
}

function getInfoxboxText(i) {
	infoBox = '<a href=\"/location/' + loc[5][i] + '\" onclick=\"o(this.href); return false;\" class=\"infobox\" target=\"_blank\" ><h5>' + loc[2][i] + '</h5><br/>' + loc[9][i] + '<br/>';
	
	// photos
	if (loc[6][i] == 1) {
		infoBox += '<img src=\"./images/photos.gif\" alt=\"Photos\" title=\"Photos\" />';
	}
	// events
	if (loc[7][i] == 1) {
		infoBox += '<img src=\"./images/events.gif\" alt=\"Events\" title=\"Events\" />';
	}
	// events
	if (loc[8][i] > 100) {
		infoBox += '<img src=\"/images/details.gif\" alt=\"Detailed History\" title=\"Detailed History\" />';
	}
	infoBox += '</a>';
	
	return infoBox;
}

//run this to update the 'link' field. pass "draw" as the param, and the map will update too */ ?>
function updatecustom(source, lineid, param) {
	var directlink;
	numbers = '';
	lines = '';
	types = '';
	
	if (document.getElementById('s').checked == true) {
		numbers = numbers+'15,37,';
		types = types+"s,";
	}
	if (document.getElementById('i').checked == true) {
		numbers = numbers+'30,';
		types = types+"i,";
	}
	if (document.getElementById('b').checked == true) {
		numbers = numbers+'29,'
		types = types+"b,";
	} 
	if (document.getElementById('r').checked == true) {
		numbers = numbers+'1,2,3,4,5,6,7,8,9,10,11,12,13,14,';
		types = types+"r,";
	}
	if (document.getElementById('m').checked == true) {
		numbers = numbers+'27,31,33,34,36,';
		types = types+"m,";
	}
	types = (types+",").replace(',,','');
	
	// set direct link
	if (source == 'aerial') {
		directlink = "http://www.railgeelong.com/aerial.php?center="+center.toUrlValue()+"&zoom="+mapzoom;
		
		var y=document.getElementById('customlines');
		for (var j=0;j<y.length;j++) {
			if (y.elements[j].checked == true) {
				lines=lines+y.elements[j].name+',';
			}
		}
		lines = (lines+",").replace(',,','');
	} else {
		directlink = "http://www.railgeelong.com/lineguide/"+source+"/map/?center="+center.toUrlValue()+"&zoom="+mapzoom;
		numbers = lineid + "," + lineid;
	}
	
	if (lines != ",") {
		directlink += '&lines='+lines;
	}
	
	if (types != ",") {
		directlink += '&types='+types;
	}
	
	document.getElementById('directlink').value = directlink;
		
	if (param == 'draw') {
		drawcustom()
	}
}

// creates an appropriate icon for a given type of location
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
	else if(t == 1 || t == 2 || t == 3 || t == 4 || t == 5 || t == 6 || t == 7 || t == 8 || t == 9 || t == 10 || t == 11 || t == 12 || t == 13 || t == 14) {
		return rIcon;
	}
	else if(t == 15 || t == 37) {
		return sIcon;
	}
	else if(t == 30) {
		return iIcon;
	}
	else if( t == 29) {
		return bIcon;
	}
	else if( t == 27) {
		return jIcon;
	}
	else {
		return mIcon;
	}
}

// redraw the google map, based on entered data
function drawcustom() {
	var typeArray = new Array();
	var lineArray = new Array();
	typeArray = numbers.split(',');
	lineArray = lines.split(',');
	
	map.clearOverlays();
	
	// loop thru markers array
	for (var i = 0; i < loc[0].length; i++) {
		var insertL = false;
		var insertT = false;
		
		for (j = 0; j < typeArray.length; j++) {
			if (typeArray[j] == loc[3][i]) {
				insertT = true;
				break;
			}
		}
			
		for (k = 0; k < lineArray.length; k++) {
			if (lineArray[k] == loc[4][i]) {
				insertL = true;
				break;
			}
		}
		
		if ((insertL == true) && (insertT == true)) {
			point = new GLatLng(loc[0][i],loc[1][i]);
			marker = createMarker(point, loc[3][i], getInfoxboxText(i));
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
}

//opens url in original window
function o(url)
{
	opener.location.href = url;
	opener.focus();
}