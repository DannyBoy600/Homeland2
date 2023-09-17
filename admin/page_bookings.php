<?php
include "head.php";
include "../db.php";
include "../common.php";
include "../book_functions.php";

$curYearNo = (int)date("Y");

$yearNo = com_getReqParamInt("yearNo",$curYearNo);

$pageID = $_SESSION["sess_pageID"];
$cid = $_SESSION["sess_companyID"];

$db = db_connect();

$bookingExist = 0;
$header = "";
$contactPageID = 0;
$sql = "SELECT Header,ContactPageID FROM PageBook WHERE PageID = {$pageID}";
$res = mysql_query ($sql);
if ($row = mysql_fetch_row($res)) 
{
	$bookingExist = 1;
	$header = $row[0];
	$contactPageID = $row[1];
}

$arrDefaultWeeks = null;
$sql = "SELECT WeekNo,DefaultContactID FROM PageBookCell WHERE PageID = {$pageID} AND YearNo = {$yearNo} AND DefaultContactID > 0 ORDER BY WeekNo";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$arrDefaultWeeks[$row[0]][$row[1]] = 1;
}

$arrContactLists = null;
$sql = "
SELECT DISTINCT PC.PageID,P.MenuHeader 
FROM PageContact PC
INNER JOIN Page P ON P.PageID = PC.PageID
WHERE P.CompanyID = {$cid} 
ORDER BY P.MenuHeader";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$arrContactLists[] = $row;
}

$arrContacts = null;
if ($contactPageID > 0)
{
	$sql = "SELECT ContactID,Name FROM PageContact WHERE PageID = {$contactPageID} ORDER BY Name";
  $res = mysql_query ($sql);
  while ($row = mysql_fetch_row($res)) 
  {
	  $arrContacts[] = $row;
  }
}

// find out if bookings exist
$redsExist = false;
$sql = "SELECT COUNT(*) FROM PageBookCell WHERE PageID = {$pageID} AND ContactID > 0";
$res = mysql_query ($sql);
if ($row = mysql_fetch_row($res)) 
  if ($row[0] > 0)
    $redsExist = true;

$loopYearNo = $curYearNo;
for ($i=0;$i<10;$i++)
{
  $arrDefaultYears[] = array($loopYearNo,$loopYearNo);
  $loopYearNo++;
}
?>
<HTML>
<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>
var cid = "<?php echo $cid?>";
function pictureChanged()
{
  top.changed = true;
  parent.navbar.highlightSave();
}

function setBookingButton()
{
	var bookingExist = <?php echo $bookingExist?>;
	var btn = parent.navbar.document.getElementById("btn_book");
	if (btn != null)
	{
		if (bookingExist == 1)
		  btn.disabled = false;
		else
			btn.disabled = true;
  }
}

function selectDefaultYear()
{
	if (top.changed)
	  if (!confirm("Bilden är ändrad, vill du fortsätta utan att spara?")) 
	    return;
  top.changed = false;
  self.location.href = "page_bookings.php?yearNo=" + document.getElementById("sel_default_year").value;
}

function clearAllMarks()
{
	var i = 0;
  var hid = document.getElementById("hid_contactID_" + i);
  while (hid != null)
  {
  	for (var j=1; j<53;j++)
  	  document.getElementById("chk_" + i + "_" + j).checked = false;
  	i++;
    hid = document.getElementById("hid_contactID_" + i);
  }
  pictureChanged();
}

function onLoad()
{
	setBookingButton();
	top.changed = false;
}
</SCRIPT>

</HEAD>
<?php

$tab = "
<TABLE border='0' cellpadding='3' cellspacing='1'>
<TR>
  <TD class='normalBold'>Bokning av:&nbsp;</TD>
  <TD><INPUT TYPE='text' class='normal' id='txt_header' name='txt_header' size=80 maxLength=100 value='{$header}' onKeyDown='javascript:pictureChanged()'></TD>
</TR>
<TR>" .
  com_printSelect("Personlista:","","sel_contact_pages","sel_contact_pages",false,1,$arrContactLists,$contactPageID,"pictureChanged()",true,$redsExist) . "
</TR>
<TR>
  <TD class='normal' colspan='2'>Anm. Personerna i personlistan bokar veckor. Här kan man välja vilken sidas personlista som skall användas.&nbsp;</TD>
</TR>
</TABLE>";

$tabList = "
<TABLE border='0' cellpadding='0' cellspacing='1'>";
if (count($arrContacts) > 0)
{
	$tabList .= "
<TR>
  <TD colspan='53'>
    <TABLE border='0' cellpadding='0' cellspacing='0'>
      <TR>" .
        com_printSelect("Förhandsbokade veckor år:","","sel_default_year","sel_default_year",false,1,$arrDefaultYears,$yearNo,"selectDefaultYear()",false,false) . "
        <TD>&nbsp;&nbsp;&nbsp;</TD>" .
        com_printButton("btn_clear","Rensa","clearAllMarks()","btn","font-size:8pt",0,"Rensa alla markeringar",false) . "
      </TR>
    </TABLE>
  </TD>
</TR>
<TR><TD>&nbsp;</TD>";
  for ($i=1;$i<53;$i++)
  {
    $tabList .= "<TD class='normal' width='15'>{$i}</TD>";
  }
  $tabList .= "
</TR>";
  for ($i=0;$i<count($arrContacts);$i++)
  {
  	$curContactID = $arrContacts[$i][0];
  	$curContactName = $arrContacts[$i][1];
    $tabList .= "
<TR>
  <TD class='normal' nowrap>{$curContactName}</TD>
  <INPUT type='hidden' id='hid_contactID_{$i}' name='hid_contactID_{$i}' value='{$curContactID}'>";
    for ($j=1;$j<53;$j++)
    {
    	(isset($arrDefaultWeeks[$j][$curContactID])) ? $checked = "checked" : $checked = "";
      $tabList .= "<TD class='normal'><INPUT type='checkbox' id='chk_{$i}_{$j}' name='chk_{$i}_{$j}' {$checked} onClick='javascript:pictureChanged()'></TD>";
    }
    $tabList .= "
</TR>";
  }
}
$tabList .= "
</TABLE>";

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_bookings_save.php' method='POST'>

<TABLE border='0' cellpadding='0' cellspacing='2'>
  <TR><TD valign='top'>{$tab}</TD></TR>
  <TR><TD valign='top'>&nbsp;</TD></TR>
  <TR><TD valign='top'>{$tabList}</TD></TR>
</TABLE>

</FORM></BODY></HTML>";
?>