<?php
session_start();
include "common.php";
include "db.php";

$menuID = $_SESSION["sess_homepage_menuID"];

db_connect();

$pages = null;
if (isset($_SESSION["sess_homeland_logged_in"]))
  $sql = "SELECT PageID,PageDate,MenuHeader FROM Page WHERE MenuID = {$menuID} ORDER BY OrderNo";
else
  $sql = "SELECT PageID,PageDate,MenuHeader FROM Page WHERE MenuID = {$menuID} AND IsProtected = 0 ORDER BY OrderNo";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
while ($row = mysql_fetch_row($res))
  $pages[] = $row;
  
$loadPageID = 0;
if (count($pages) > 0) $loadPageID = $pages[0][0];

$anc = "";
if ($menuID == $_SESSION["sess_first_menuID"])
{
	if (isset($_SESSION["sess_homeland_logged_in"]))
  	$anc = "<a class='submenu_item' href='javascript:doLogout()' style='color:#404040;text-decoration:none'>LOGGA UT</a>";
	else
  	$anc = "<a class='submenu_item' href='javascript:doLogin()' style='color:#404040;text-decoration:none'>LOGGA IN</a>";
}  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<STYLE>
a:hover {color:darkgreen}  /* mouse over link */
</STYLE>
<SCRIPT>
function highlight(ind)
{
	var i = 0;
	var anc = document.getElementById("anc_" + i); 
	while (anc != null)
	{
		if (i == ind)
		{
	    anc.className = "submenu_item_sel";
	  }
	  else
	  {
	    anc.className = "submenu_item";
	  }
	  i++;
	  anc = document.getElementById("anc_" + i);	
	}
}

function loadPage(pageID,ind)
{
	highlight(ind);
  parent.page.location.href = "page.php?pageID=" + pageID;
}

function doLogin()
{
	parent.parent.center.location.href = "login_member.php";
}

function doLogout()
{
	parent.parent.center.location.href = "logout.php";
}

function onLoad()
{
  var loadPageID = <?php echo $loadPageID?>;
  loadPage(loadPageID,0);
}
</SCRIPT>
</HEAD>

<?php

function printSubMenuItem($ind)
{
	global $pages;
	
	$pi = $pages[$ind];
	$pageID = $pi[0];
	//$header = strtoupper(htmlspecialchars($pi[2],ENT_QUOTES));
	$header = strtoupper(htmlspecialchars($pi[2], ENT_COMPAT,'ISO-8859-1', true));
	$header = str_replace(" ","&nbsp;",$header);
	if (count($pages) == 1) $header = "";
	$date = substr($pi[1],0,10);
	
  $s = "<td><a class='submenu_item' id='anc_{$ind}' href='javascript:loadPage({$pageID},{$ind})'>{$header}&nbsp;</a>&nbsp;</td>";

  return $s;
}
                                	
echo "
<body class='submenu' onLoad='javascript:onLoad()'>

<table border='0' cellpadding='0' cellspacing='0' style='position:absolute;top:4px'>
  <tr>";

for ($i=0;$i<count($pages);$i++)
  echo "<td>" . printSubMenuItem($i) . "</td>";

echo "<td>" . $anc . "</td>";
  
echo "
  </tr>
</table>

</body></html>";
?>