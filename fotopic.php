<?php 

session_start(); 
/*
 * PROXY SETTINGS
 * if required, it probably will not be
 */
$aContext = array(
	/* 
   		'http' => array(
        'proxy' => 'tcp://136.186.1.14:8000', // This needs to be the server and the port of the NTLM Authentication Proxy Server.
        'request_fulluri' => True,
      	),
	*/
    );
/*
 * END PROXY SETTINGS
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Fotopic exporter</title>
</head>
<body>
<?php
$cxContext = stream_context_create($aContext);

/*
 * SETUP INPUT VARIABLES
 */

$galid = $_REQUEST['galid'];
$colid = $_REQUEST['colid'];

// for posted variables, save in session
if (!isset($_SESSION['u']))
{
	$u = $_REQUEST['u'];
	
	// for passwords
	if (isset($_REQUEST['m']))
	{
		$p = md5($_REQUEST['m']);
	}
	else
	{
		$p = $_REQUEST['p'];
	}
	
	// set session variables
	$_SESSION['u'] = $u;
	$_SESSION['p'] = $p;
}
else
{
	$u = $_SESSION['u'];
	$p = $_SESSION['p'];
}

if (isset($_REQUEST['logout']))
{
	session_unregister($_SESSION['u']);
	session_unregister($_SESSION['p']);
	session_destroy();
	echo "<h1>Logged out!</h1><hr>";
	echo '<a href="fotopic.php">Return!</a>';
}
/*
 * END INPUT VARIABLES
 */
 
/*
 * OPTION: HOME PAGE
 * with login form
 */
else if ($p == '' AND $u == '')
{
?>
<h1>Fotopic converter!</h1>
<p>Converts info in your <a href="http://fotopic.net/">http://fotopic.net/</a> gallery to other formats for use elsewhere</p>

<form name="passwordForm" method="POST" action="<?=$_server['php-self']?>"><table>
<tr><td><input type="text" name="u" size="40" value=""></td><td>Your Fotopic.net username (email addess you registered with)</td></tr>
<tr><td><input type="password" name="m" size="40" value=""></td><td>Password</td></tr>
<tr><td></td><td>OR</td></tr>
<tr><td><input type="text" name="p" size="40" value=""></td><td>MD5 hash of your password *[NOTE]</td></tr>
<tr><td><input type="submit" value="Start"></td><td></td></tr>
</table></form>
<p>A <a href="http://en.wikipedia.org/wiki/MD5">MD5 hash</a> is a way to share passwords, but without telling someone what they are. What make it more secure than sharing the password is that you can make a MD5 from a password, but you can't find the password if you have the MD5.</p>

<p>To make one for your password go <a href="http://www.miraclesalad.com/webtools/md5.php">here</a> or Google 'md5 Hash Generator'. Once there input you password, and it will generate a long random word. Copy and paste it into the above box.</p>
<?
}
/*
 * OPTION: LIST ALL FOTOPIC COLLECTIONS FOR A GIVEN GALLERY
 */
elseif ($galid != '' AND $colid == '' AND $colid != 'ALL')
{
	$simple = file_get_contents("http://toolkit.fotopic.net/?action=collection.list&u=$u&p=$p&galid=$galid", False, $cxContext);
	
	$parser = xml_parser_create();
	xml_parse_into_struct($parser, $simple, $vals, $index);
	xml_parser_free($parser);
	
	echo '<h1>Select collection:</h1>';
	echo "<a href=\"?colid=all&galid=$galid\">ALL</a> (<a href=\"?colid=all&galid=$galid&sql\">as SQL</a>) (<a href=\"?colid=all&galid=$galid&filenames\">filename list</a>)<p>Very slow to do!</p><hr/>";
	
	for ($i = 0; $i < sizeof($vals); $i++)
	{
		if ($vals[$i]['tag'] == 'RESULT')
		{
			if ($vals[$i+1]['tag'] == 'COLLECTION')
			{
				$colid = $vals[$i+1]['attributes']['ID'];
				//echo $colid;
				
				if ($vals[$i+2]['tag'] == 'TITLE')
				{
					$coltitle = $vals[$i+2]['value'];
					//echo $coltitle;
					
					if ($vals[$i+14]['tag'] == 'COMMENTARY')
					{
						$commentary = $vals[$i+14]['value'];
						//echo $commentary;
						
						echo "<a href=\"?colid=$colid\">$coltitle</a> (<a href=\"?colid=$colid&sql\">as SQL</a>)  (<a href=\"?colid=$colid&filenames\">filename list</a>)<p>$commentary</p><hr/>";
					}
				}
			}
		}
	}
	echo 'FIN';
}
/*
 * OPTION: LIST DETAILS OF ALL PHOTOS IN A SELECTED COLLECTION
 */
