<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageFilterType = "";
if (array_key_exists('type', $_GET))
{
	$pageFilterType = $_REQUEST['type'];
}

$pageTitle = 'List location permalinks';
switch ($pageFilterType)
{
	case 'empty';
		$pageTitle .= " - without permalinks";
		break;
	case 'duplicated';
		$pageTitle .= " - duplicated permalinks";
		break;
}

include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT * FROM locations l WHERE ".SQL_NEXTABLE. " ORDER BY name ASC, link ASC";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
$existingLinks = array();
$existingProposed = array();

if ($numberOfRows>0) 
{
?>
<div class="headbar">
	<a href="listLocationPermanlinks.php?type=empty">Without permalinks</a> :: 
	<a href="listLocationPermanlinks.php?type=duplicated">Duplicated permalinks</a> :: 
	<a href="listLocationPermanlinks.php">All</a>
</div>
<TABLE class="linedTable">
	<TR>
		<th>ID</th>
		<th align="left">Name</th>
		<th>Current link</th>
		<th>Valid?</th>
		<th>Proposed link</th>
	</TR>
<?php
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

		$name = stripslashes($result[$i]["name"]);
		$id = stripslashes($result[$i]["location_id"]);
		$link = stripslashes($result[$i]["link"]);
		$isValid = $proposed = $isValidProposed = "";
		$proposed = strtolower(str_replace(' ', '-', $name));
		$display = false;

		if ($pageFilterType == "")
		{
			$display = true;
			if ($link != "")
			{
				$proposed = "";
			}
		}
		else if ($link == "" AND $pageFilterType == 'empty')
		{
			$display = true;
			
			if ($name != "")
			{
				$updateSql = "UPDATE locations SET `link` = '" . $proposed . "' WHERE `location_id` = " . $id;
				if (array_key_exists('act', $_GET))
				{
					query($updateSql);
				}
			}
		}
		else if ($pageFilterType == 'duplicated')
		{
			if ($existingLinks[$link] AND $link != "")
			{
				$display = true;
			}
			else if ($existingProposed[$proposed] && $link == "")
			{
				$display = true;
			}
		}
		
		$existingLinks[$link] = true;
		$existingProposed[$proposed] = true;
		
		if (IsInvalidLink($link))
		{
			$isValid = "invalid!";
		}
		
		if ($link == "")
		{
			if (IsInvalidLink($proposed))
			{
				$isValidProposed = "invalid!";
			}
			$link = "[none]";
		}

		if ($name == "")
		{
			$name = "[none]";
		}

		if ($display)
		{
?>
	<TR class="<?php echo $bgColor; ?>">
		<TD align="center"><?php echo $id ?></TD>
		<TD><a href="editLocations.php?location=<?php echo $id; ?>"><?php echo $name ?></a></td>
		<td><a href="/location/<?php echo $link ?>/" alt="View"><?php echo $link ?></a></td>
		<td><?php echo $isValid ?></td>
		<td><?php echo $proposed ?></td>
		<td><?php echo $updateSql . $isValidProposed ?></td>
	</TR>
<?php
		}	// end if
	} 		// end while loop
}			// end if
?>
</TABLE>
<?php
include_once("common/footer.php");

function IsInvalidLink($link)
{
	return (strpos($link, "/") > 0) || (strpos($link, "'") > 0);
}
?>