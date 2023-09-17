<?php
include "head.php";
include "../db.php";
include "../common.php";

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];

$db = db_connect();

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
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function pictureChanged()
{
  top.changed = true;
  parent.navbar.highlightSave();
}

var selRowNo = -1;
function selectRow(rowNo)
{
  var tab = document.getElementById("tab_list");
  var tr = null;
  for (var i=0;i<tab.rows.length;i++)
  {
    tr = document.getElementById("tr_row_" + i);
    if (tr != null) tr.style.backgroundColor = "";
  }
  // mark row as selected
  tr = document.getElementById("tr_row_" + rowNo);
  if (tr != null) tr.style.backgroundColor = "yellow";

  selRowNo = rowNo;
}

function moveUp()
{
  if (selRowNo == -1) return;
    
  var doc = document;
  tab = doc.getElementById("tab_list");
	var tableRowNo = getCurTableRowNo();
	if (tableRowNo < 1) return;
	
	var tableRowNo2 = tableRowNo - 1; 
	var rowNo2 = tab.rows[tableRowNo2].id.substr(7); // row id = tr_row_##
	
	var tr = doc.getElementById("tr_row_" + selRowNo);
	var tr2 = doc.getElementById("tr_row_" + rowNo2);
	
	// swap (on td level)
	var contents = new Array(); 
  for(i=0;i<tr.childNodes.length;i++) { 
      contents[i] = tr.childNodes[i].innerHTML;
  } 
 
  var contents2 = new Array(); 
  for(i=0;i<tr2.childNodes.length;i++) { 
      contents2[i] = tr2.childNodes[i].innerHTML; 
  }
 
  for(i=0;i<tr.cells.length;i++) {
    tr.childNodes[i].innerHTML = contents2[i];
    tr2.childNodes[i].innerHTML = contents[i];
  }
	
	// swap row id's too
	tr2.setAttribute("id","tr_999999");
	tr.setAttribute("id","tr_row_" + rowNo2); 
	tr2.setAttribute("id","tr_row_" + selRowNo);
	
	selectRow(selRowNo);
}

function moveDown()
{
  if (selRowNo == -1) return;
    
	var doc = document;
  tab = doc.getElementById("tab_list");
  var tableRowNo = getCurTableRowNo();
  if (tableRowNo >= tab.rows.length-1) return;

  var tableRowNo2 = tableRowNo + 1; 
  var rowNo2 = tab.rows[tableRowNo2].id.substr(7); // row id = tr_row_##

  var tr = doc.getElementById("tr_row_" + selRowNo);
  var tr2 = doc.getElementById("tr_row_" + rowNo2);
  
  // swap (on td level)
  var contents = new Array(); 
  for(i=0;i<tr.childNodes.length;i++) { 
      contents[i] = tr.childNodes[i].innerHTML; 
  } 

  var contents2 = new Array(); 
  for(i=0;i<tr2.childNodes.length;i++) 
  { 
    contents2[i] = tr2.childNodes[i].innerHTML; 
  }

  for(i=0;i<tr.childNodes.length;i++) {
      tr.childNodes[i].innerHTML = contents2[i];
      tr2.childNodes[i].innerHTML = contents[i];
  }

  // swap row id's too
  tr2.setAttribute("id","tr_999999");
  tr.setAttribute("id","tr_row_" + rowNo2); 
  tr2.setAttribute("id","tr_row_" + selRowNo);
  
  selectRow(selRowNo);
}

function getCurTableRowNo()
{
  for (var i=0;i<tab.rows.length;i++)
  {
    var curRowNo = tab.rows[i].id.substr(7); // row id = tr_row_##
    if (curRowNo == selRowNo) return i;
  }
  return -1;
}

// handle key up or key down
function handleKeyPress(ev)
{
  var keyCode = 0; 
  if (window.event != null)
  {
    e = window.event;
    keyCode = e.keyCode;
  }
  else
  {
    keyCode = ev.which;
  }
  if (keyCode == 38 || keyCode == 40)
  {
    if (keyCode == 38) moveUp();
    if (keyCode == 40) moveDown();
  }
}

function doSave()
{
	var ids = "";
	for (var i=0;i<tab.rows.length;i++)
  {
  	var curRowNo = tab.rows[i].id.substr(7); // row id = tr_row_##
    var curID = document.getElementById("hid_id_" + curRowNo).value;
    if (ids != "") ids += ";";
    ids += curID;
  }
  document.getElementById("hid_ids").value = ids;
	document.forms[0].submit();
}

function onLoad()
{
	top.changed = false;
}
</SCRIPT>

</HEAD>
<?php

$tab = "
<table id='tab_list' border='1' cellpadding='3' cellspacing='0' valign='top' onKeyDown='javascript:handleKeyPress(event)'>";
for ($i=0;$i<count($pages);$i++)
{
	$curID = strtoupper($pages[$i][0]);
	$curName = strtoupper($pages[$i][1]);
	$curLine = "&nbsp;&nbsp;" . str_replace(" ","&nbsp;",$curName) . "&nbsp;&nbsp;";
  $tab .= "<tr id='tr_row_{$i}'><td class='normal'><a id='anc_row_{$i}' class='normal' style='color:black;text-decoration:none' hidefocus=true href='javascript:selectRow({$i})'>{$curLine}</a><input type='hidden' id='hid_id_{$i}' value='{$curID}'></td></tr>";
}
$tab .= "
  </tr>
</table>";

$tabAction = "
<table border='0' cellpadding='0' cellspacing='0' valign='top'>
  <tr>".com_printButton("btn_save","Spara","doSave()","but","font-size:10px;",1,"",false)."</tr>
</table>";

$tabInfo = "
<table border='0' cellpadding='0' cellspacing='0' valign='top'>
  <tr><td class='big' style='font-style:italic'>Sidornas ordning kan ändras genom att:<br>* klicka på önskad rad,<br>* klicka på piltangent upp/ned,<br>* klicka Spara!</td></tr>
</table>";

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_order_save.php' method='POST'>

<table border='0' cellpadding='0' cellspacing='0' valign='top'>
  <tr><td>{$tab}</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>{$tabAction}</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>{$tabInfo}</td></tr>
</table>

<input type='hidden' id='hid_ids' name='hid_ids' value=''>

</FORM></BODY></HTML>";
?>