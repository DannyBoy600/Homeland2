<?php
include "head.php";
include "../db.php";
include "../common.php";

$cid = $_SESSION["sess_companyID"];
$pageID = $_SESSION["sess_pageID"];

$db = db_connect();

$contactPageID = 0;
$sql = "SELECT Header,ContactPageID FROM PageBook WHERE PageID = {$pageID}";
$res = mysql_query ($sql);
if ($row = mysql_fetch_row($res))
	$contactPageID = $row[1];

$arrContacts = null;
if ($contactPageID > 0)
{
	$sql = "SELECT ContactID,Name FROM PageContact WHERE PageID = {$contactPageID} ORDER BY Name";
  $res = mysql_query ($sql);
  while ($row = mysql_fetch_row($res)) 
	  $arrContacts[] = $row;
}
  
?>
<HTML>
<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function selectContact()
{
	var contactID = document.getElementById("sel_contact").value;
  var ifr = document.getElementById("ifr_book").contentWindow;
  ifr.location.href = "page_bookings_book2.php?contactID=" + contactID;
}

function onLoad()
{
}
</SCRIPT>

</HEAD>
<?php

$tab = "
<TABLE border='0' cellpadding='3' cellspacing='1'>
<TR>" .
  com_printSelect("Boka för:","","sel_contact","sel_contact",false,1,$arrContacts,0,"selectContact()",true,false) . "
</TR>
</TABLE>";

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_bookings_save.php' method='POST'>

<TABLE border='0' cellpadding='0' cellspacing='2' width='100%'>
  <TR><TD valign='top' style='background-Color:#ECF0C2'>{$tab}</TD></TR>
  <TR><TD valign='top' height='5'></TD></TR>
  <tr><td align='center' valign='top'><IFRAME src='empty.php' name='ifr_book' id='ifr_book' frameBorder=0 width='100%' height='740'></IFRAME></td></tr>
</TABLE>

</FORM></BODY></HTML>";
?>