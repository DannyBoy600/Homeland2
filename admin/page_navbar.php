<?php
include "head.php";
include "../db.php";
include "../common.php";

$kind = com_getReqParamStr("kind");
$_SESSION["sess_kind"] = $kind; // used for re-generating via page_left.php

$db = db_connect();

$pageID = $_SESSION["sess_pageID"];

$menuHeader = "";
$date = "";
$subject = "";
$author = "";
if ($pageID > 0)
{
  $sql = "SELECT MenuHeader,PageDate,Subject,InsBy FROM Page WHERE PageID = {$pageID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_row($res))
  {
  	$menuHeader = $row[0];
  	$date = com_getMyDate($row[1]);
  	$subject = strtoupper($row[2]);
  	$author = $row[3];
  }
}

?>
<HTML>

<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT src="jscripts.js"></SCRIPT>
<SCRIPT>
var kind = "<?php echo $kind?>";

var saveIsRed = false;
function highlightSave()
{
  if (saveIsRed) return;
  var btn = document.getElementById("btn_save");
  if (btn.disabled) return;
  if (btn.style.color == "")
  {
    btn.style.color = "red";
    saveIsRed = true;
  }
}

function resetSave()
{
  if (saveIsRed)
  {
    saveIsRed = false;
    var btn = document.getElementById("btn_save");  	
    btn.style.color = "";
  }
}

function resetDel()
{
  var btn = document.getElementById("btn_del");  	
  btn.style.color = "";
}

function highlightDelete()
{
  var btn = document.getElementById("btn_del");
  if (btn.disabled) return;
  if (btn.style.color == "")
    btn.style.color = "red";
}

function doDeletePage()
{
	if (!confirm("Vill du verkligen radera sidan?")) return;
	parent.center.location.href = "page_delete.php";
}

function doDeleteText()
{
	if (!confirm("Vill du verkligen radera texten?")) return;
	parent.center.location.href = "page_text_delete.php";
}
  	
function doSaveCommon()
{
	var doc = parent.center.document;
  
  if (trim(doc.getElementById("txt_menu_header").value) == "")
  {
  	alert("Namn saknas!");
  	return;
  }
  
  if (trim(doc.getElementById("txt_subject").value) == "")
  {
  	alert("Rubrik saknas!");
  	return;
  }
  
	top.changed = false;

	doc.forms[0].submit();
}

function doSaveText()
{
	var doc = parent.center.document;
	
  var hid = doc.getElementById("hid_body");
  var txt = parent.center.oEdit1.getHTMLBody();
  hid.value = txt;
  
	top.changed = false;

	doc.forms[0].submit();
}

function doSaveDocs()
{
	var doc = parent.center.document;

  resetSave();
	top.changed = false;

	doc.forms[0].submit();
}

function doSaveLinks()
{
	var doc = parent.center.document;

	// validate
	var i = 0;
	var hid = doc.getElementById("hid_id_" + i);
	while (hid != null)
	{
		var chk = doc.getElementById("chk_del_" + i); 
		if (chk != null)
		  if (chk.checked && hid.value > 0)
		    if (!confirm("Vill du verkligen radera länken?")) 
		      return;
		i++;
		hid = doc.getElementById("hid_id_" + i);
  }
  
  resetSave();
	top.changed = false;

	doc.forms[0].submit();
}

function doSaveContacts()
{
	var doc = parent.center.document;

	// validate
	var i = 0;
	var hid = doc.getElementById("hid_id_" + i);
	while (hid != null)
	{
		var chk = doc.getElementById("chk_del_" + i); 
		if (chk != null)
		  if (chk.checked && hid.value > 0)
		    if (!confirm("Vill du verkligen radera personen?")) 
		      return;
		var pn = doc.getElementById("txt_name_" + i).value;
		var un = doc.getElementById("txt_un_" + i).value;
		var pw = doc.getElementById("txt_pw_" + i).value;
		if (pn != "")
		{
			if (un != "" && pw == "") {alert(pn + ", lösenord saknas"); return;}
			if (un != "" && pw.length < 6) {alert(pn + ", lösenordet måste ha minst 6 tecken!"); return;}
	  }
		i++;
		hid = doc.getElementById("hid_id_" + i);
  }
  
  resetSave();
	top.changed = false;

	doc.forms[0].submit();
}

