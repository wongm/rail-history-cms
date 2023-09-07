<?php
$pageTitle = "Aerial View";
$pageHeading = $pageTitle;

include_once("common/dbConnection.php");
include_once("common/header.php");

$thisId = isset($_REQUEST["locationField"]) ? $_REQUEST["locationField"] : "";
$thisPoint = isset($_REQUEST["pointField"]) ? $_REQUEST["pointField"] : "";
$locationtoset = isset($_REQUEST["locationtoset"]) ? $_REQUEST["locationtoset"] : "";
?>
<style>
#navigation { display: none }
#content { margin: 0; }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo getOption('gmap_api_key'); ?>&callback=initMap" async defer></script>
<script type="text/javascript">
//<![CDATA[
var map;
var marker;
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -38.14454755370596, lng: 144.3548154830932},
          zoom: 13,
          mapTypeId: 'hybrid'
        });
        
    google.maps.event.addListener(map, 'click', function(event) {
        var str = event.latLng.toString().replace('(','');
        str = str.replace(')','');
        window.frames['update'].document.pointUpdateForm.pointField.value = str;
        
        placeMarker(event.latLng);
    });
    
    function placeMarker(location) {
        if (marker == undefined){
            marker = new google.maps.Marker({
                position: location,
                map: map, 
                animation: google.maps.Animation.DROP,
            });
        }
        else{
            marker.setPosition(location);
        }
    }
}
//]]>
</script>
<div id="mapcontent">
    <div align="center"><iframe id="update" name="update" width="95%" height="40px" src="editAerialFrame.php?locationtoset=<?php echo $locationtoset?>"></iframe></div>
    <div id="map" style="width: 95%; margin: auto; height: 800px"></div>
</div>
<?php
// footer

include_once("common/footer.php"); 
?>