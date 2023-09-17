<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = $_SESSION["sess_pageID"];

$db = db_connect();

$menuHeader = "";
$subject = "";
$orderNo = "";
$isVisible = 0;
$isProtected = 0;
$allowComments = 0;
$secureComments = 0;
$insDate = "";
$insBy = "";
$updDate = "";
$updBy = "";
if ($pageID > 0)
{
  $sql = "SELECT MenuHeader, Subject, OrderNo, IsVisible, IsProtected, AllowComments, SecureComments, InsDate, InsBy, UpdDate, UpdBy FROM Page WHERE PageID = {$pageID}";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_row($res)) 
  {
  	$menuHeader = $row[0];
    $subject = $row[1];
    $orderNo = $row[2];
    $isVisible = $row[3];
    $isProtected = $row[4];
    $allowComments = $row[5];
    $secureComments = $row[6];
    $insDate = $row[7];
    $insBy = $row[8];
    $updDate = $row[9];
    $updBy = $row[10];
  }
}
?>
<HTML>
<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
	document.getElementById("txt_menu_header").focus();
}
</SCRIPT>

</HEAD>
<?php

($isVisible == 1) ? $checked = "checked" : $checked = "";
($isProtected == 1) ? $checked2 = "checked" : $checked2 = "";
($allowComments == 1) ? $checked3 = "checked" : $checked3 = "";

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_common_save.php' method='POST'>

<TABLE border='0' cellpadding='0' cellspacing='2'>
  <TR>
    <TD class='normalBold'>Namn:&nbsp;</TD>
    <TD class='normal' valign='top'><input type='text' class='normal' id='txt_menu_header' name='txt_menu_header' size='30' maxLength='30' value='{$menuHeader}' onKeyDown='javascript:pictureChanged()'></TD>
  </TR>
  <TR>
    <TD class='normalBold'>Rubrik:&nbsp;</TD>
    <TD class='normal' valign='top'><input type='text' class='normal' id='txt_subject' name='txt_subject' size='60' maxLength='255' value='{$subject}' onKeyDown='javascript:pictureChanged()'></TD>
  </TR>
  <TR>
    <TD class='normalBold'>Visa:&nbsp;</TD>
    <TD class='normal' valign='top'><input type='checkbox' {$checked} name='chk_show' onClick='javascript:pictureChanged()'></TD>
  </TR>
  <TR>
    <TD class='normalBold'>Lösenords-skyddad:&nbsp;</TD>
    <TD class='normal' valign='top'><input type='checkbox' {$checked2} name='chk_protected' onClick='javascript:pictureChanged()'></TD>
  </TR>
  <TR>
    <TD class='normalBold'>Får kommenteras:&nbsp;</TD>
    <TD class='normal' valign='top'><input type='checkbox' {$checked3} name='chk_allow_comments' onClick='javascript:pictureChanged()'></TD>
  </TR>
</TABLE>

</FORM></BODY></HTML>";
?>