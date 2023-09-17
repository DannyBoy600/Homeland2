<?php
session_start();
include "common.php";
include "db.php";

$pageID = com_getReqParamInt("pageID");

$_SESSION["sess_pageID"] = $pageID;

db_connect();

$crlf = chr(13) . chr(10);

$date = "";
$subject = "";
$allowComments = 0;
$author = "";
$hasText = 0;
$hasDocs = 0;
$hasLinks = 0;
$hasNews = 0;
$hasContacts = 0;
$hasActions = 0;
$hasComments = 0;
$hasBookings = 0;

$sql = "SELECT PageDate,Subject,AllowComments,InsBy FROM Page WHERE PageID = {$pageID}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_row($res))
{
	$date = com_getMyDate($row[0]);
	$subject = strtoupper($row[1]);
	$allowComments = strtoupper($row[2]);
	$author = $row[3];
}

$sql = "SELECT COUNT(*) AS CNT FROM PageText WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasText = 1;
  
$sql = "SELECT COUNT(*) AS CNT FROM PageDoc WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasDocs = 1;
  
$sql = "SELECT COUNT(*) AS CNT FROM PageLink WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasLinks = 1;
  
$sql = "SELECT COUNT(*) AS CNT FROM PageBlog WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasNews = 1;
  
$sql = "SELECT COUNT(*) AS CNT FROM PageContact WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasContacts = 1;
    
$sql = "SELECT COUNT(*) AS CNT FROM PageAction WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasActions = 1;
  
$sql = "SELECT COUNT(*) AS CNT FROM PageComment WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasComments = 1;

$sql = "SELECT COUNT(*) AS CNT FROM PageBook WHERE PageID = {$pageID} AND IsVisible = 1";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  if ($row->CNT > 0) $hasBookings = 1;
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT>
function doNewComment()
{
	var div = document.getElementById("div_comment");
	div.style.visibility = "visible";
	document.getElementById("txt_name").focus();
}

function doCancelComment()
{
	var div = document.getElementById("div_comment");
	div.style.visibility = "hidden";
	document.getElementById("txt_name").value = "";
	document.getElementById("txt_comment").value = "";
}

function doSaveComment()
{
	var author = document.getElementById("txt_name").value;
	var body = document.getElementById("txt_comment").value;
	var f = document.forms[0];
	f.action = "comment_save.php";
	f.method = "POST";
	f.submit();
}

function selectSmiley(ind)
{
	var i = 0;
	var td = document.getElementById("td_pic_" + i);
	while (td != null)
	{
		td.style.backgroundColor = "";
		i++;
	  td = document.getElementById("td_pic_" + i);
	}
	document.getElementById("td_pic_" + ind).style.backgroundColor = "#88C43E";	
	var src = document.getElementById("img_pic_" + ind).src;
	var pos = src.lastIndexOf("/");
	src = src.substr(pos+1);
	document.getElementById("hid_picture").value = src;
}

</SCRIPT>
</HEAD>

<?php

function printDocument($pageID,$docName,$docType,$docSize,$note,$width)
{
	$tab = "";
  if ($docName != "") 
  {
  	$cid = $_SESSION["sess_companyID"];
  	//$docName = htmlspecialchars($docName,ENT_QUOTES);
  	$docName = htmlspecialchars($docName, ENT_COMPAT,'ISO-8859-1', true);
    $docUrl = "./docs/company_{$cid}/page_{$pageID}/" . rawurlencode($docName);
    //$note = com_fixLineBreak(htmlspecialchars($note,ENT_QUOTES));
    $note = com_fixLineBreak(htmlspecialchars($note, ENT_COMPAT,'ISO-8859-1', true));
    
  	$fullName = "./docs/company_{$cid}/page_{$pageID}/{$docName}";
    if (file_exists ($fullName)) 
    {
      // get pictures to display
      $ext = com_getDocumentExtension($docType);
      if ($ext == "_picture")
      {
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
      }
      else
      {
        $doc = "<img border='0' src='./images/icon/dokument_48x48{$ext}.gif'>";
      } 
      $tab = "
<TABLE  style='border: 1px solid darkred' cellpadding='5' cellspacing='0' width='100%' height='100%'>
  <TR>
    <TD align='center'>
      <A href='{$docUrl}' target='_blank' title='Visa dokumentet i nytt fönster'>{$doc}</A>&nbsp;<br>
      <A href='{$docUrl}' style='color:#202020;font-weight:bold;text-decoration:none' target='_blank' title='Visa dokumentet i nytt fönster'>{$docName}</A>&nbsp;
    </TD>
  </TR>
  <TR><TD class='normal' valign='bottom'>{$note}</TD></TR>
</TABLE>";
    }
  }
  return $tab;
}

