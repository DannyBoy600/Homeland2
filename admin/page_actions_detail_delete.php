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

$actionID = com_getReqParamInt("hid_actionID");

if ($actionID > 0)
{
	$sql = "DELETE FROM PageAction WHERE ActionID = {$actionID}";
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
	if (errorNo != 0) alert("Fel vid radera!");
  parent.parent.parent.left.location.href = "page_left.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>