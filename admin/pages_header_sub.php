<?php
include "head.php";
include "../db.php";
include "../common.php";

db_connect();

$menuID = com_getReqParamInt("menuID");
$pageID = com_getReqParamInt("pageID");
$kind = com_getReqParamStr("kind");
$_SESSION["sess_menuID"] = $menuID;

$cid = $_SESSION["sess_companyID"];
  
$pages = null;
$sql = "SELECT PageID,MenuHeader FROM Page WHERE MenuID = {$menuID} ORDER BY OrderNo";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
while ($row = mysql_fetch_row($res))
  $pages[] = $row;
?>
<HTML>

<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT src="jscripts.js"></SCRIPT>
<SCRIPT>
var menuID = "<?php echo $menuID?>";
var pageID = "<?php echo $pageID?>";
var kind = "<?php echo $kind?>";

function highlight(ind)
{
	var i = 0;
	var anc = document.getElementById("anc_header_sub_" + i); 
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
	  anc = document.getElementById("anc_header_sub_" + i); 
	}
}

function loadPage(ind)
{
	highlight(ind);
	var hid = document.getElementById("hid_pageID_" + ind);
	var pid = 0;
	if (hid != null)
	  pid = hid.value;
	parent.center.location.href = "page_main.php?pageID=" + pid + "&kind=" + kind;
}

function loadPageById(pid)
{
	var i = 0;
	var hid = document.getElementById("hid_pageID_" + i);
	while (hid != null)
	{
		if (hid.value == pid)
		{
			loadPage(i);
			return;
	  }
	  i++;
	  hid = document.getElementById("hid_pageID_" + i);
	}
}

function doOrder()
{
	parent.center.location.href = "page_order.php";
}

function onLoad()
{
	if (pageID > 0)
	  loadPageById(pageID);
	else
	  loadPage(0);
}

</SCRIPT>

</HEAD>

<?php

echo "
<body class='pages_header_sub' onLoad='javascript:onLoad()'><form>

<table border='0' cellpadding='0' cellspacing='0' valign='top'>
  <tr>
    <td class='header_menu' style='color:black;font-weight:bold'>Sida:" . str_repeat("&nbsp;",10) ."</td>";

echo com_printButton("btn_0","Ny sida","loadPage(-1)","but","font-size:10px;",1,"",false);

if (count($pages) > 1) 
  echo com_printButton("btn_1","Ordna...","doOrder()","but","font-size:10px;",1,"",false);
  
for ($i=0;$i<count($pages);$i++)
{
	$curID = strtoupper($pages[$i][0]);
	$curName = strtoupper($pages[$i][1]);
	$curLine = "&nbsp;&nbsp;" . str_replace(" ","&nbsp;",$curName) . "&nbsp;&nbsp;";
  echo "<td><a id='anc_header_sub_{$i}' class='sub_menu' hidefocus=true href='javascript:loadPage({$i})'>{$curLine}</a></td>
  <input type='hidden' id='hid_pageID_{$i}' value='{$curID}'>";
}

echo "
  </tr>
</table>

</form></body></html>";
?>