<?php
include "head.php";
include "../db.php";
include "../common.php";

db_connect();

$cid = $_SESSION["sess_companyID"];

$items = null;
$sql = "SELECT MenuID, Name, IsVisible FROM Menu WHERE CompanyID = {$cid} ORDER BY OrderNo";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
while ($row = mysql_fetch_row($res))
  $items[] = $row;
  
?>
<HTML>

<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT src="jscripts.js"></SCRIPT>
<SCRIPT>

function highlight(ind)
{
	var i = 0;
	var anc = document.getElementById("anc_header_" + i); 
	while (anc != null)
	{
		if (i == ind)
		{
	    anc.style.color = "darkred";
	    anc.style.fontWeight = "bold";
	  }
	  else
	  {
	    anc.style.color = "";
	    anc.style.fontWeight = "normal";
	  }
	  i++;
	  anc = document.getElementById("anc_header_" + i); 
	}
}

function loadMenu(ind)
{
	highlight(ind);
	var menuID = document.getElementById("hid_menuID_" + ind).value;
	parent.header_sub.location.href = "pages_header_sub.php?menuID=" + menuID;
}

function onLoad()
{
	loadMenu(0);
}

</SCRIPT>

</HEAD>

<?php

$tabMenu = "
<table border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td class='header_menu' style='color:black;font-weight:bold'>Menyval:&nbsp;</td>";
for ($i=0;$i<count($items);$i++)
{
	$curItemID = $items[$i][0];
	$curItemName = strtoupper($items[$i][1]);
	$curLine = "&nbsp;&nbsp;" . str_replace(" ","&nbsp;",$curItemName) . "&nbsp;&nbsp;";
  $tabMenu .= "<td><a id='anc_header_{$i}' class='header_menu' hidefocus=true href='javascript:loadMenu({$i})'>{$curLine}</a></td>
  <input type='hidden' id='hid_menuID_{$i}' value='{$curItemID}'>";
}
$tabMenu .= "
  </tr>
</table>";

echo "
<body class='pages_header' onLoad='javascript:onLoad()'><form>

{$tabMenu}

</form></body></html>";
?>