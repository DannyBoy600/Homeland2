<?php
session_start();
include "common.php";
include "db.php";

db_connect();

$arrPrio[] = array(0,"Alla");
$arrPrio[] = array(1,"1 (hög)");
$arrPrio[] = array(2,"2 (medel)");
$arrPrio[] = array(3,"3 (låg)");

$arrFinished[] = array(0,"Ja/Nej");
$arrFinished[] = array(1,"Ja");
$arrFinished[] = array(2,"Nej");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function newItem()
{  
  parent.location.href = "new_main.php";
}

function showList()
{
	var prio = 0;
	var sel = document.getElementById("sel_prio");
	if (sel != null) prio = sel.value;
	var fin = 0;
	sel = document.getElementById("sel_finished");
	if (sel != null) finished = sel.value;
	parent.center.location.href = "act_center.php?prio=" + prio + "&finished=" + finished;
}

function printList()
{     
	var props = "width=700,height=600,resizable,scrollbars=1,menubar=1,location=0,toolbar=1,status=0"; 
  var win = window.open("", "list_print", props);
  loadFrame(win,"list_print.php");
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
        <TR>";
echo com_printSelect("Prio:","","sel_prio","sel_prio",false,1,$arrPrio,0,"showList()",false,false) . "<TD>&nbsp;&nbsp;</TD>";
echo com_printSelect("Klar:","","sel_finished","sel_finished",false,1,$arrFinished,0,"showList()",false,false);
echo "
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM></BODY></HTML>";