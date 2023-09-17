<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = $_SESSION["sess_pageID"];
$kind = $_SESSION["sess_kind"];

$db = db_connect();

$date = "";
$subject = "";
$hasCommon = 0;
$hasText = 0;
$hasDocs = 0;
$hasLinks = 0;
$hasNews = 0;
$hasContacts = 0;
$hasActions = 0;
$hasComments = 0;
$hasBookings = 0;
$author = "";

if ($pageID > 0)
{
	$hasCommon = 1;
	
  $sql = "SELECT COUNT(*) AS CNT FROM PageText WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasText = 1;
    
  $sql = "SELECT COUNT(*) AS CNT FROM PageDoc WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasDocs = 1;
    
  $sql = "SELECT COUNT(*) AS CNT FROM PageLink WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasLinks = 1;
    
  $sql = "SELECT COUNT(*) AS CNT FROM PageBlog WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasNews = 1;
    
  $sql = "SELECT COUNT(*) AS CNT FROM PageContact WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasContacts = 1;
      
  $sql = "SELECT COUNT(*) AS CNT FROM PageAction WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasActions = 1;
    
  $sql = "SELECT COUNT(*) AS CNT FROM PageComment WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasComments = 1;
    
  $sql = "SELECT COUNT(*) AS CNT FROM PageBook WHERE PageID = {$pageID} ";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    if ($row->CNT > 0) $hasBookings = 1;
}

if ($kind == "")
{
	if ($hasText == 1) $kind = "text";
	else if ($hasDocs == 1) $kind = "docs";
	else if ($hasLinks == 1) $kind = "links";
	else if ($hasNews == 1) $kind = "news";
	else if ($hasContacts == 1) $kind = "contacts";
	else if ($hasActions == 1) $kind = "actions";
	else if ($hasBookings == 1) $kind = "bookings";
	else $kind = "common";
}
?>
<HTML>
<HEAD>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>
var pageID = <?php echo $pageID?>;

function highlight(kind)
{
	var kinds = new Array("common","text","docs","links","contacts","actions","comments","bookings");
	for(var i=0; i < kinds.length; i++) 
	{
	  var anc = document.getElementById("anc_" + kinds[i]); 
	  if (anc != null)
	  {
	    if (kind == kinds[i])
		  {
	      anc.style.color = "darkred";
	      anc.style.fontWeight = "bold";
	    }
	    else
	    {
	      anc.style.color = "";
	      anc.style.fontWeight = "normal";
	    }
	  }
	}
}

/*
function showCommon()
{
	highlight("");
	var loc = "page_navbar.php?kind=common";
	parent.navbar.location.href = loc;
}
*/

function showPart(kind)
{
	highlight(kind);
	var loc = "page_navbar.php?kind=" + kind;
	parent.navbar.location.href = loc;
}

function onLoad()
{
	top.changed = false;
	var kind = "<?php echo $kind?>";
	showPart(kind)
}
</SCRIPT>

<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">

</HEAD>
<?php

function printLink($label,$kind,$exists)
{
  if ($exists) $label .= "*";
  $s = "<A HREF='javascript:showPart(\"{$kind}\")' id='anc_{$kind}' class='normal' style='font-size:12px;text-decoration:none;color:black' hidefocus=true>{$label}</A>";	
  return $s;
}

echo "
<BODY class='left' onLoad='javascript:onLoad()'><FORM>";

//if ($pageID > 0)
{
	echo "
<table border='0' cellpadding='0' cellspacing='10' align='center'>

  <tr><td class='normalBold' style='font-size:10px'>Varje sida kan innehålla olika typer av info.<br>(* = info finns)<br><br></td></tr>";

  echo "<TR><TD>" . printLink("Allmänt","common",$hasCommon) . "</TD></TR>";

  echo "<TR><TD>" . printLink("Text","text",$hasText) . "</TD></TR>";
  
  echo "<TR><TD>" . printLink("Dokument & bilder","docs",$hasDocs) . "</TD></TR>";
  
  echo "<TR><TD>" . printLink("Länkar","links",$hasLinks) . "</TD></TR>";
  
  echo "<TR><TD>" . printLink("Personer","contacts",$hasContacts) . "</TD></TR>";
  
  echo "<TR><TD>" . printLink("Åtgärdslista","actions",$hasActions) . "</TD></TR>";
  
  echo "<TR><TD>" . printLink("Bokning","bookings",$hasBookings) . "</TD></TR>";
  
  if ($hasComments == 1) echo "<TR><TD>" . printLink("Kommentarer","comments",1) . "</TD></TR>";
  
  echo "<TR><TD class='normal'>".str_repeat("<br>",30) . "(id:{$pageID})</TD></TR>";

  echo "
</table>";
}

echo "
</FORM></BODY></HTML>";
?>