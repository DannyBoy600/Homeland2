<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$errorNo = 0;

$menuHeader = com_getReqParamStr("txt_menu_header");
$subject = com_getReqParamStr("txt_subject");
$isVisible = com_getReqParamChk("chk_show");
$isProtected = com_getReqParamChk("chk_protected");
$allowComments = com_getReqParamChk("chk_allow_comments");

if ($pageID == 0)
{
	$orderNo = 1;
	$sql = "SELECT COUNT(*) AS CNT FROM Page WHERE MenuID = {$menuID}";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_object($res)) 
    $orderNo = $row->CNT + 1;
    
	$sql = "
INSERT Page (PageID,MenuID,CompanyID,PageDate,MenuHeader,Subject,OrderNo,IsVisible,IsProtected,AllowComments,InsDate,InsBy) 
VALUES (NULL,{$menuID},{$cid},now(),'{$menuHeader}','{$subject}',{$orderNo},{$isVisible},{$isProtected},{$allowComments},now(),'{$logger}')";
  $res = mysql_query($sql);
  if (mysql_errno() == 0) $pageID = mysql_insert_id();
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($pageID > 0) $_SESSION["sess_pageID"] = $pageID;
}
else
{
  $sql = "
UPDATE Page SET 
MenuHeader = '{$menuHeader}',
Subject = '{$subject}',
IsVisible = '{$isVisible}',
IsProtected = '{$isProtected}',
AllowComments = {$allowComments},
UpdDate = now(),
UpdBy = '{$logger}' 
WHERE PageID = {$pageID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}
?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
var errorNo = <?php echo $errorNo?>;
var pageID = "<?php echo $pageID?>";
var menuID = "<?php echo $menuID?>";
function onLoad()
{
	if (errorNo != 0) alert("Fel vid spara!");
  //parent.parent.header_sub.location.href = "pages_header_sub.php?pageID=" + pageID + "&menuID=" + menuID + "&kind=common";
  parent.left.location.href = "page_left.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>