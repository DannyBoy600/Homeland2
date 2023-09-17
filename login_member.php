<?php 
session_start();
include "common.php";
include "db.php";

db_connect();

$cid = $_SESSION["sess_companyID"];

$name = "";
$sql = "SELECT Name FROM Company WHERE CompanyID = {$cid}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_row($res))
  $name = $row[0];
  
$msg = "";
if (isset( $_SESSION["sess_error_msg"]))
  $msg = $_SESSION["sess_error_msg"];
$_SESSION["sess_error_msg"] = ""
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT LANGUAGE=javascript>

function onLoad()
{
  document.login.name.focus();
}

</SCRIPT>
</HEAD>

<?php

$tabLogin = "
<table border='0' cellspacing='0' cellpadding='0'>
  <tr><td class='normalBold' align='center'>Medlemsinlogg {$name}<br><br></td></tr>
  <tr><td class='normal' align='center'>Användarnamn</td></tr>
  <tr><td class='normal' align='center'><input type='text' name='name' size='20' class='normal'></td></tr>
  <tr><td class='normal' align='center'>Lösenord</td></tr>
  <tr><td align='center'><input type='password' name='password' size='20' class='normal'><br><br></td></tr>
  <tr><td align='center'><input type='submit' class='normal' value='Logga in' id=submit1 name=submit1></td></tr>
  <tr><td class='normal' style='color:red' align='center'>&nbsp;{$msg}</td></tr>
</table>";

echo "
<BODY onLoad='javascript:onLoad()'>
<center>
<form action='login.php' method='post' name='login'>

<br><br><br>

<table style='border: 1px solid darkred' cellspacing='0' cellpadding='20'>
	<tr>
		<td>$tabLogin</td>
 </tr>
<br>

</center>
</form>
</BODY>
</HTML>";
?>