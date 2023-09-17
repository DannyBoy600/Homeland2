<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$errorNo = 0;

$i = 0;
while (isset($_POST["hid_userID_{$i}"]))
{
	$curUserID = com_getReqParamInt("hid_userID_{$i}");
	$curDel = com_getReqParamChk("chk_del_{$i}");
	$curFirstName = trim(com_getReqParamStr("txt_first_name_{$i}"));
	$curLastName = trim(com_getReqParamStr("txt_last_name_{$i}"));
	$curUserName = trim(com_getReqParamStr("txt_user_name_{$i}"));
	$curPassword = trim(com_getReqParamStr("txt_password_{$i}"));
  
  if ($errorNo == 0)
  {
  	$sql = "";
    if ($curDel == 1)
    {
  	  if ($curUserID > 0)
  	  {
  		  $sql = "DELETE FROM User WHERE UserID = {$curUserID}";
  		}
    }
    else
    {
      if ($curUserID == 0 && ($curFirstName != "" || $curLastName != "") )
      {
        $sql = "
INSERT User(UserID,CompanyID,FirstName,LastName,UserName,PassWord) 
VALUES (NULL,{$cid},'{$curFirstName}','{$curLastName}','{$curUserName}','{$curPassword}')";
      }
      else if ($curUserID > 0)
      {
        $sql = "
UPDATE User SET 
FirstName = '{$curFirstName}',
LastName = '{$curLastName}',
UserName = '{$curUserName}',
PassWord = '{$curPassword}'
WHERE UserID = {$curUserID}";
      }
    }
    if ($sql != "")
    {
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
function onLoad()
{
  loadFrame(parent,"users_main.php");
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>