<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];

$items = null;
$sql = "
SELECT DISTINCT MenuID, Name, OrderNo, IsVisible, IsProtected 
FROM Menu
WHERE CompanyID = {$cid} 
ORDER BY OrderNo";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$items[] = $row;
}

// add some extra lines
for ($i=0;$i<5;$i++)
  $items[] = array(0,"",0,0,0);

$used = null;
$sql = "SELECT DISTINCT MenuID FROM Page WHERE CompanyID = {$cid} ORDER BY MenuID";
$res = mysql_query ($sql);
while ($row = mysql_fetch_object($res)) 
	$used[$row->MenuID] = 1;

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
}
</SCRIPT>

</HEAD>
<?php
echo "
<BODY class='center' leftmargin=10 topmargin=5 onLoad='javascript:onLoad()'><FORM action='mainmenu_save.php' method='POST'>

<TABLE border='0' cellpadding='3' cellspacing='1'>
  <tr bgColor='darkgrey' >
    <td class='normalBold' style='color:white' title='Radera'>&nbsp;R</td>
    <td class='normalBold' style='color:white' title='Visa på hemsidan'>Visa</td>
    <td class='normalBold' style='color:white' title='Visa endast för inloggad'>Lösenords-skyddad</td>
    <td class='normalBold' style='color:white'>Namn</td>
    <td class='normalBold' style='color:white'>Sortering</td>
  </tr>";
  
for ($i=0;$i<count($items);$i++)
{
	$curMenuID = $items[$i][0];
  //$curName = htmlspecialchars($items[$i][1],ENT_QUOTES);
  $curName = htmlspecialchars($items[$i][1], ENT_COMPAT,'ISO-8859-1', true);
  $curOrderNo = $items[$i][2];
  $curIsVisible = $items[$i][3];
  ($curIsVisible == 1) ? $curChecked = "checked" : $curChecked = "";
  $curIsProtected = $items[$i][4];
  ($curIsProtected == 1) ? $curProtChecked = "checked" : $curProtChecked = "";
  $chkDel = "<input type='checkbox' name='chk_del_{$i}' onClick='javascript:pictureChanged()'>";
  if (isset($used[$curMenuID]) || $curMenuID ==0) $chkDel = "&nbsp;";
  $sortNo = $i+ 1;
  ($i % 2 == 0) ? $class='evenRow' : $class='oddRow';
  echo "
  <tr class='{$class}'>
    <td class='normal' align='center' valign='top'>{$chkDel}</td>
    <td class='normal' align='center' valign='top'><input type='checkbox' {$curChecked} name='chk_show_{$i}' onClick='javascript:pictureChanged()'></td>
    <td class='normal' align='center' valign='top'><input type='checkbox' {$curProtChecked} name='chk_protected_{$i}' onClick='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' id='txt_name_{$i}' name='txt_name_{$i}'  size='25' maxLength='255' value='{$curName}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' id='txt_order_{$i}' name='txt_order_{$i}'  size='2' maxLength='2' value='{$sortNo}' onKeyDown='javascript:pictureChanged()'></td>
    <input type='hidden' name='hid_id_{$i}' value='{$curMenuID}'>
  </tr>";
}

echo "
</TABLE>
  
</FORM></BODY></HTML>";
?>