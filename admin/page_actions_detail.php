<?php
include "head.php";
include "../db.php";
include "../common.php";

$actionID = com_getReqParamInt("actionID");

db_connect();

$actionInfo = null;
if ($actionID > 0)
{
  $sql = "
SELECT ActionID,No,Name,Finished,Prio,Descr,ReporterName,Actions,ReadyDate,Responsible,InsDate 
FROM PageAction
WHERE ActionID = {$actionID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_array($res))
    $actionInfo = $row;
}

$arrPrios[0] = array(1,1);
$arrPrios[1] = array(2,2);
$arrPrios[2] = array(3,3);

$arrItems = $_SESSION["act_list"];
$prevID = 0;
$nextID = 0;
for ($i=0;$i<count($arrItems);$i++)
{
  if ($arrItems[$i][0] == $actionID)
  {
    if ($i > 0) $prevID = $arrItems[$i-1][0];
    if (($i+1) < count($arrItems)) $nextID = $arrItems[$i+1][0];
  }
}
?>
<HTML>

<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
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

function pictureChanged()
{
	top.changed = true;
  highlightSave();
}

function doBack()
{
	var div_list = parent.document.getElementById("div_list");
  div_list.style.visibility = "visible";
  var div_detail = parent.document.getElementById("div_detail");
  div_detail.style.visibility = "hidden";
}

function doSave()
{
	document.forms[0].submit();
}

function doDelete()
{
	if (!confirm("Vill du verkligen radera åtgärden?")) return;
	document.forms[0].action = "page_actions_detail_delete.php";
	document.forms[0].submit();
	
}

function doPrev()
{
	var prevID = <?php echo $prevID?>;
	parent.showItem(prevID);
}

function doNext()
{
	var nextID = <?php echo $nextID?>;
	parent.showItem(nextID);
}

function onLoad()
{
  document.forms[0].txt_name.focus();
}
</SCRIPT>
</HEAD>

<?php

($prevID > 0) ? $prevDisabled = "" : $prevDisabled = "disabled";
($nextID > 0) ? $nextDisabled = "" : $nextDisabled = "disabled";

$tabAction = "
<TABLE border=0 align='left' cellpadding='0' cellspacing='0'>
<TR>
  <TD><input type='button' class='but' id='btn_cancel' value='&nbsp;Avbryt&nbsp;' onClick='javascript:doBack()'>&nbsp;</TD>
  <TD><input type='button' class='but' id='btn_save' value='&nbsp;Spara&nbsp;' onClick='javascript:doSave()'>&nbsp;</TD>";
if ($actionID > 0)
  $tabAction .= "
  <TD><input type='button' class='but' id='btn_delete' value='&nbsp;Radera&nbsp;' onClick='javascript:doDelete()'>&nbsp;</TD>
  <TD><input type='button' {$prevDisabled} class='but' id='btn_prev' value='&nbsp<=&nbsp;' onClick='javascript:doPrev()'>&nbsp;</TD>
  <TD><input type='button' {$nextDisabled} class='but' id='btn_next' value='&nbsp=>&nbsp;' onClick='javascript:doNext()'>&nbsp;</TD>";
$tabAction .= "
</TR>
</TABLE>";

$style = "";
if ($actionInfo["ReadyDate"] == "1900-01-01") $actionInfo["ReadyDate"] = "";
if ($actionInfo["ReadyDate"] != "")
{
  $today = date("Y-m-d");
  if ($actionInfo["ReadyDate"] < $today && $actionInfo["Finished"] == 0)
    $style = "color:red";
}

$header = $actionInfo["No"] . " " . $actionInfo["Name"];
$tab = "
<TABLE style='border: 1px solid darkred' cellpadding='0' cellspacing='5'>
<TR><TD class='header_small' style='{$style}' colspan='2' align='center'>{$header}</TD></TR>
<TR>" . com_printInputText("Namn:","","txt_name","txt_name",$actionInfo["Name"],80,100,false) . "</TR>
<TR>" . com_printTextArea("Beskrivning:","","txt_descr","txt_descr",$actionInfo["Descr"],8,80,false,false) . "</TR>
<TR>" . com_printSelect("Prio:","","sel_prio","sel_prio",false,1,$arrPrios,$actionInfo["Prio"],"pictureChanged()",true,false) . "
<TR>" . com_printInputText("Rapportör:","","txt_reporter","txt_reporter",$actionInfo["ReporterName"],80,100,false) . "</TR>
<TR>" . com_printTextArea("Åtgärder:","","txt_action","txt_action",$actionInfo["Actions"],8,80,false,false) . "</TR>
<TR>" . com_printInputText("Ansvarig:","","txt_responsible","txt_responsible",$actionInfo["Responsible"],80,100,false) . "</TR>
<TR>" . com_printInputText("Klar-datum:","","txt_ready_date","txt_ready_date",$actionInfo["ReadyDate"],10,10,false) . "</TR>
<TR>" . com_printYesNo("Klar:","","rdo_finished","rdo_finished",$actionInfo["Finished"],"pictureChanged()",false,false) . "</TR>
<TR><TD colspan='2' align='center' height='5'></TD></TR>
<TR><TD colspan='2' align='center'>{$tabAction}</TD></TR>
</TABLE>";

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_actions_detail_save.php' method='POST'>
 
<DIV id='div_main'>
<TABLE border='0' cellpadding='0' cellspacing='5'>
  <TR><TD valign='top'>{$tab}</TD></TR>
</TABLE>
</DIV>

<INPUT TYPE='hidden' id='hid_actionID' name='hid_actionID' value='{$actionID}'>
<INPUT TYPE='hidden' id='hid_no' name='hid_no' value='".$actionInfo["No"]."'>

</FORM></BODY></HTML>";
?>