function printText()
{
	global $pageID;
	
	$body = "";
	$sql = "SELECT Body FROM PageText WHERE PageID = {$pageID} AND IsVisible = 1";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_row($res))
    $body = $row[0];
	
	// special, fix relative links
  $body = str_replace("../","./",$body);

  $tab = "
<TABLE  style='border: 0px solid darkred' cellpadding='5' cellspacing='0' width='100%' height='100%'>
  <TR>
    <TD align='left'>{$body}</TD>
  </TR>
</TABLE>";
  return $tab;
}

function printDocs()
{
  global $pageID;
  
	$tab = "
<table border='0' cellpadding='0' cellspacing='5' align='center'>
<tr><td class='header_small' align='center' colspan='2'>Bilder & dokument</td></tr>";

	$sql = "SELECT Name,DocType,DocSize,Comment FROM PageDoc WHERE PageID = {$pageID} AND IsVisible = 1";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  $rowNo = 0;
  while ($row = mysql_fetch_row($res))
  {
    $curName = $row[0];
    $curType = $row[1];
    $curSize = $row[2];
    $curComment = $row[3];
    if ($rowNo % 2 == 0) $tab .= "<tr>";
    $tab .= "<td valign='top' height='250'>" . printDocument($pageID,$curName,$curType,$curSize,$curComment,250) . "</td>";
    if ($rowNo % 2 == 1) $tab .= "</tr>";
    $rowNo++;
  }
  $tab .= "
</table>";

  return $tab;
}

function printLinks()
{
	global $pageID;
	
	$curLineNo = 0;
	$tab = "
<table style='border: 1px solid darkred' cellpadding='0' cellspacing='5' align='center'>
  <tr><td class='header_small' colspan='2' align='center'>Länkar</td></tr>";
  $sql = "SELECT DISTINCT LinkID, Name, URL, Descr FROM PageLink WHERE PageID = {$pageID} AND IsVisible = 1 ORDER BY Name";
  $res = mysql_query ($sql);
  while ($row = mysql_fetch_object($res)) 
  {
    $curLinkID = $row->LinkID;
    $curLinkName = $row->Name;
    $curUrl = $row->URL;
    if (strpos($curUrl, "http") === false)
      $curUrl = "http://" . $curUrl;  
    $curDescr = com_fixLineBreak(trim($row->Descr));
    $curLineNo++;
    if ($curLineNo % 2 == 0) $rowClass = "oddRow"; else $rowClass = "evenRow";
    $tab .= "
  <tr class='{$rowClass}'>
    <td class='normal' valign='top' nowrap><a href='{$curUrl}'  style='color:black;text-decoration:none' target=_blank hideFocus=true>{$curLinkName}</a></td>
     <td class='normal' valign='top'><a href='{$curUrl}' style='color:black;text-decoration:none' target=_blank hideFocus=true>{$curDescr}</a></td>
  </tr>";
  }
  $tab .= "
</table>";
  return $tab;
}

function printNews()
{
}

//  ContactID,Name,Role,Email,Phone,Mobile,JPG,Info
function printPerson($person)
{
	global $pageID;
	
	$id   = $person[0];
	$name   = $person[1];
	$role   = $person[2];
	$email  = $person[3];
	$phone  = $person[4];
	$mobile = $person[5];
	$pic    = $person[6];
	$info   = $person[7];

  $cid = $_SESSION["sess_companyID"];
	$fullPic = "./docs/company_{$cid}/contacts/contact_{$id}.jpg";
	if (!file_exists($fullPic)) $fullPic = "./images/NoPicture.jpg";

  $tabPic = "
<table border='0' cellpadding=0 cellspacing=0 >
  <tr><td><img src='{$fullPic}' border=0 width=200></td></tr>
</table>";

  $tabInfo = "
<table border='0' cellpadding=0 cellspacing=0 >
  <tr><td class='bigBold'>{$name}</td></tr>";
	if ($role != "")
    $tabInfo .= "
  <tr><td class='normal'><b>Roll:</b> {$role}</td></tr>";
	if ($email != "")
	  $tabInfo .= "
	<tr><td class='normal'><b>E-mail:</b> <a href='mailto:{$email}'>{$email}</a></td></tr>";
  if ($phone != "")
    $tabInfo .= "
  <tr><td class='normal'><b>Tel:</b> {$phone}</td></tr>";
  if ($mobile != "")
    $tabInfo .= "
  <tr><td class='normal'><b>Mobil:</b> {$mobile}</td></tr>";
  if ($info != "")
    $tabInfo .= "
  <tr><td class='normal'><b>Info:</b> {$info}</td></tr>";
  $tabInfo .= "
</table>";

  $tab = "
<table border='0' cellpadding=0 cellspacing=10 >
  <tr>
    <td valign='top'>{$tabPic}</td>
    <td valign='top'>{$tabInfo}</td>
  </tr>
</table>";

  return $tab;
}

