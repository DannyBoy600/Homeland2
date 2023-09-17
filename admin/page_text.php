<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = $_SESSION["sess_pageID"];

$cid = $_SESSION["sess_companyID"];

db_connect();

$crlf = chr(13) . chr(10);

$body = "";
$sql = "SELECT Body FROM PageText WHERE PageID = {$pageID}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_row($res))
  $body = $row[0];

?>
<HTML>
<HEAD>
<script language="Javascript" src="../editor/scripts/language/sv-SE/editor_lang.js"></script>
<script language="Javascript" src="../editor/scripts/innovaeditor.js"></script>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
</HEAD>

<?php

function encodeHTML($sHTML)
{
  $sHTML=str_replace("&","&amp;",$sHTML);
  $sHTML=str_replace("<","&lt;",$sHTML);
  $sHTML=str_replace(">","&gt;",$sHTML);
  return $sHTML;
}

$tab = "
<TABLE border='0' cellpadding='0' cellspacing='2'>
  <tr>
    <td class='normal'>
    <textarea id='txt_body' name='txt_body' rows=20 cols=40>" . encodeHTML(stripslashes($body)) . "</textarea>
    <script>
      var oEdit1 = new InnovaEditor('oEdit1');
      oEdit1.cmdAssetManager = \"modalDialogShow('../editor/assetmanager/assetmanager.php?lang=sv-SE&cid={$cid}',700,500);\";
			oEdit1.width='800px';
			oEdit1.height='600px';
			oEdit1.initialRefresh=true;
			oEdit1.REPLACE('txt_body');
	  </script>
    </td>
  </tr>
</TABLE>";

echo "
<body class='center'><form action='page_text_save.php' method='post'>

<table border='0' cellpadding='0' cellspacing='0' align='left'>
  <TR><TD>{$tab}</TD></TR>
</table>

<input type='hidden' id='hid_body' name='hid_body' value=''>

</body></html>";
?>