<?php
session_start();
include "common.php";
include "db.php";

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function pictureChanged()
{
}

function doBack()
{
	var div_list = parent.document.getElementById("div_list");
  div_list.style.visibility = "visible";
  var div_detail = parent.document.getElementById("div_detail");
  div_detail.style.visibility = "hidden";
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

($prevID > 0) ? $prevDisabled = false : $prevDisabled = true;
($nextID > 0) ? $nextDisabled = false : $nextDisabled = true;
$tabAction = "
<TABLE border=0 align='left' cellpadding='0' cellspacing='0'>
  <TR>" . 
    com_printButton("btn_back","Tillbaka","doBack()","","font-size:10px",1,"",false) . 
    com_printButton("btn_prev","&nbsp<=&nbsp;","doPrev()","","font-size:10px",1,"",$prevDisabled) .
    com_printButton("btn_next","&nbsp=>&nbsp;","doNext()","","font-size:10px",1,"",$nextDisabled) ."
  </TR>
</TABLE>";

$style = "";
$today = date("Y-m-d");
if ($actionInfo["ReadyDate"] < $today && $actionInfo["Finished"] == 0)
  $style = "color:red";
  
$header = $actionInfo["No"] . " " . $actionInfo["Name"];
$tab = "
<TABLE style='border: 1px solid darkred' cellpadding='0' cellspacing='5'>
<TR><TD class='header_small' style='{$style}' colspan='2' align='center'>{$header}</TD></TR>
<TR>" . com_printInputText("Namn:","","txt_name","txt_name",$actionInfo["Name"],80,100,false) . "</TR>
<TR>" . com_printTextArea("Beskrivning:","","txt_descr","txt_descr",$actionInfo["Descr"],6,80,false,false) . "</TR>
<TR>" . com_printSelect("Prio:","","sel_prio","sel_prio",false,1,$arrPrios,$actionInfo["Prio"],"pictureChanged()",true,false) . "
<TR>" . com_printInputText("Rapportör:","","txt_reporter","txt_reporter",$actionInfo["ReporterName"],80,100,false) . "</TR>
<TR>" . com_printTextArea("Åtgärder:","","txt_action","txt_action",$actionInfo["Actions"],6,80,false,false) . "</TR>
<TR>" . com_printInputText("Ansvarig:","","txt_responsible","txt_responsible",$actionInfo["Responsible"],80,100,false) . "</TR>
<TR>" . com_printInputText("Klar-datum:","","txt_responsible","txt_responsible",$actionInfo["ReadyDate"],10,10,false) . "</TR>
<TR>" . com_printYesNo("Klar:","","rdo_finished","rdo_finished",$actionInfo["Finished"],"pictureChanged()",false,false) . "</TR>
<TR><TD colspan='2' align='center' height='5'></TD></TR>
<TR><TD colspan='2' align='center'>{$tabAction}</TD></TR>
</TABLE>";



//com_printButton($id,$value,$jsRoutine,$cssClass,$tdStyle,$spaces,$info,$disabled)

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='act_detail_save.php' method='POST'>
 
<DIV id='div_main'>
<TABLE border='0' cellpadding='0' cellspacing='5'>
  <TR><TD valign='top'>{$tab}</TD></TR>
</TABLE>
</DIV>

</FORM></BODY></HTML>";
?>