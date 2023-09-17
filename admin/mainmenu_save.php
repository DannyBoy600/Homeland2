<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];

$i=0;
while (isset($_POST["txt_name_{$i}"]))
{
	$curID = com_getReqParamInt("hid_id_{$i}"); 
	$curDel = com_getReqParamChk("chk_del_{$i}"); 
	$curIsVisible = com_getReqParamChk("chk_show_{$i}"); 
	$curIsProtected = com_getReqParamChk("chk_protected_{$i}"); 
  $curName = com_getReqParamStr("txt_name_{$i}");
  $curOrder = com_getReqParamStr("txt_order_{$i}");
  $sql = "";
  if ($curID > 0)
  {
  	if ($curDel == 1)
  	{
  		$sql = "DELETE FROM Menu WHERE MenuID = {$curID}";
  	}
    else
    {
      $sql = "UPDATE Menu SET Name='{$curName}', OrderNo={$curOrder}, IsVisible={$curIsVisible}, IsProtected={$curIsProtected} WHERE MenuID = {$curID}";
    }
    
  }
  else if ($curName != "")
  {
    $sql = "INSERT Menu (MenuID,CompanyID,Name,OrderNo,IsVisible,IsProtected) VALUES (NULL,{$cid},'{$curName}',{$curOrder},{$curIsVisible},{$curIsProtected})";
  }
  if ($sql != "")
  {
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
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
  parent.location.href = "mainmenu_main.php";
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>