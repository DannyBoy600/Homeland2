<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];

$name = "";
$footer = "";
$sql = "SELECT Name,Footer FROM Company WHERE CompanyID = {$cid}";
$res = mysql_query ($sql);
if ($row = mysql_fetch_row($res))
{
  $name = $row[0];
  $footer = $row[1];
}

?>
<HTML>
<HEAD>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function pictureChanged()
{
  top.changed = true;
  parent.navbar.highlightSave();	
}

function onLoad()
{
	top.changed = false;
}
</SCRIPT>

<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
</HEAD>
<?php

$div = "
<div style='position:absolute;top:50px;left:10px;visibility:hidden' id='div_person'>
  <img id='img_person' src=''>
</div>";

$tab = "
<TABLE border='0' cellpadding='3' cellspacing='1'>
<TR>
  <TD class='normalBold'>Föreningens namn:&nbsp;</TD>
  <TD><INPUT TYPE='text' class='normal' id='txt_name' name='txt_name' size=80 maxLength=100 value='{$name}' onKeyDown='javascript:pictureChanged()'></TD>
</TR>
<TR>
  <TD class='normalBold'>Fot-text:&nbsp;</TD>
  <TD><INPUT TYPE='text' class='normal' id='txt_footer' name='txt_footer' size=150 maxLength=200 value='{$footer}' onKeyDown='javascript:pictureChanged()'></TD>
</TR>
</TABLE>";

echo "
<BODY class='center' onLoad='javascript:onLoad()'>
<FORM action='company_save.php' method='POST'>

<TABLE border='0' cellpadding='0' cellspacing='2'>
  <TR><TD valign='top'>{$tab}</TD></TR>
</TABLE>

</FORM></BODY></HTML>";
?>