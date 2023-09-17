<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$errorNo = 0;

$name = trim(com_getReqParamStr("txt_name"));
$footer = trim(com_getReqParamStr("txt_footer"));

if ($name != "")
{
  $sql = "
UPDATE Company SET 
Name = '{$name}',
Footer = '{$footer}'
WHERE CompanyID = {$cid}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
function onLoad()
{
  loadFrame(parent,"company_main.php");
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>