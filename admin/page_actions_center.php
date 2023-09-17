<?php
include "head.php";
include "../db.php";
include "../common.php";

$prio = com_getReqParamInt("prio");
$finished = com_getReqParamInt("finished"); // 0 = don't care, 1 = yes, 2 = no

db_connect();

$cid = $_SESSION["sess_companyID"];

$pageID = $_SESSION["sess_pageID"];

$arrItems = null;
$sql = "
SELECT ActionID,No,Name,Finished,Prio,Descr,ReadyDate,Responsible,InsDate 
FROM PageAction
WHERE IsVisible = 1 AND PageID = {$pageID}";
if ($prio > 0) $sql .= " AND Prio = {$prio}";
if ($finished > 0)  
  ($finished == 1) ? $sql .= " AND Finished = 1" : $sql .= " AND Finished = 0";
$sql .= " ORDER BY No";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
while ($row = mysql_fetch_row($res))
{
  $arrItems[] = $row;
}
$_SESSION["act_list"] = $arrItems;

?>
<HTML>

<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function showItem(actionID)
{
	var div_list = document.getElementById("div_list");
  div_list.style.visibility = "hidden";
  var div_detail = document.getElementById("div_detail");
  div_detail.style.visibility = "visible";
  var ifr_detail = document.getElementById("ifr_detail");
  ifr_detail.contentWindow.location.href = "page_actions_detail.php?actionID=" + actionID;
  
}

function onLoad()
{
}
</SCRIPT>
</HEAD>

<?php

$today = date("Y-m-d");

//ActionID,No,Name,Finished,Prio,Descr,ReadyDate,Responsible,InsDate 
$tabList = "";
if (count($arrItems) > 0)
{
  $tabList = "
<TABLE border='0' cellpadding='2' cellspacing='1' align='left'>
  <TR bgColor='darkgrey'>
    <TD class='normalBold' style='color:white'>Nr&nbsp;</TD>
    <TD class='normalBold' style='color:white'>Namn&nbsp;</TD>
    <TD class='normalBold' style='color:white'>Prio&nbsp;</TD>
    <TD class='normalBold' style='color:white'>Klar-datum&nbsp;</TD>
    <TD class='normalBold' style='color:white'>Klar&nbsp;</TD>
    <TD class='normalBold' style='color:white'>Ansvarig</TD>
  </TR>";

  for ($i=0;$i<count($arrItems);$i++)
  {  	
  	$curID = $arrItems[$i][0];
  	$curNo = $arrItems[$i][1];
  	$curName = $arrItems[$i][2];
  	($arrItems[$i][3] == 1) ? $curFin = "Ja" : $curFin = "Nej";
  	$curPrio = $arrItems[$i][4];
  	$curDescr = $arrItems[$i][5];
  	$curReadyDate = $arrItems[$i][6];
  	$curResp = $arrItems[$i][7];
  	$curInsDate = $arrItems[$i][8];
  	
    ($i % 2 == 0) ? $rowClass='evenRow' : $rowClass='oddRow';
    $style = "color:black;text-decoration:none";
    if (com_displayDate($curReadyDate) != "")
    {
      if ($curReadyDate < $today && $curFin == "Nej")
        $style = "color:red;text-decoration:none";
    }
      
    $tabList .= "<TR class='{$rowClass}'>";
    $tabList .= com_printLink($curNo,"",0,"showItem({$curID})","normalBold",$style,false);
    $tabList .= com_printLink($curName,"",0,"showItem({$curID})","normalBold",$style,false);
    $tabList .= com_printLink($curPrio,"",0,"showItem({$curID})","normalBold",$style,false);
    $tabList .= com_printLink(com_displayDate($curReadyDate),"",0,"showItem({$curID})","normalBold",$style,false);
    $tabList .= com_printLink($curFin,"",0,"showItem({$curID})","normalBold",$style,false);
    $tabList .= com_printLink($curResp,"",0,"showItem({$curID})","normalBold",$style,false);
    $tabList .= "<INPUT type='hidden' id='hid_actionID_{$i}' value='{$curID}'>";
    $tabList .= "</TR>";
    $tabList .= "<TR class='{$rowClass}'><TD colspan='6' class='normal'><A href='javascript:showItem({$curID})' class='normal' style='{$style}'>{$curDescr}</A></TD></TR>";
  }
  $tabList .= "
</TABLE>";
}

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM>

<DIV id='div_list'>{$tabList}</DIV>

<DIV id='div_detail' style='position:absolute;top:0px;left:5px;visibility:hidden;'><IFRAME name='ifr_detail' id='ifr_detail' frameBorder=0 width='800' height='500'></DIV>

</FORM></BODY></HTML>";
?>