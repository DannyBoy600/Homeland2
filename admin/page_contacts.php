<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = $_SESSION["sess_pageID"];

$cid = $_SESSION["sess_companyID"];

$db = db_connect();

$contacts = null;
$sql = "SELECT DISTINCT ContactID,Name,Role,Email,Phone,Mobile,JPG,UserName,PassWord,Info,IsVisible FROM PageContact WHERE PageID = {$pageID} ORDER BY Name";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$contacts[] = $row;
}
  
// add some extra lines
for ($i=0;$i<5;$i++)
  $contacts[] = array(0,"","","","","","","","","",0);
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

function handlePicture(rowNo)
{
	var divList = document.getElementById("div_list");
	divList.style.visibility = "hidden";
	
	var divUpload = document.getElementById("div_upload");
	divUpload.style.visibility = "visible";
	var imgUpload = document.getElementById("img_upload");
	var btnDelete = document.getElementById("btn_delete");
	var tdName = document.getElementById("td_upload_name");
	
	var idList = document.getElementById("hid_id_" + rowNo);
	var imgList = document.getElementById("img_picture_" + rowNo);
	if (imgList.src.indexOf("contact_" + idList.value) > -1) 
	{
		imgUpload.src = "../docs/company_" + cid + "/contacts/contact_" + idList.value + ".jpg";
		btnDelete.style.visibility = "visible";
  }
  else
  {
  	imgUpload.src = "../images/NoPicture.jpg";
		btnDelete.style.visibility = "hidden";
  }
  tdName.innerHTML = document.getElementById("txt_name_" + rowNo).value;
  document.getElementById("hid_upload_id").value = idList.value;
}

function cancelUpload()
{
	var divList = document.getElementById("div_list");
	divList.style.visibility = "visible";
	
	var divUpload = document.getElementById("div_upload");
	divUpload.style.visibility = "hidden";
	
	var btnDelete = document.getElementById("btn_delete");
	btnDelete.style.visibility = "hidden";
}

function doUpload()
{
	var frm = document.forms[0];
	frm.action = "page_contacts_upload.php";
	frm.enctype = "multipart/form-data";
	frm.submit();
}

function deletePicture()
{
	if (!confirm("Vill du verkligen radera bilden?")) return;
	var frm = document.forms[0];
	frm.action = "page_contacts_delete.php";
	frm.submit();
}

function onLoad()
{
	top.changed = false;
}
</SCRIPT>

</HEAD>
<?php

