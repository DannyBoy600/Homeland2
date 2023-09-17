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
while (isset($_REQUEST["hid_id_{$i}"]))
{
	$id = com_getReqParamInt("hid_id_{$i}");
	$chk = com_getReqParamChk("chk_del_{$i}");
	if ($chk == 1)
	{
		$sql = "DELETE FROM PageComment WHERE CommentID = {$id}";
	  $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    
    // unlink
    $fullName = "../docs/company_{$cid}/page_{$pageID}/comment_{$id}.jpg";
    if (file_exists ($fullName)) unlink($fullName);
	}
  		  
	$i++;
}
?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
function onLoad()
{
  self.location.href = "page_comments.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>