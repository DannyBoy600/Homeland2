<?php
session_start();
include "db.php";
include "common.php";

$db = db_connect();

$pid = $_SESSION["sess_pageID"];
$mid = $_SESSION["sess_homepage_menuID"];
$cid = $_SESSION["sess_companyID"];

$name = com_getReqParamStr("txt_name");
$comment = com_getReqParamStr("txt_comment");
$picture = com_getReqParamStr("hid_picture");

$sql = "
INSERT PageComment (CommentID,PageID,MenuID,CompanyID,Body,Picture,IsVisible,InsDate,InsBy) VALUES 
(NULL,{$pid},{$mid},{$cid},'{$comment}','{$picture}',1,now(),'{$name}')";
$res = mysql_query ($sql);
$commentID = mysql_insert_id();
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);

// handle possible picture

// make sure company folder exists
$path = "./docs/company_{$cid}";
if (!file_exists($path)) mkdir($path);

// upload file
$path = "./docs/company_{$cid}/page_{$pid}";
if (!file_exists($path)) mkdir($path);

$filename = $_FILES['fil_comment']['tmp_name'];
$msg = "";
if ($filename != "")
{  
  $errorNo = $_FILES['fil_comment']['error'];
  if ($errorNo != 0)
    $msg = "Fel vid inläsning av bilden";
  
  if ($msg == "")
  {
    $filename = $_FILES['fil_comment']['tmp_name'];
    if (!file_exists ($filename))
      $msg = "Kunde inte hitta filen {$filename}";
  }
  
  if ($msg == "")
  {
    $origfilename = $_FILES['fil_comment']['name'];
    $fileType = $_FILES['fil_comment']['type'];
    $fileSize = $_FILES['fil_comment']['size'];
    
    if ($fileType != "image/jpg" && $fileType != "image/jpeg" && $fileType != "image/pjpeg")
      $msg = "Fel filtyp, skall vara jpg!";
      
    if ($fileSize > 2048000)
      $msg = "För stor fil, max storlek 2MB!";
  }
        
  if ($msg == "")
  {
  	$newFilename = "{$path}/comment_{$commentID}.jpg";
    if (!move_uploaded_file ($filename, $newFilename))
      $msg = "Kunde inte spara bilden!";
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
  self.location.href = "page.php?pageID=" + <?php echo $pid?>;
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>