function printContacts()
{
	global $pageID;
	
  $tab = "
<table style='border: 1px solid darkred' cellpadding='0' cellspacing='5' align='center'>
  <tr><td class='header_small' align='center'>Kontakter</td></tr>";
  
  $sql = "SELECT ContactID,Name,Role,Email,Phone,Mobile,JPG,Info FROM PageContact WHERE PageID = {$pageID} AND IsVisible = 1 ORDER BY Name";
  $res = mysql_query ($sql);
  while ($row = mysql_fetch_row($res))
  {
  	$tab .= "<tr><td align='left' valign='top'>" . printPerson($row) . "</td></tr>"; 
  }
  $tab .= "
</table>";
  return $tab;
}

function printActions()
{
	global $pageID;
	
	$tab = "
<table style='border: 1px solid darkred' cellpadding='0' cellspacing='5' align='center' width='690'>
  <tr><td class='header_small' colspan='2' align='center'>Åtgärdslista</td></tr>
  <tr><td colspan='3' align='center' valign='top'><IFRAME  src='act_main.php' name='ifr_act' id='ifr_act' frameBorder=0 width='100%' height='500'></IFRAME></td></tr>
</table>";
  return $tab;
}

function printBookings()
{
	global $pageID;
	
	$tab = "";
	if (isset($_SESSION["sess_homeland_logged_in"]))
	{
	  $header = "";
    $sql = "SELECT Header FROM PageBook WHERE PageID = {$pageID}";
    $res = mysql_query ($sql);
    if ($row = mysql_fetch_row($res)) 
	    $header = $row[0];

    $_SESSION["sess_book_userID"] = $_SESSION["sess_userID"];
  
	  $tab = "
<table style='border: 1px solid darkred' cellpadding='0' cellspacing='5' align='center' width='700'>
  <tr><td class='header_small' align='center'>Bokningar av {$header}</td></tr>
  <tr><td align='center' valign='top'><IFRAME src='book_main.php' name='ifr_book' id='ifr_book' frameBorder=0 width='100%' height='1400'></IFRAME></td></tr>
</table>";
  }
  return $tab;
}

function printSmileys()
{
	// smileys
  $smileys = null;
  $path = "./images/smileys//";
  if ($handle = opendir($path)) 
  {
    while (false !== ($file = readdir($handle)))
      if ($file != "." && $file != ".." && !is_dir($path . $file))
        $smileys[] = $path . $file;
  }
  closedir($handle);

	$tab = "";
  if (count($smileys) > 0)
  {
    $tab .= "
<TABLE border='0' cellpadding='3' cellspacing='0' >
  <TR><TD class='microBold' colspan='20'>Välj smiley:</TD></TR>";

    $cols = 10;
    $rows = count($smileys) / $cols;
    $i = 0;
    for ($row=0; $row<$rows; $row++)
    {
      $tab .= "
    <TR>";
      $j = 0; // row of pics
      while($i<count($smileys) && $j<$cols)
      {
        $picName = dirname($smileys[$i]) . "/" . rawurlencode(basename($smileys[$i]));
        $tab .= "<TD id='td_pic_{$i}' nowrap><A align='center' href='javascript:selectSmiley({$i})'><IMG border='0' id='img_pic_{$i}' width='30' height='30' src='{$picName}'></A></TD>";
        $i++;
        $j++;
      }
      $tab .= "
    </TR>";
    }
    $tab .= "
</TABLE><br>";
  }
  return $tab;
}

function printUpload()
{
  $tab = "
<table border='0' cellpadding='3' cellspacing='0'>
  <tr>
    <td class='normal'>Ladda upp bild (i jpg-format):&nbsp;</td>
  </tr>
  <tr>
    <td class='normal'>
      <input type='file' size='40' name='fil_comment' value='' onChange='javascript:pictureChanged()'>
    </td>
  </tr>
</table>";
  return $tab;
}

