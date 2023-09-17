<?php
include "head.php";
include "../db.php";
include "../common.php";

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$db = db_connect();

$msg = "";
$errorNo = 0;

$i=0;
while (isset($_POST["txt_name_{$i}"]) && $msg == "" && $errorNo == 0)
{
	$curShow = com_getReqParamChk("chk_show_{$i}"); 
	$curDel = com_getReqParamChk("chk_del_{$i}"); 
  $curName = com_getReqParamStr("txt_name_{$i}");
  $curRole = com_getReqParamStr("txt_role_{$i}");
  $curEmail = com_getReqParamStr("txt_email_{$i}");
  $curPhone = com_getReqParamStr("txt_phone_{$i}");
  $curMobile = com_getReqParamStr("txt_mobile_{$i}");
  $curUn = com_getReqParamStr("txt_un_{$i}");
  $curPw = com_getReqParamStr("txt_pw_{$i}");
  $curInfo = com_getReqParamStr("txt_info_{$i}");
  $curContactID = com_getReqParamInt("hid_id_{$i}");
  $sql = "";
  if ($curContactID > 0)
  {
  	if ($curDel == 1)
  	{
  		$sql = "DELETE FROM PageContact WHERE ContactID = {$curContactID}";
  		$res = mysql_query ($sql);
      $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  	}
    else
    {
    	if ($curUn != "")
    	{
    	  $sql = "SELECT ContactID FROM PageContact WHERE ContactID <> {$curContactID} AND UserName = '{$curUn}' LIMIT 1";
        $res = mysql_query ($sql);
        if ($row = mysql_fetch_row($res)) 
	        $msg = "Annan person har detta användarnamn!";
	    }
	    if ($msg == "")
	    {
        $sql = "UPDATE PageContact SET Name='{$curName}',Role='{$curRole}',Email='{$curEmail}',Phone='{$curPhone}',Mobile='{$curMobile}',UserName='{$curUn}',PassWord='{$curPw}',Info='{$curInfo}',IsVisible={$curShow} WHERE ContactID = {$curContactID}";
        $res = mysql_query ($sql);
        $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
      }
    }
  }
  else if ($curName != "")
  {
  	if ($curUn != "")
    {
    	$sql = "SELECT ContactID FROM PageContact WHERE UserName = '{$curUn}' LIMIT 1";
      $res = mysql_query ($sql);
      if ($row = mysql_fetch_row($res)) 
	      $msg = "Annan person har detta användarnamn!";
	  }
	  if ($msg == "")
	  {
      $sql = "
INSERT PageContact (ContactID,PageID,MenuID,CompanyID,Name,Role,Email,Phone,Mobile,JPG,UserName,PassWord,Info,IsVisible,InsDate,InsBy) VALUES 
(NULL,{$pageID},{$menuID},{$cid},'{$curName}','{$curRole}','{$curEmail}','{$curPhone}','{$curMobile}','','{$curUn}','{$curPw}','{$curInfo}',{$curShow},now(),'{$logger}')";
      $res = mysql_query ($sql);
      $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    }
  }
  $i++;
}

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
var errorNo = <?php echo $errorNo?>;
var msg = "<?php echo $msg?>";
function onLoad()
{
	if (errorNo != 0) alert("Fel vid spara!");
	if (msg != "") alert(msg);
  //parent.location.href = "page_main.php?pageID=<?php echo $pageID?>&kind=contacts";
  parent.left.location.href = "page_left.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()">
</body></html>