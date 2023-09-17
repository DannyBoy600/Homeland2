<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$body = com_getReqParamStr("hid_body");

$errorNo = 0;

// fix stupid blank line problem
//$body = str_replace("<div>&nbsp;</div>","<div>&nbsp;<br></div>",$body);
//$body = str_replace("<DIV>&nbsp;</DIV>","<DIV>&nbsp;<br></DIV>",$body);

$cnt = 0;
$sql = "SELECT COUNT(*) as Cnt FROM PageText WHERE PageID = {$pageID}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  $cnt = $row->Cnt;
  
if ($cnt == 0)
{
	$sql = "
INSERT PageText (PageID,MenuID,CompanyID,Body,IsVisible,InsDate,InsBy) 
VALUES ({$pageID},{$menuID},{$cid},'{$body}',1,now(),'{$logger}')"; 
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}
else if ($cnt > 0)
{
  $sql = "UPDATE PageText SET Body = '{$body}',UpdDate = now(),UpdBy = '{$logger}' WHERE PageID = {$pageID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
var errorNo = <?php echo $errorNo?>;
function onLoad()
{
	if (errorNo != 0) alert("Fel vid spara!");
  //parent.location.href = "page_main.php?pageID=" + "<?php echo $pageID?>&kind=text";
  parent.left.location.href = "page_left.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>