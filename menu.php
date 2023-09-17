<?php
session_start();
include "common.php";
include "db.php";

db_connect();

$cid = $_SESSION["sess_companyID"];

$items = null;
if (isset($_SESSION["sess_homeland_logged_in"]))
  $sql = "SELECT MenuID, Name FROM Menu WHERE CompanyID = {$cid} AND IsVisible = 1 ORDER BY OrderNo";
else
  $sql = "SELECT MenuID, Name FROM Menu WHERE CompanyID = {$cid} AND IsVisible = 1 AND IsProtected = 0 ORDER BY OrderNo";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
while ($row = mysql_fetch_row($res))
  $items[] = $row;

$_SESSION["sess_first_menuID"] = -1;
if (count($items) > 0)
{
	$_SESSION["sess_first_menuID"] = $items[0][0];
}

$name = "";
$sql = "SELECT Name FROM Company WHERE CompanyID = {$cid}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_row($res))
  $name = $row[0];
  
$namePos = 400 - (strlen($name)/2*9);

$loggedinName = "";
$loggedinNamePos = 800;
if (isset($_SESSION["sess_homeland_logged_in"]))
{
	$loggedinName = $_SESSION["sess_full_name"];
	$loggedinNamePos = 800 - (strlen($loggedinName)*8);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT src="jscripts.js"></SCRIPT>
<SCRIPT>

var selInd = 0;
function highlight(ind)
{
	var i = 0;
	var td = document.getElementById("td_" + i); 
	var anc = document.getElementById("anc_" + i); 
	while (anc != null)
	{
		if (i == ind)
		{
	    anc.className = "header_menu_sel";
	  }
	  else
	  {
	    anc.className = "header_menu";
	  }
	  i++;
	  td = document.getElementById("td_" + i); 
	  anc = document.getElementById("anc_" + i);	
	}
	selInd = ind;
}

function mouseOverMenu(ind)
{
	if (ind == selInd) return;
	var td = document.getElementById("td_" + ind);
	var anc = document.getElementById("anc_" + ind); 
	anc.className = "header_menu_sel";
}

function mouseOutMenu(ind)
{
	if (ind == selInd) return;
	var td = document.getElementById("td_" + ind); 
	var anc = document.getElementById("anc_" + ind); 
	anc.className = "header_menu";
}

function loadMenu(ind,menuID)
{
	highlight(ind);
  if (menuID > 0)
  {
	  parent.center.location.href = "center.php?menuID=" + menuID;
  }
}

function onLoad()
{
	var firstMenuID = <?php echo $items[0][0]?>;
	loadMenu(0,firstMenuID);
}

</SCRIPT>
</HEAD>

<?php

echo "
<body class='menu' style='background-color:white' onLoad='javascript:onLoad()'>

<div style='position:absolute;visibility:visible;left:{$namePos}px;top:5px'>{$name}</div>

<div style='font-size:12px;color:black;font-weight:normal;position:absolute;visibility:visible;left:{$loggedinNamePos}px;top:5px'>{$loggedinName}</div>
 
<div style='position:absolute;visibility:visible;left:2px;top:42px'>
  <img id='img_menu' src='./images/menu.jpg'>
</div>

<div style='position:absolute;visibility:visible;left:10px;top:40px'>
<table border='0' cellpadding='0' cellspacing='0' height='28'>
  <tr>
    <td>&nbsp;&nbsp;</td>";
for ($i=0;$i<count($items);$i++)
{
	$curItemID = $items[$i][0];
	$curItemName = strtoupper($items[$i][1]);
	$curLine = "&nbsp;&nbsp;" . str_replace(" ","&nbsp;",$curItemName) . "&nbsp;&nbsp;";
  echo "<td id='td_{$i}' onMouseOver='javascript:mouseOverMenu({$i})' onMouseOut='javascript:mouseOutMenu({$i})'><a id='anc_{$i}' class='header_menu' hidefocus=true href='javascript:loadMenu({$i},{$curItemID})'>{$curLine}</a></td>";
}
echo "
  </tr>
</table>
</div>

</body></html>";
?>