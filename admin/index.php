<?php 
session_start();

$cid = 0;
if (isset($_REQUEST["cid"])) 
{
  $_SESSION["sess_companyID"] = (int)$_REQUEST["cid"];
  $cid = $_SESSION["sess_companyID"];
}

$msg = "";
if (isset( $_SESSION["sess_error_msg"]))
  $msg = $_SESSION["sess_error_msg"];
$_SESSION["sess_error_msg"] = ""

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<LINK rel="stylesheet" href="login.css">
<TITLE>Homeland Admin</TITLE>
<STYLE type="text/css">
body { 
	 background:url(../images/bg.gif);
   background-repeat:repeat-x;
	 background-position: left top;
}
</STYLE>
<SCRIPT LANGUAGE=javascript>

function onLoad()
{
  document.login.name.focus();
  document.getElementById("hid_screen_width").value = screen.width;
}

</SCRIPT>
</HEAD>

<?php

$tabImg = "<img src='admin.png'>";

$tabLogin = "
<table >
  <tr><td class='head' align='center'>Administrera föreningens hemsida<br><br></td></tr>
  <tr><td class='login' align='center'>Användarnamn</td></tr>
  <tr><td class='head' align='center'><input type='text' name='name' size='20' class='normal'></td></tr>
  <tr><td class='login' align='center'>Lösenord</td></tr>
  <tr><td align='center'><input type='password' name='password' size='20' class='normal'><br><br></td></tr>
  <tr><td align='center'><input type='submit' class='login' value='Logga in' id=submit1 name=submit1></td></tr>
  <tr><td class='error' align='center'>&nbsp;{$msg}</td></tr>";
if ($cid > 0)
  $tabLogin .= "
  <tr><td class='login' align='center'><a href='../index.php?cid={$cid}' style='color:darkgreen;text-decoration:none'>Till föreningens hemsida</a></td></tr>";
$tabLogin .= "
</table>";

echo "
<BODY onLoad='javascript:onLoad()'>
<center>
<form action='login.php' method='post' name='login'>

<br><br><br>

<table style='border: 1px solid darkgreen;background-color:white'>
	<tr>
		<td>{$tabImg}</td>
		<td>$tabLogin</td>
 </tr>
<br>

<input type='hidden' name='hid_screen_width' id='hid_screen_width' value='0'>

</center>
</form>
</BODY>
</HTML>";
?>