function doSaveBookings()
{
	var doc = parent.center.document;

  // must enable contact list, otherwise it will not be submitted!
  var sel = doc.getElementById("sel_contact_pages");
  sel.disabled = false;
  
  resetSave();
	top.changed = false;

	doc.forms[0].submit();
}

function doBook()
{
	var props = "width=700,height=800,resizable,scrollbars=1,menubar=1,location=0,toolbar=1,status=0"; 
  var win = window.open("", "do_book", props);
  loadFrame(win,"page_bookings_book.php");
}

function doDeleteComments()
{
	if (!confirm("Vill du verkligen radera kommentarerna?")) return;
	resetDel();
	parent.center.document.forms[0].action = "page_comments_delete.php";
	parent.center.document.forms[0].submit();
}

function doSave()
{
  switch (kind)
  {
  	case "common": doSaveCommon(); break;
  	case "text": doSaveText(); break;
  	case "docs": doSaveDocs(); break;
  	case "links": doSaveLinks(); break;
  	case "contacts": doSaveContacts(); break;
  	case "bookings": doSaveBookings(); break;
  }
}

function doDelete()
{
	switch (kind)
  {
  	case "common": doDeletePage(); break;
  	case "text": doDeleteText(); break;
  	case "comments": doDeleteComments(); break;
  }
}

function doNewAction()
{
	parent.center.center.showItem(0);
}

function onLoad()
{
	var loc = "page_" + kind + ".php"; 
	parent.center.location.href = loc;
}

</SCRIPT>

</HEAD>

<?php

$tabAction = "
<DIV style='position:absolute;top:5px;left:0px'>
<TABLE border=0 align='left' cellpadding='0' cellspacing='0'>
<TR>
  <TD>&nbsp;<input type='button' class='but' id='btn_save' value='&nbsp;Spara&nbsp;' onClick='javascript:doSave()'>&nbsp;</TD>";
if ($kind == "common" || $kind == "text")
  $tabAction .= "<TD><input type='button' class='but' id='btn_del' value='&nbsp;Radera&nbsp;' onClick='javascript:doDelete()'>&nbsp;</TD>";
if ($kind == "bookings")
  $tabAction .= "<TD><input type='button' class='but' id='btn_book' value='&nbsp;Boka...&nbsp;' onClick='javascript:doBook()'>&nbsp;</TD>";
$tabAction .= "
  <TD>" . str_repeat("&nbsp;",40) . "</TD>
  <TD class='header_small'>{$menuHeader}</TD>
</TR>
</TABLE>
</DIV>";

if ($kind == "actions")
  $tabAction = "
<DIV style='position:absolute;top:5px;left:0px'>
<TABLE border=0 align='left' cellpadding='0' cellspacing='0'>
<TR>
  <TD>&nbsp;<input type='button' class='but' id='btn_new' value='&nbsp;Ny&nbsp;' onClick='javascript:doNewAction()'>&nbsp;</TD>
  <TD>" . str_repeat("&nbsp;",40) . "</TD>
  <TD class='header_small'>{$menuHeader}</TD>
</TR>
</TABLE>
</DIV>";

if ($kind == "comments")
  $tabAction = "
<DIV style='position:absolute;top:5px;left:0px'>
<TABLE border=0 align='left' cellpadding='0' cellspacing='0'>
<TR>
  <TD>&nbsp<input type='button' class='but' id='btn_del' value='&nbsp;Radera&nbsp;' onClick='javascript:doDelete()'>&nbsp;</TD>
  <TD>" . str_repeat("&nbsp;",40) . "</TD>
  <TD class='header_small'>{$menuHeader}</TD>
</TR>
</TABLE>
</DIV>";

echo "
<body class='navbar' onLoad='javascript:onLoad()'><form>

{$tabAction}

</form></body></html>";
?>