else if ($colid != '' AND $colid != 'ALL' AND $galid == '')
{
	echo '<h1>All photos in collection:</h1>';
	
	if (isset($_REQUEST['sql']) OR isset($_REQUEST['filenames']))
	{
		echo '<textarea wrap="VIRTUAL" cols="120" rows="50">';
	}
	else
	{
		echo '<ul>';
	}
	
	$simple = file_get_contents("http://toolkit.fotopic.net/?action=photo.list&u=$u&p=$p&colid=$colid", False, $cxContext);
	$parser = xml_parser_create();
	xml_parse_into_struct($parser, $simple, $vals, $index);
	xml_parser_free($parser);
	
	for ($i = 0; $i < sizeof($vals); $i++)
	{
		if ($vals[$i]['tag'] == 'RESULT')
		{
			if ($vals[$i+1]['tag'] == 'PHOTO')
			{
				$phid = $vals[$i+1]['attributes']['ID'];
				
				if ($vals[$i+2]['tag'] == 'FILENAME')
				{
					$phfile = $vals[$i+2]['value'];
					
					if ($vals[$i+4]['tag'] == 'DESCRIPTION')
					{
						$phdesc = $vals[$i+4]['value'];
						
						if (isset($_REQUEST['sql']))
						{
							$phdesc = fixvar($phdesc);
							$phdesc = addslashes($phdesc);
							$phfile = addslashes($phfile);
							
							echo "UPDATE `table_name` SET `title_field` = '$phdesc' WHERE `title_field` = '$phfile';\n";
						}
						else if (isset($_REQUEST['filenames']))
						{
							echo "$phfile\n";
						}
						else
						{
							echo "<li><i>$phfile</i>: $phdesc (Fotopic ID = $phid) </li>";
						}
					}
				}
			}
		}
	}
	
	if (isset($_REQUEST['sql']) OR isset($_REQUEST['filenames']))
	{
		echo '</textarea><br>';
	}
	else
	{
		echo '</ul>';
	}
	echo 'FIN';
}
/*
 * OPTION: LIST ALL PHOTOS IN A SELECTED GALLERY
 */
