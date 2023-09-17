<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = $_SESSION["sess_pageID"];

$db = db_connect();

$comments = null;
$sql = "SELECT DISTINCT CommentID,Body,Picture,IsVisible,InsDate,InsBy FROM PageComment WHERE PageID = {$pageID} ORDER BY InsDate";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$comments[] = $row;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function pictureChanged()
{
  top.changed = true;
  parent.navbar.highlightDelete();
}

function onLoad()
{
	top.changed = false;
}
</SCRIPT>

</HEAD>
<?php

function printCommentPicture($pageID,$commentID,$width)
{
	$tab = "";
  $cid = $_SESSION["sess_companyID"];
  $fullName = "../docs/company_{$cid}/page_{$pageID}/comment_{$commentID}.jpg";
  if (file_exists ($fullName)) 
  {
    // get pictures to display
    $htmlSize = "";
    $size = getimagesize ($fullName);
    $w = $size[0];
    $h = $size[1];
    if ($w > $width) 
    {
      $scale = $w/$width;
      $w = floor($w/$scale);
      $h = floor($h/$scale);
      $htmlSize = "width='{$w}' height='{$h}'";
    }	
    $doc = "<img {$htmlSize} border='0' src='{$fullName}'>";
    $tab = "
<TABLE border='0' cellpadding='5' cellspacing='0' width='100%' height='100%'>
  <TR>
    <TD align='center'>
      <A href='{$fullName}' target='_blank' title='Visa dokumentet i nytt fönster'>{$doc}</A>
    </TD>
  </TR>
</TABLE>";
  }
  return $tab;
}

// CommentID,Body,Picture,IsVisible,InsDate,InsBy
function printComment($comment,$ind)
{
	global $pageID;
	
	$id      = $comment[0];
	$body    = com_fixLineBreak($comment[1]);
	$picture = $comment[2];
	$date    = substr($comment[4],0,16);
	$author  = $comment[5];

  $tabPic = "";
  if ($picture != "")
  {
	  $fullPic = "../images/smileys/{$picture}";
	  if (file_exists($fullPic))
	  {
      $tabPic = "
<table border='0' cellpadding=0 cellspacing=0 >
  <tr><td><img src='{$fullPic}' border=0></td></tr>
</table>";
    }
  }
  
  $chk = "<input type='checkbox' name='chk_del_{$ind}' onClick=javascript:pictureChanged()>";
  $tabInfo = "
<table border='0' cellpadding=0 cellspacing=0 >
  <tr><td class='normalBold'>{$chk}&nbsp;{$author}&nbsp;{$date}</td></tr>
  <tr><td class='normal'>{$body}</td></tr>
</table>";

  $tabUploadedPic = printCommentPicture($pageID,$id,400);
  
  $tab = "
<table style='border: 1px solid darkred' border='0' cellpadding=0 cellspacing=10 >
  <tr><td valign='top'>{$tabInfo}</td></tr>
  <tr><td valign='top'>{$tabPic}</td></tr>
  <tr><td valign='top'>{$tabUploadedPic}</td></tr>
  <input type='hidden' id='hid_id_{$ind}' name='hid_id_{$ind}' value='{$id}'>
</table>";

  return $tab;
}

function printComments()
{
  global $comments;
  
  $tab = "
<table border=0 cellpadding='0' cellspacing='5' align='center'>";

  for ($i=0;$i<count($comments);$i++)
  {
  	$tab .= "<tr><td align='left' valign='top'>" . printComment($comments[$i],$i) . "</td></tr>"; 
  }
  $tab .= "
</table>";
  return $tab;	
}

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_contacts_save.php' enctype='multipart/form-data' method='POST'>

<div id='div_list'>
<table border='0' cellpadding='0' cellspacing='0'>
  <TR><TD>" . printComments() ."</TD></TR>
</table>
</div>

</FORM></BODY></HTML>";
?>