function printCommentPicture($pageID,$commentID,$width)
{
	$tab = "";
  $cid = $_SESSION["sess_companyID"];
  $fullName = "./docs/company_{$cid}/page_{$pageID}/comment_{$commentID}.jpg";
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

//  CommentID,Body,Picture,InsDate,InsBy
function printComment($comment)
{
	global $pageID;
	
	$id      = $comment[0];
	$body    = com_fixLineBreak($comment[1]);
	$picture = $comment[2];
	$date    = substr($comment[3],0,16);
	$author  = $comment[4];

  $tabPic = "";
  if ($picture != "")
  {
	  $fullPic = "./images/smileys/{$picture}";
	  if (file_exists($fullPic))
	  {
      $tabPic = "
<table border='0' cellpadding=0 cellspacing=0 >
  <tr><td><img src='{$fullPic}' border=0></td></tr>
</table>";
    }
  }
  
  $tabInfo = "
<table border='0' cellpadding=0 cellspacing=0 >
  <tr><td class='normalBold'>{$author}&nbsp;{$date}</td></tr>
  <tr><td class='normal'>{$body}</td></tr>
</table>";

  $tabUploadedPic = printCommentPicture($pageID,$id,400);

  $tab = "
<table border='0' cellpadding=0 cellspacing=10 >
  <tr>
    <td valign='top'>{$tabInfo}</td>
  </tr>
  <tr>
    <td valign='top'>{$tabPic}</td>
  </tr>
  <tr>
    <td valign='top'>{$tabUploadedPic}</td>
  </tr>
</table>";

  return $tab;
}

function printComments()
{
	global $pageID;
	
	$tab = "
<table style='border: 1px solid darkred' cellpadding='0' cellspacing='0' align='center'>
  <tr><td class='header_small' align='center'>Kommentarer</td></tr>";

  $sql = "SELECT CommentID,Body,Picture,InsDate,InsBy FROM PageComment WHERE PageID = {$pageID} AND IsVisible = 1 ORDER BY InsDate";
  $res = mysql_query ($sql);
  $cnt = 0;
  while ($row = mysql_fetch_row($res))
  {
  	if ($cnt > 0)
  	$tab .= "
  	<tr>
    <td valign='top' align='left'>&nbsp;&nbsp;<img src='./images/divider_1.jpg'></td>
   </tr>";
  	$tab .= "<tr><td align='left' valign='top'>" . printComment($row) . "</td></tr>"; 
  	$cnt++;
  }
  $tab .= "
</table>";
  return $tab;
}

echo "
<body class='page' bgcolor='white'><form enctype='multipart/form-data' method='post'>

<table border='0' cellpadding='0' cellspacing='10' align='center'>
  <tr><td class='header' valign='center' align='center'>{$subject}</td></tr>";
if ($hasText) echo "<TR><TD>" . printText() . "</TD></TR>";
if ($hasDocs) echo "<TR><TD>" . printDocs() . "</TD></TR>";
if ($hasLinks) echo "<TR><TD>" . printLinks() . "</TD></TR>";
if ($hasNews) echo "<TR><TD>" . printNews() . "</TD></TR>";
if ($hasContacts) echo "<TR><TD>" . printContacts() . "</TD></TR>";
if ($hasActions) echo "<TR><TD>" . printActions() . "</TD></TR>";
if ($hasBookings) echo "<TR><TD>" . printBookings() . "</TD></TR>";
if ($hasComments && $allowComments == 1) echo "<TR><TD>" . printComments() . "</TD></TR>";
if ($allowComments == 1) echo "<TR><TD><input type='button' class='but' style='font-size:10px' id='btn_new' value='&nbsp;Kommentera...&nbsp;' onClick='javascript:doNewComment()'></TD></TR>";
echo "
</table>";

if ($allowComments == 1)
  echo "
<div id='div_comment' style='visibility:hidden'>
<table style='border: 1px solid darkblue'  cellpadding='0' cellspacing='5' align='center'>
  <tr><td class='normal'>Jag heter:&nbsp;<input type='text' id='txt_name' name='txt_name' size=45></td></tr>
  <tr><td class='normal'><textarea id='txt_comment' name='txt_comment' cols=60 rows=6></textarea></td></tr>
  <tr><td>" . printSmileys() . "</td></tr>
  <tr><td>" . printUpload() . "</td></tr>
  <tr><td>
    <input type='button' class='but' style='font-size:10px' id='btn_new' value='&nbsp;Spara&nbsp;' onClick='javascript:doSaveComment()'>&nbsp;
    <input type='button' class='but' style='font-size:10px' id='btn_new' value='&nbsp;Avbryt&nbsp;' onClick='javascript:doCancelComment()'>
  </td></tr>
</table>
</div>";

echo "
<input type='hidden' id='hid_picture' name='hid_picture' value=''>

</form></body></html>";
?>