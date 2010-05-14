<?php 

session_start();
$_SESSION['authorised'] = false;

// test for localhost
$server = $_SERVER['HTTP_HOST'];
if ($server == 'z' OR $server == 'localhost')
{
	$localhost = true;
	$_SESSION['authorised'] = true;
	$url = "/backend/admin.php";
	$url = "http://".$_SERVER['HTTP_HOST'].$url;
	header("Location: ".$url,TRUE,302);
}

if (isset($_REQUEST['username']))
{
	$username = addslashes($_REQUEST['username']);
	include_once("common/dbConnection.php");

	$sql = "SELECT  * FROM users WHERE username = '$username'";
	$results = MYSQL_QUERY($sql);

	if (MYSQL_NUM_ROWS($results) == 1)
	{
		$password = md5(addslashes($_REQUEST['password']));
		
		if (MYSQL_RESULT($results,0,"password") == $password)
		{		
			$_SESSION['authorised'] = true;
			$url = "/backend/admin.php";
			$url = "http://".$_SERVER['HTTP_HOST'].$url;
			header("Location: ".$url,TRUE,302);
		}
		else
		{
			fail();
		}
	}
	else
	{
		fail();
	}
}
else
{
	fail();
}

function fail()
{
	session_unregister($_SESSION['username']);
	session_unregister($_SESSION['password']);
	session_unregister($_SESSION['authorised']);
	session_destroy();
	
	$pageHeading = 'Site Management';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Rail Geelong - <?php echo $pageHeading; ?></title>
<link rel="stylesheet" type="text/css" href="/common/style.css" />
</head>
<body>
<table id="container">
<tr><td id="header">
<h1><a href="/">RG</a> - <?php echo $pageHeading; ?></h1>
</td></tr>
<tr><td id="big" valign="top">
<div id="content">
<table>
<tr><td>
<form name="login" method="POST" action="index.php">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Username :  </b> </td>
		<td> <input type="text" name="username" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Password :  </b> </td>
		<td> <input type="password" name="password" size="20" value="">  </td> 
	</tr>
	<tr valign="top" height="20"><td align="right"><input type="submit" name="submitEnterUserForm" value="Login"></td><td></td></tr>
</table>
</form>
</td></tr>
</table>
<?php 

	include_once("common/footer.php"); 
}	// end function
?>