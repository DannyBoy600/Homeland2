<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = $_SESSION["sess_pageID"];

$db = db_connect();

$links = null;
$sql = "SELECT DISTINCT LinkID, Name, URL, Descr, IsVisible FROM PageLink WHERE PageID = {$pageID} ORDER BY Name";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$links[] = $row;
}
  
// add some extra lines
for ($i=0;$i<5;$i++)
  $links[] = array(0,"","","",0);
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

function testLink(rowNo)
{  
	var theUrl = trim(document.getElementById("txt_url_" + rowNo).value);
  if ( theUrl == "") { alert("Tom länk!"); return; }
  if (theUrl.indexOf("http") == -1) theUrl = "http://" + theUrl;
  var props = "width=800,height=600,resizable,scrollbars=1,menubar=0,location=0,toolbar=0,status=1"; 
  var win = window.open(theUrl, "link_" + rowNo, props);
}

function onLoad()
{
	top.changed = false;
}
</SCRIPT>

</HEAD>
<?php

// LinkID, Name, URL, Descr, IsVisible 
function printLinks()
{
  global $links;
  
	$tab = "
<TABLE border='0' cellpadding='3' cellspacing='1'>
  <tr bgColor='darkgrey' >
    <td class='normalBold' style='color:white' title='Testa'>&nbsp;&nbsp;T</td>
    <td class='normalBold' style='color:white' title='Radera'>&nbsp;&nbsp;R</td>
    <td class='normalBold' style='color:white'>Visa</td>
    <td class='normalBold' style='color:white'>Namn</td>
    <td class='normalBold' style='color:white'>Webadress</td>
    <td class='normalBold' style='color:white'>Beskrivning</td>
  </tr>";
  
  for ($i=0;$i<count($links);$i++)
  {
  	$curLinkID = $links[$i][0];
    //$curName = htmlspecialchars($links[$i][1],ENT_QUOTES);
    $curName = htmlspecialchars($links[$i][1], ENT_COMPAT,'ISO-8859-1', true);
    $curURL = $links[$i][2];
    //$curDescr = htmlspecialchars($links[$i][3],ENT_QUOTES);
    $curDescr = htmlspecialchars($links[$i][3], ENT_COMPAT,'ISO-8859-1', true);
    $curShow = $links[$i][4];
    $chkDel = "";
    if ($curLinkID > 0) $chkDel = "<INPUT TYPE='checkbox' id='chk_del_{$i}' name='chk_del_{$i}' onClick='javascript:pictureChanged()'>";
    ($curShow == 1) ? $curChecked = "checked" : $curChecked = "";
    ($i % 2 == 0) ? $class='evenRow' : $class='oddRow';
    $tab .= "
  <tr class='{$class}'>
    <td><A href='javascript:testLink({$i})' id='anc_eye_{$i}' title='Testa länken'><img border='0' src='../images/eye.gif'></A></td>
    <TD>{$chkDel}</TD>
    <td class='normal' align='center' valign='top'><input type='checkbox' {$curChecked} name='chk_show_{$i}' onClick='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_name_{$i}' name='txt_name_{$i}'  size='25' maxLength='255' value='{$curName}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_url_{$i}' name='txt_url_{$i}' size='40' maxLength='255' value='{$curURL}'  onKeyDown='javascript:pictureChanged()'</td>
    <td class='normal' valign='top'><textarea class='normal' name='txt_descr_{$i}' rows='6' cols='80' onKeyDown='javascript:pictureChanged()'>{$curDescr}</textarea></td>
    <input type='hidden' id='hid_id_{$i}' name='hid_id_{$i}' value='{$curLinkID}'>
  </tr>";
  }
  $tab .= "
</TABLE>";
  return $tab;
}

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_links_save.php' method='POST'>

<table border='0' cellpadding='0' cellspacing='0'>
  <TR><TD>" . printLinks() ."</TD></TR>
</table>

</FORM></BODY></HTML>";
?>