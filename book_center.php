<?php
session_start();
include "common.php";
include "db.php";

$yearNo = com_getReqParamInt("yearNo");
$_SESSION["sess_book_yearNo"] = $yearNo;

$cid = $_SESSION["sess_companyID"];
$pageID = $_SESSION["sess_pageID"];
$userID = $_SESSION["sess_book_userID"];

db_connect();

$header = "";
$contactPageID = 0;
$sql = "SELECT Header,ContactPageID FROM PageBook WHERE PageID = {$pageID}";
$res = mysql_query ($sql);
if ($row = mysql_fetch_row($res)) 
{
	$header = $row[0];
	$contactPageID = $row[1];
}

$arrContacts = null;
if ($contactPageID > 0)
{
	$sql = "SELECT ContactID,Name FROM PageContact WHERE PageID = {$contactPageID} ORDER BY Name";
  $res = mysql_query ($sql);
  while ($row = mysql_fetch_row($res)) 
  {
	  $arrContacts[$row[0]] = $row[1];
  }
}

$arrCells = null;
$sql = "SELECT CellID,WeekNo,DefaultContactID,ContactID,State FROM PageBookCell WHERE PageID = {$pageID} AND YearNo = {$yearNo} ORDER BY CellID";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$arrCells[$row[1]] = array($row[0],$row[2],$row[3],$row[4]);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>
var userID = <?php echo $userID?>;

function pictureChanged()
{
  top.changed = true;
  parent.navbar.highlightSave();
}

function doRed(weekNo)
{
	document.getElementById("btn_red_" + weekNo).style.backgroundColor = "red";
	document.getElementById("btn_yellow_" + weekNo).style.backgroundColor = "";
	document.getElementById("btn_green_" + weekNo).style.backgroundColor = "";
	document.getElementById("hid_state_" + weekNo).value = 3;
	document.getElementById("hid_contactID_" + weekNo).value = userID;
	document.getElementById("hid_dirty_" + weekNo).value = 1;
	pictureChanged();
}

function doYellow(weekNo)
{
	document.getElementById("btn_red_" + weekNo).style.backgroundColor = "";
	document.getElementById("btn_yellow_" + weekNo).style.backgroundColor = "yellow";
	document.getElementById("btn_green_" + weekNo).style.backgroundColor = "";
	document.getElementById("hid_state_" + weekNo).value = 2;
	document.getElementById("hid_contactID_" + weekNo).value = userID;
	document.getElementById("hid_dirty_" + weekNo).value = 1;
	pictureChanged();
}

function doGreen(weekNo)
{
	document.getElementById("btn_red_" + weekNo).style.backgroundColor = "";
	document.getElementById("btn_yellow_" + weekNo).style.backgroundColor = "";
	document.getElementById("btn_green_" + weekNo).style.backgroundColor = "green";
	document.getElementById("hid_state_" + weekNo).value = 1;
	document.getElementById("hid_contactID_" + weekNo).value = 0;
	document.getElementById("hid_dirty_" + weekNo).value = 1;
	pictureChanged();
}


function onLoad()
{
}
</SCRIPT>
</HEAD>

<?php

$today = date("Y-m-d");

$tabList = "
<TABLE border='0' cellpadding='0' cellspacing='3'>";
{
	$tabList .= "
<TR>
  <TD class='normalBold' width='15' align='right'>V&nbsp;</TD>
  <TD class='normalBold' align='center'>Ledig</TD>
  <TD class='normalBold' align='center'>Prel.bokad</TD>
  <TD class='normalBold' align='center'>Bokad</TD>
  <TD class='normalBold' align='left'>Person</TD>
</TR>";
  /*
  $arrContacts[contactid] = name;
	$arrCells[week] = array(cellid,defaultcontactid,contactid,state);
	*/
  for ($i=1;$i<53;$i++)
  {
  	$curCellID = $arrCells[$i][0];
  	$curDefaultContactID = $arrCells[$i][1];
  	$curContactID = $arrCells[$i][2];
  	$curState = $arrCells[$i][3]; // 1=Free, 2=Preliminary, 3=Booked 
  	
  	$arrBgColors = array("","","","");
  	switch ($curState)
  	{
  		case 1 : $arrBgColors[1] = "green"; break;
  		case 2 : $arrBgColors[2] = "yellow"; break;
  		case 3 : $arrBgColors[3] = "red"; break;
    }
    
    $name = "";
    $id = 0;
    if ($curState > 1)
    {
      if ($curContactID > 0)
      {
        if (isset($arrContacts[$curContactID]))
        {
          $name = $arrContacts[$curContactID];
          $id = $curContactID;
        }
      }
      else if ($curDefaultContactID > 0) 
      {
        if (isset($arrContacts[$curDefaultContactID]))
        {
          $name = $arrContacts[$curDefaultContactID];
          $id = $curDefaultContactID;
        }
      }
    }
      
    // authorization...
    $arrDisabled = array("","disabled","disabled","disabled");
    switch ($curState)
  	{
  		case 1 : /* green */
  		  $arrDisabled[1] = "";
  		  if ($curDefaultContactID == $userID) $arrDisabled[2] = "";
  		  $arrDisabled[3] = "";
  		  break;
  		case 2 : /* yellow */
  		  if ($curDefaultContactID == $userID)
  		  {
  		    $arrDisabled[1] = "";
  		    $arrDisabled[2] = "";
  		    $arrDisabled[3] = "";
  		  }
  		  break;
  		case 3 : /* red */
  		  if ($curContactID == $userID)
  		  {
  		    $arrDisabled[1] = "";
  		    if ($userID == $curDefaultContactID) $arrDisabled[2] = "";
  		    $arrDisabled[3] = "";
  		  }
  		  break;
    }

    ($id == $userID) ? $nameStyle = "font-weight:bold" : $nameStyle = "font-style:italic";
    
    $tabList .= "
<TR>
  <TD class='normal' width='15' align='right'>{$i}&nbsp;</TD>
  <TD class='normal'><INPUT type='button' {$arrDisabled[1]} class='but' style='background-color:{$arrBgColors[1]};width:80px' id='btn_green_{$i}'  value='&nbsp;&nbsp;' onClick='javascript:doGreen({$i})' hidefocus=true>&nbsp;</TD>
  <TD class='normal'><INPUT type='button' {$arrDisabled[2]} class='but' style='background-color:{$arrBgColors[2]};width:80px' id='btn_yellow_{$i}' value='&nbsp;&nbsp;' onClick='javascript:doYellow({$i})' hidefocus=true>&nbsp;</TD>
  <TD class='normal'><INPUT type='button' {$arrDisabled[3]} class='but' style='background-color:{$arrBgColors[3]};width:80px' id='btn_red_{$i}'    value='&nbsp;&nbsp;' onClick='javascript:doRed({$i})' hidefocus=true>&nbsp;</TD>
  <TD class='normal' style='{$nameStyle}'>{$name}&nbsp;</TD>
  <INPUT type='hidden' id='hid_cellID_{$i}'    name='hid_cellID_{$i}' value='{$curCellID}'>
  <INPUT type='hidden' id='hid_state_{$i}'     name='hid_state_{$i}' value='{$curState}'>
  <INPUT type='hidden' id='hid_contactID_{$i}' name='hid_contactID_{$i}' value='0'>
  <INPUT type='hidden' id='hid_dirty_{$i}'     name='hid_dirty_{$i}' value='0'>
</TR>";
  }
}
$tabList .= "
</TABLE>";

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='book_save.php' method='POST'>

{$tabList}

</FORM></BODY></HTML>";
?>