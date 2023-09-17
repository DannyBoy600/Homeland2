<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];

$users = null;
$sql = "SELECT UserID,FirstName,LastName,UserName,PassWord FROM User WHERE CompanyID = {$cid} ORDER BY FirstName";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res))
  $users[] = $row;

for ($i=0;$i<5;$i++)
  $users[] = array(0,"","","","");

?>
<HTML>
<HEAD>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function rowChanged(rowNo)
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
  <tr bgColor='darkgrey' >
    <td class='normalBold' style='color:white'title='Radera'>&nbsp;&nbsp;R</td>
    <td class='normalBold' style='color:white'>Förnamn</td>
    <td class='normalBold' style='color:white'>Efternamn</td>
    <td class='normalBold' style='color:white'>Användarnamn</td>
    <td class='normalBold' style='color:white'>Lösenord</td>
  </tr>";
  
for ($i=0;$i<count($users);$i++)
{
	$curUserID = $users[$i][0];
	//$curFirstName = htmlspecialchars($users[$i][1],ENT_QUOTES);
	$curFirstName = htmlspecialchars($users[$i][1], ENT_COMPAT,'ISO-8859-1', true);
	//$curLastName = htmlspecialchars($users[$i][2],ENT_QUOTES);
	$curLastName = htmlspecialchars($users[$i][2], ENT_COMPAT,'ISO-8859-1', true);
	$curUserName = $users[$i][3];
	$curPassword = $users[$i][4];
	
	$chk = "<INPUT TYPE='checkbox' id='chk_del_{$i}' name='chk_del_{$i}' onClick='javascript:rowChanged({$i})'>";
	if ($curUserID == 0) $chk = "";
	
  $tab .= "
<TR>
  <TD>{$chk}</TD>
  <TD><INPUT TYPE='text' class='normal' id='txt_first_name_{$i}' name='txt_first_name_{$i}' size=40 maxLength=100 value='{$curFirstName}' onKeyDown='javascript:rowChanged({$i})'></TD>
  <TD><INPUT TYPE='text' class='normal' id='txt_last_name_{$i}' name='txt_last_name_{$i}' size=40 maxLength=100 value='{$curLastName}' onKeyDown='javascript:rowChanged({$i})'></TD>
  <TD><INPUT TYPE='text' class='normal' id='txt_user_name_{$i}' name='txt_user_name_{$i}' size=20 maxLength=20 value='{$curUserName}' onKeyDown='javascript:rowChanged({$i})'></TD>
  <TD><INPUT TYPE='password' class='normal' id='txt_password_{$i}' name='txt_password_{$i}' size=20 maxLength=20 value='{$curPassword}' onKeyDown='javascript:rowChanged({$i})'></TD>
  <INPUT TYPE='hidden' id='hid_userID_{$i}' name='hid_userID_{$i}' value='{$curUserID}'>
</TR>";
}

echo "
<BODY class='center' onLoad='javascript:onLoad()'>
<FORM action='users_save.php' enctype='multipart/form-data' method='POST'>

<TABLE border='0' cellpadding='0' cellspacing='2'>
  <TR><TD valign='top'>{$tab}</TD></TR>
</TABLE>

</FORM></BODY></HTML>";
?>