<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = $_SESSION["sess_pageID"];

db_connect();

?>
<HTML>
<HEAD>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function pictureChanged()
{
  top.changed = true;
  parent.navbar.highlightSave();	
}

function onLoad()
{
	top.changed = false;
}
</SCRIPT>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
</HEAD>

<?php

function printDocument($rowNo,$docID,$pageID,$docName,$docType,$docSize,$note,$width)
{
	$doc = "";
  if ($docName != "") 
  {
  	//$docName = htmlspecialchars($docName,ENT_QUOTES);
  	$docName = htmlspecialchars($docName, ENT_COMPAT,'ISO-8859-1', true);
  	$cid = $_SESSION["sess_companyID"];
    $docUrl = "../docs/company_{$cid}/page_{$pageID}/" . rawurlencode($docName);
    //$note = htmlspecialchars($note,ENT_QUOTES);
    $note = htmlspecialchars($note, ENT_COMPAT,'ISO-8859-1', true);
  
  	$fullName = "../docs/company_{$cid}/page_{$pageID}/{$docName}";
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
        $doc = "<img border='0' src='../images/icon/dokument_48x48{$ext}.gif'>";
      } 
    }
  }
  $tab = "
<TABLE  style='border: 1px solid darkred' cellpadding='5' cellspacing='0' width='100%' height='100%'>
  <TR>
    <TD align='left' valign='top' class='normal'><INPUT type='checkbox' id='chk_del_{$rowNo}' name='chk_del_{$rowNo}' onClick='javascript:pictureChanged()'>Radera</TD>
  </TR>
  <TR>
    <TD align='center' valign='top'><A href='{$docUrl}' target='_blank' title='Visa dokumentet i nytt fönster'>{$doc}</A>&nbsp;</TD>
  </TR>
  <TR>
    <TD class='normal' valign='bottom'><TEXTAREA id='txt_note_{$rowNo}' name='txt_note_{$rowNo}' rows='4' cols='50' onKeyDown='javascript:pictureChanged()'>{$note}</TEXTAREA></TD>
  </TR>
  <INPUT TYPE='hidden' id='hid_id_{$rowNo}' name='hid_id_{$rowNo}' value='{$docID}'>
  <INPUT TYPE='hidden' id='hid_doc_name_{$rowNo}' name='hid_doc_name_{$rowNo}' value='{$docName}'>
</TABLE>";
  return $tab;
}

function printDocs()
{
  global $pageID;
  
	$tab = "
<table border='0' cellpadding='0' cellspacing='5' align='center'>";

	$sql = "SELECT DocID,Name,DocType,DocSize,Comment FROM PageDoc WHERE PageID = {$pageID} AND IsVisible = 1";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  $rowNo = 0;
  while ($row = mysql_fetch_row($res))
  {
  	$curID = $row[0];
    $curName = $row[1];
    $curType = $row[2];
    $curSize = $row[3];
    $curComment = $row[4];
    if ($rowNo % 2 == 0) $tab .= "<tr>";
    $tab .= "<td valign='top' height='250'>" . printDocument($rowNo,$curID,$pageID,$curName,$curType,$curSize,$curComment,150) . "</td>";
    if ($rowNo % 2 == 1) $tab .= "</tr>";
    $rowNo++;
  }
  $tab .= "
</table>";

  return $tab;
}

$tab = "
<table border='0' cellpadding='0' cellspacing='2'>
  <tr>
    <td class='normalBold'>Ladda upp dokument eller bild:&nbsp;</td>
    <td class='normal'>
      <input type='file' size='65' name='fil_document' value='' onChange='javascript:pictureChanged()'>
    </td>
  </tr>
</table>";
  
echo "
<body class='center'><form action='page_docs_save.php' enctype='multipart/form-data' method='post'>

<table border='0' cellpadding='0' cellspacing='0'>
  <TR><TD>{$tab}</TD></TR>
  <TR><TD>" . printDocs() ."</TD></TR>
</table>

</form></body></html>";
?>