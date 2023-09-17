<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$i = 0;
while (isset($_POST["hid_id_{$i}"]))
{
	$curID = com_getReqParamInt("hid_id_{$i}");
	$curDel = com_getReqParamChk("chk_del_{$i}");
	$curIsVisible = 1;
  $curComment = com_getReqParamStr("txt_note_{$i}");
  $curDocName = com_getReqParamStr("hid_doc_name_{$i}");
  if ($curID > 0)
  {
  	if ($curDel == 1)
  	{
  		$sql = "DELETE FROM PageDoc WHERE DocID = {$curID}";
  		$res = mysql_query ($sql);
      $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
      if ($errorNo == 0)
      {
  		  // unlink
  		  $fullName = "../docs/company_{$cid}/page_{$pageID}/{$curDocName}";
  		  if (file_exists ($fullName)) unlink($fullName);
  		}
  	}
    else
    {
      $sql = "UPDATE PageDoc SET Comment='{$curComment}' WHERE DocID = {$curID}";
      $res = mysql_query ($sql);
      $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    } 
  }
  $i++;
}

// make sure company folder exists
$path = "../docs/company_{$cid}";
if (!file_exists($path)) mkdir($path);
$path = "../docs/company_{$cid}/contacts";
if (!file_exists($path)) mkdir($path);

// upload file
$path = "../docs/company_{$cid}/page_{$pageID}";
if (!file_exists($path)) mkdir($path);

$filename = $_FILES['fil_document']['tmp_name'];
$msg = "";
if ($filename != "")
{  
  $errorNo = $_FILES['fil_document']['error'];
  if ($errorNo != 0)
    $msg = "Fel vid inläsning av dokument";
  
  if ($msg == "")
  {
    $filename = $_FILES['fil_document']['tmp_name'];
    if (!file_exists ($filename))
      $msg = "Kunde inte hitta filen {$filename}";
  }
  
  if ($msg == "")
  {
    $origfilename = $_FILES['fil_document']['name'];
    $fileType = $_FILES['fil_document']['type'];
    $fileSize = $_FILES['fil_document']['size'];
  }

  if ($msg == "")
  {
  	$newFilename = "{$path}/{$origfilename}";
    if (!move_uploaded_file ($filename, $newFilename))
      $msg = "Kunde inte spara bilden!";
  }

  // insert database post
  if ($msg == "")
  {
    $sql = "
INSERT PageDoc (DocID,PageID,MenuID,CompanyID,Name,DocType,DocSize,Comment,IsVisible,InsDate,InsBy) VALUES 
(NULL,{$pageID},{$menuID},{$cid},'{$origfilename}','{$fileType}','{$fileSize}','',1,now(),'{$logger}')";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  }
}

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
function onLoad()
{
	var msg = "<?php echo $msg?>";
	if (msg != "") alert(msg);
  parent.left.location.href = "page_left.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>