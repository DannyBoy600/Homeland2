<?php
include "head.php";
include "../db.php";
include "../common.php";

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$db = db_connect();
  
$i=0;
while (isset($_POST["txt_name_{$i}"]))
{
	$curShow = com_getReqParamChk("chk_show_{$i}"); 
	$curDel = com_getReqParamChk("chk_del_{$i}"); 
  $curName = com_getReqParamStr("txt_name_{$i}");
  $curUrl = com_getReqParamStr("txt_url_{$i}");
  $curDescr = com_getReqParamStr("txt_descr_{$i}");
  $curLinkID = com_getReqParamInt("hid_id_{$i}");
  $sql = "";
  if ($curLinkID > 0)
  {
  	if ($curDel == 1)
  	{
  		$sql = "DELETE FROM PageLink WHERE LinkID = {$curLinkID}";
  	}
    else
    {
      $sql = "UPDATE PageLink SET Name='{$curName}',URL='{$curUrl}',Descr='{$curDescr}',IsVisible={$curShow} WHERE LinkID = {$curLinkID}";
    }
  }
  else if ($curName != "")
  {
    $sql = "
INSERT PageLink (LinkID,PageID,MenuID,CompanyID,Name,URL,Descr,IsVisible,InsDate,InsBy) VALUES 
(NULL,{$pageID},{$menuID},{$cid},'{$curName}','{$curUrl}','{$curDescr}',{$curShow},now(),'{$logger}')";
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
  //parent.location.href = "page_main.php?pageID=<?php echo $pageID?>&kind=links";
  parent.left.location.href = "page_left.php";
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>