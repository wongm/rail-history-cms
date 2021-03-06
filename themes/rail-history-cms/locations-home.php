<?php 
$pageTitle = 'Locations';
require_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Locations</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once('common/midbar.php'); ?>
<h3>Introduction to the locations database</h3>
<div class="locations">
<p>Here is a listing of all the railway locations in the Geelong Region. Either view by type, or search by name. You can also browse by line from the <a href="/lineguides">lineguides</a>. The sort order can be altered in all cases.</p>
<h4>By Type</h4>
<ul class="tableofcontents"><li><a href="/locations/stations/">Stations</a></li>
<li><a href='/locations/industries/'>Industries</a></li>
<li><a href='/locations/signalboxes/'>Signal Boxes</a></li>
<li><a href='/locations/yards/'>Yards</a></li>
<li><a href='/locations/misc/'>Miscellaneous</a></li></ul>
</div>
<?php
drawLocationSearchBox();
?>
<h4>About the Location listings</h4>
<hr/>
<p>Each location in the database can have a written history, tables of location and line events, track diagrams, and photographs. The listings show what information is available to view for each location.</p>
<p>The 'star guide' shows how detailed each location history is:<br/><br/>
<img src="/images/rank5.gif" alt="Essay" title="Essay" />Essay<br/><br/>
<img src="/images/rank4.gif" alt="Very Detailed" title="Very Detailed" />Very Detailed<br/><br/>
<img src="/images/rank3.gif" alt="Detailed" title="Detailed" />Detailed<br/><br/>
<img src="/images/rank2.gif" alt="Beginning" title="Beginning" />Beginning<br/><br/>
<img src="/images/rank1.gif" alt="Basic" title="Basic" />Basic<br/><br/>
I recommend having a look at the page on <a href="/location/south-geelong/">South Geelong</a> for an example of a detailed page.  ;-)</p>
<?php require_once("common/footer.php"); ?>