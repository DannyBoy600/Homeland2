<?php
session_start();
include "common.php";
include "db.php";

db_connect();

$cid = $_SESSION["sess_companyID"];
$pageID = $_SESSION["sess_pageID"];

// get years
$now = date("Y");
$prev = $now - 1;
$arrYears[] = array($prev,$prev);
$arrYears[] = array($now,$now);

$next = $now;
for ($i=0;$i<3;$i++)
{
	$next++;
  $arrYears[] = array($next,$next);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

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

function enableSave(yearNo)
{
	var now = <?php echo $now?>;
	var btn = document.getElementById("btn_save");
	if (yearNo < now)
	  btn.disabled = true;
	else
		 btn.disabled = false;	
}

function showList()
{
	var yearNo = document.getElementById("sel_year").value;
	enableSave(yearNo);
	parent.center.location.href = "book_center.php?yearNo=" + yearNo;
}

function doSave()
{
	var doc = parent.center.document;

	top.changed = false;
	resetSave();

	doc.forms[0].submit();
}

function onLoad()
{
	showList();
}
</SCRIPT>
</HEAD>

<?php
echo "
<BODY class='navbar' onLoad='javascript:onLoad()'><FORM>

<TABLE border='0' cellpadding='0' cellspacing='0' valign='center' height='100%' width='100%'>
  <TR>
    <TD>
      <TABLE border='0' cellpadding='0' cellspacing='0' valign='center'>
        <TR>
          <TD>&nbsp;<input type='button' class='but' id='btn_save' value='&nbsp;Spara&nbsp;' onClick='javascript:doSave()'>&nbsp;</TD>" .
          com_printSelect("&nbsp;&nbsp;Välj år:","","sel_year","sel_year",false,1,$arrYears,$now,"showList()",false,false) . "
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM></BODY></HTML>";