<?php
include_once("../common/dbConnection.php");
include_once("../common/lineguide-functions.php");
include_once("../common/formatting-functions.php");

$pageTitle = "Line Guides";
include_once("header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Line Guides</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once('midbar.php'); ?>
<h3>Introduction to the line guides</h3>
<img class="photo-right" src="/images/geelong-region.gif" alt="Geelong region railway lines" title="Geelong region railway lines" usemap="#linemap" height="402" width="500" />
<map name="linemap" id="linemap">
	<area shape="poly" coords="214,259,118,277,11,232,19,218,211,252,214,259" href="/lineguide/gheringhap-maroona" alt="Gheringhap - Maroona Line" title="Gheringhap - Maroona Line">
	<area shape="poly" coords="246,282,278,273,278,263,243,262,246,282" href="/lineguide/fyansford" alt="Fyansford Line" title="Fyansford Line">
	<area shape="poly" coords="300,276,283,276,282,287,298,291,301,277,300,276" href="/lineguide/cunningham" alt="Cunningham Pier Line" title="Cunningham Pier Line">
	<area shape="poly" coords="103,2,145,31,222,240,277,258,276,264,218,258,90,13,103,2" href="/lineguide/ballarat" alt="Geelong - Ballarat Line" title="Geelong - Ballarat Line">
	<area shape="poly" coords="394,336,283,307,285,289,373,287,399,323,394,337,394,336" href="/lineguide/queenscliff" alt="Queenscliff Line" title="Queenscliff Line">
	<area shape="poly" coords="60,378,277,349,290,248,499,143,500,75,278,221,277,287,54,315,60,378" href="/lineguide/geelong" alt="Melbourne - Geelong - Warrnambool Line" title="Melbourne - Geelong - Warrnambool Line">
</map>

<ul>
	<li>Track diagrams</li>
	<li>Safeworking diagrams</li>
	<li>Line events</li>
	<li>Location histories</li>
</ul>
<p>They're all here - and the diagrams change according to the year you want to see.</p>
<p>Choose a line on the map to the right, or a link from below to start.</p>

<h4 style="clear:both;" >The lines...</h4><hr/>
<?php
drawAllLineguideDotpoints(false);
include_once("footer.php");
?>