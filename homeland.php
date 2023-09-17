<?php
session_start();
include "db.php";
include "common.php";

$db = db_connect();

$companies = null;
$sql = "SELECT CompanyID,Name FROM Company ORDER BY Name";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$companies[] = $row;
}

?>
<HTML>
<HEAD>
<TITLE>Homeland</TITLE>
<LINK rel="stylesheet" href="styles.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="">
<meta name="description" content="">
<STYLE type="text/css">
body { 
	 background:url(./images/bg.gif);
   background-repeat:repeat-x;
	 background-position: left top;
}
</STYLE>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function selectSite(companyID)
{
	self.location.href = "index.php?cid=" + companyID;
}

function onLoad()
{
}
</SCRIPT>
</HEAD>

<?php

$tabImg = "
<table>
  <tr><td  valign='top'><img src='./images/hem.jpg'></td></tr>
</table>";

$tabContent = "
<table >
  <tr><td class='header' align='center'>Välkommen till Homeland!<br><br></td></tr>
  <tr><td class='normal' align='center'><a href='./admin/index.php' style='color:blue;text-decoration:none'>Administrera din sida.</a><br><br></td></tr>
  <tr><td class='normalBold' align='center'>Välj önskad sida nedan.<br></td></tr>";
for ($i=0;$i<count($companies);$i++)
  $tabContent .= "<tr><td class='normal' align='center'><a href='javascript:selectSite({$companies[$i][0]})' style='color:blue;text-decoration:none'>{$companies[$i][1]}</a></td></tr>";
$tabContent .= "
</table>";

echo "
<BODY onLoad='javascript:onLoad()'>
<center>
<form action='login.php' method='post' name='login'>

<br><br><br>

<table cellspacing=20 style='border: 1px solid darkgreen;background-color:white'>
	<tr>
		<td valign='top'>{$tabImg}</td>
		<td>{$tabContent}</td>
 </tr>
<br>

<input type='hidden' name='hid_screen_width' id='hid_screen_width' value='0'>

</center>
</form>
</BODY>
</HTML>";
?>
