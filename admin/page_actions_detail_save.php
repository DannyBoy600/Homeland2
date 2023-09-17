<?php
include "head.php";
include "../db.php";
include "../common.php";

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$db = db_connect();

$errorNo = 0;

$actionID = com_getReqParamInt("hid_actionID");
$no = com_getReqParamInt("hid_no"); 
$name = com_getReqParamStr("txt_name"); 
$descr = com_getReqParamStr("txt_descr"); 
$prio = com_getReqParamInt("sel_prio");
$reporter = com_getReqParamStr("txt_reporter");
$action = com_getReqParamStr("txt_action");
$resp = com_getReqParamStr("txt_responsible");
$readyDate = com_getReqParamStr("txt_ready_date");
if ($readyDate == "") $readyDate = "1900-01-01";
$finished = com_getReqParamInt("rdo_finished");

if ($actionID > 0)
{
	$sql = "
UPDATE PageAction SET 
No = {$no},
Name = '{$name}',
Descr = '{$descr}',
Prio = '{$prio}',
ReporterName = '{$reporter}',
Actions = '{$action}',
ReadyDate = '{$readyDate}',
Responsible = '{$resp}',
Finished = '{$finished}',
UpdDate = now(),
UpdBy = '{$logger}'
WHERE ActionID = {$actionID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}
else
{
	$no = 1;
	$sql = "SELECT IFNULL(MAX(No),0) AS MaxNo FROM PageAction WHERE PageID = {$pageID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_object($res))
    $no = $row->MaxNo + 1;
    
  $sql = "
INSERT PageAction (ActionID,PageID,MenuID,CompanyID,No,Name,Descr,Prio,ReporterName,Actions,ReadyDate,Responsible,Finished,IsVisible,InsDate,InsBy) VALUES 
(NULL,{$pageID},{$menuID},{$cid},{$no},'{$name}','{$descr}','{$prio}','{$reporter}','{$action}','{$readyDate}','{$resp}','{$finished}',1,now(),'{$logger}')";
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
  parent.parent.parent.left.location.href = "page_left.php";
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>