else if ($galid != '' AND $colid == 'all')
{
	echo '<h1>All photos in GALLERY!</h1>';
	
	// get all collections in gallery
	$simple = file_get_contents("http://toolkit.fotopic.net/?action=collection.list&u=$u&p=$p&galid=$galid", False, $cxContext);
	$parser = xml_parser_create();
	xml_parse_into_struct($parser, $simple, $vals, $index);
	xml_parser_free($parser);
	
	// HTML formattting at start
	if (isset($_REQUEST['sql']) OR isset($_REQUEST['filenames']))
	{
		echo '<textarea wrap="VIRTUAL" cols="120" rows="50">';
	}
	// end of HTML formattting at start
	
	//get each collection in the gallery
	for ($i = 0; $i < sizeof($vals); $i++)
	{
		if ($vals[$i]['tag'] == 'RESULT')
		{
			if ($vals[$i+1]['tag'] == 'COLLECTION')
			{
				$colid = $vals[$i+1]['attributes']['ID'];
				
				if ($vals[$i+2]['tag'] == 'TITLE')
				{
					if (isset($_REQUEST['sql']) OR isset($_REQUEST['filenames']))
					{}
					else
					{
						$coltitle = $vals[$i+2]['value'];
						echo "<h2>$coltitle</h2>";
						echo '<ul>';
					}
					
					// reset time limit for this section!
					set_time_limit(30);
					
					$simple = file_get_contents("http://toolkit.fotopic.net/?action=photo.list&u=$u&p=$p&colid=$colid", False, $cxContext);
					$parser = xml_parser_create();
					xml_parse_into_struct($parser, $simple, $colvals, $index);
					xml_parser_free($parser);
					
					/// get each photo from the given collection
					for ($j = 0; $j < sizeof($colvals); $j++)
					{
						if ($colvals[$j]['tag'] == 'RESULT')
						{
							if ($colvals[$j+1]['tag'] == 'PHOTO')
							{
								$phid = $colvals[$j+1]['attributes']['ID'];
								
								if ($colvals[$j+2]['tag'] == 'FILENAME')
								{
									$phfile = $colvals[$j+2]['value'];
									
									if ($colvals[$j+4]['tag'] == 'DESCRIPTION')
									{
										$phdesc = $colvals[$j+4]['value'];
										
										if (isset($_REQUEST['sql']))
										{
											$phdesc = fixvar($phdesc);
											$phdesc = addslashes($phdesc);
											$phfile = addslashes($phfile);
											
											echo "UPDATE `table_name` SET `title_field` = '$phdesc' WHERE `title_field` = '$phfile';\n";
										}
										else if (isset($_REQUEST['filenames']))
										{
											echo $phfile."\n";
										}
										else
										{
											echo "<li><i>$phfile</i>: $phdesc (Fotopic ID = $phid) </li>";
										}
									}
								}
							}
						}
					}
					
					if (isset($_REQUEST['sql']) OR isset($_REQUEST['filenames']))
					{}
					else
					{
						echo '</ul>';
					}
				}
			}
		}
	}
	
	// HTML formattting at end			
	if (isset($_REQUEST['sql']) OR isset($_REQUEST['filenames']))
	{
		echo '</textarea><br>';
	}
	echo 'FIN';
	// end of HTML formattting at end
}
/*
 * OPTION: LIST ALL FOTOPIC GALLERIES FOR A GIVEN AUTHENTICATED USER NAME
 */
else
{
	$simple = file_get_contents("http://toolkit.fotopic.net/?action=gallery.list&u=$u&p=$p", False, $cxContext);
	$parser = xml_parser_create();
	xml_parse_into_struct($parser, $simple, $vals, $index);
	xml_parser_free($parser);
	
	echo '<h1>Select gallery:</h1>';
	
	if ($vals[1]['tag'] == 'ERROR')
	{
		echo $vals[1]['value'];
		echo '<br><a href="/fotopic.php">Incorrect user authentication details - Return and try again!</a>';
	}
	else
	{
		for ($i = 0; $i < sizeof($vals); $i++)
		{
			if ($vals[$i]['tag'] == 'RESULT')
			{
				if ($vals[$i+1]['tag'] == 'GALLERY')
				{
					$galid = $vals[$i+1]['attributes']['ID'];
					
					if ($vals[$i+2]['tag'] == 'TITLE')
					{
						$galtitle = $vals[$i+2]['value'];
						
						if ($vals[$i+4]['tag'] == 'HOSTNAME')
						{
							$galurl = $vals[$i+4]['value'];
							
							echo "<a href=\"?galid=$galid\">$galtitle</a> ($galurl)<br/>";
						}
					}
				}
			}
		}
		echo 'FIN';
	} // end else
}

function fixvar($phdesc)
{
	$phdesc = str_replace('&quot;', '\"', $phdesc);
	$phdesc = str_replace('&ldquo;', '\"', $phdesc);
	$phdesc = str_replace('&rdquo;', '\"', $phdesc);
	$phdesc = str_replace('&lsquo;', '\"', $phdesc);
	$phdesc = str_replace('&rsquo;', '\"', $phdesc);
	return $phdesc;
}
?>
<hr>
<a href="fotopic.php?logout=">Logout!</a>
</body>
</html>