<?php 	

include_once("common/dbConnection.php");
$pageTitle = "Aerial View";
$pageHeading = $pageTitle;

$thisId = addslashes($_REQUEST['locationField']);
$thisPoint = addslashes($_REQUEST['pointField']);
$locationtoset = addslashes($_REQUEST['locationtoset']);
	 
//start timer
$time = round(microtime(), 3);
session_start();

	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<!-- Description: <?php echo $pageTitle;?> -->
<!-- Author: Marcus Wong -->
<!-- Date: November 28 2006 -->
<title>Rail Geelong - <?php echo $pageTitle;?></title>
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all" title="Normal" />
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>
<script type="text/javascript">

    //<![CDATA[

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
		    var str = point.toString().replace('(','');
		    str = str.replace(')','');
		    window.frames['update'].document.pointUpdateForm.pointField.value = str;
		    marker.openInfoWindowHtml("Add marker: <br>"+point.toString());
		  }
});
      }
    }

    //]]>
</script>
</head>
<!-- start body -->
<body onload="load()" onunload="GUnload()">
<?
if ($locationtoset == '')
{
?>
<table id="container" cellspacing="5">
<tr><td id="header" colspan="2">
<h1><a href="/">RG</a> - <?php echo $pageHeading; ?></h1>
<div id="user_info"><p></p></div>
</td></tr>
<tr><td width="140"></td>
<td id="big" valign="top">
<?
}
else
{
	echo '<div id="container">';
}
?>
<div id="content">
<div align="center"><iframe id="update" name="update" width="95%" height="40px" src="editAerialFrame.php?locationtoset=<?=$locationtoset?>"></iframe></div>
<div id="map" style="width: 95%; margin: auto; height: 800px"></div>
<?
// footer

if ($locationtoset == '')
{
	include_once("common/footer.php"); 
}
else
{
?>
</div></div>
</body>
</html>
<?	
}
?>