// LinkID, Name, URL, Descr, IsVisible 
function printContacts()
{
  global $contacts,$cid;
  
	$tab = "
<TABLE border='0' cellpadding='3' cellspacing='1'>
  <tr bgColor='darkgrey' >
    <td class='normalBold' style='color:white' title='Bild'>&nbsp;</td>
    <td class='normalBold' style='color:white' title='Radera'>&nbsp;&nbsp;R</td>
    <td class='normalBold' style='color:white'>Visa</td>
    <td class='normalBold' style='color:white'>Namn</td>
    <td class='normalBold' style='color:white'>Roll</td>
    <td class='normalBold' style='color:white'>Email</td>
    <td class='normalBold' style='color:white'>Telefon</td>
    <td class='normalBold' style='color:white'>Mobil</td>
    <td class='normalBold' style='color:white'>Anv-namn</td>
    <td class='normalBold' style='color:white'>Lösenord</td>
    <td class='normalBold' style='color:white'>Id</td>
  </tr>";

  for ($i=0;$i<count($contacts);$i++)
  {
  	$curContactID = $contacts[$i][0];
    //$curName = htmlspecialchars($contacts[$i][1],ENT_QUOTES);
    $curName = htmlspecialchars($contacts[$i][1], ENT_COMPAT,'ISO-8859-1', true);
    $curRole = $contacts[$i][2];
    $curEmail = $contacts[$i][3];
    $curPhone = $contacts[$i][4];
    $curMobile = $contacts[$i][5];
    $curJPG = $contacts[$i][6];
    $curUsername = $contacts[$i][7];
    $curPassword = $contacts[$i][8];
    //$curInfo = htmlspecialchars($contacts[$i][9],ENT_QUOTES);
    $curInfo = htmlspecialchars($contacts[$i][9], ENT_COMPAT,'ISO-8859-1', true);
    $curShow = $contacts[$i][10];
    $chkDel = "";
    if ($curContactID > 0) $chkDel = "<INPUT TYPE='checkbox' id='chk_del_{$i}' name='chk_del_{$i}' onClick='javascript:pictureChanged()'>";
    ($curShow == 1) ? $curChecked = "checked" : $curChecked = "";
    ($i % 2 == 0) ? $class='evenRow' : $class='oddRow';
    ($curContactID > 0) ? $curContactIDDisplay = $curContactID : $curContactIDDisplay = "";
    
    $ancImg = "<img src='../images/NoPicture.jpg' border='0' width=50>";
    if ($curContactID > 0)
    {
      $src = "../docs/company_{$cid}/contacts/contact_{$curContactID}.jpg";
      if (!file_exists($src)) $src = "../images/NoPictureSmall.jpg";
      $ancImg = "<a href='javascript:handlePicture({$i})'><img src='{$src}' id='img_picture_{$i}' border='0' title='Ladda upp/radera bild' width=50></a>";
    }
    $tab .= "
  <tr class='{$class}'>
    <td rowspan='2'>{$ancImg}</td>
    <td valign='top'>{$chkDel}</td>
    <td class='normal' align='center' valign='top'><input type='checkbox' {$curChecked}    name='chk_show_{$i}' onClick='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_name_{$i}'   name='txt_name_{$i}'   size='40' maxLength='100' value='{$curName}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_role_{$i}'   name='txt_role_{$i}'   size='20' maxLength='50'  value='{$curRole}'  onKeyDown='javascript:pictureChanged()'</td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_email_{$i}'  name='txt_email_{$i}'  size='40' maxLength='100' value='{$curEmail}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_phone_{$i}'  name='txt_phone_{$i}'  size='20' maxLength='100' value='{$curPhone}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_mobile_{$i}' name='txt_mobile_{$i}' size='20' maxLength='100' value='{$curMobile}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='text' class='normal' id='txt_un_{$i}'     name='txt_un_{$i}'     size='20' maxLength='20' value='{$curUsername}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'><input type='password' class='normal' id='txt_pw_{$i}'     name='txt_pw_{$i}'     size='20' maxLength='20' value='{$curPassword}' onKeyDown='javascript:pictureChanged()'></td>
    <td class='normal' valign='top'>{$curContactIDDisplay}</td>
    <input type='hidden' id='hid_id_{$i}' name='hid_id_{$i}' value='{$curContactID}'>
  </tr>
  <tr class='{$class}'>
    <td colspan='2' class='normal' align='right' valign='top'>Info:</td>
    <td colspan='8' class='normal' valign='top'><input type='text' class='normal' id='txt_info_{$i}' name='txt_info_{$i}' size='210' maxLength='255' value='{$curInfo}' onKeyDown='javascript:pictureChanged()'></td>
  </tr>";
  }
  $tab .= "
</table>";
  return $tab;
}

$tabUpload = "
<table border='0' cellpadding='3' cellspacing='1'>
  <tr>
    <td class='normalBold' id='td_upload_name'>&nbsp;</td>
  </tr>
  <tr>
    <td class='normalBold'><img src='../images/NoPicture.jpg' id='img_upload' width='200'></td>
  </tr>
  <tr>
    <td class='normalBold'>Ladda upp bild (jpg-format):&nbsp;
      <input type='file' size='65' name='fil_picture' value='' onChange='javascript:pictureChanged()'>
      <input  type='button' id='btn_delete' class='but' style='font-size:9pt' value='Radera bild' title='' onClick='javascript:deletePicture()'>
    </td>
  </tr>
  <tr>
    <td>
      <table border='0' cellpadding='0' cellspacing='0'>" .
        com_printButton("btn_ok"," Ok ","doUpload()","but","",1,"",false) . 
        com_printButton("btn_cancel","Avbryt","cancelUpload()","but","",1,"",false) . "
      </table>
    </td>
  </tr>
</table>";

echo "
<BODY class='center' onLoad='javascript:onLoad()'><FORM action='page_contacts_save.php' enctype='multipart/form-data' method='POST'>

<div id='div_list'>
<table border='0' cellpadding='0' cellspacing='0'>
  <TR><TD>" . printContacts() ."</TD></TR>
</table>
</div>

<div id='div_upload' style='position:absolute;left:5px;top:5px;visibility:hidden'>{$tabUpload}</div>

<input type='hidden' id='hid_upload_id' name='hid_upload_id' value='0'>

</FORM></BODY></HTML>";
?>