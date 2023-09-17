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

$ids = com_getReqParamStr("hid_ids");
$items = explode(";",$ids);

for ($i=0;$i<count($items);$i++)
{
	$curPageID = $items[$i];
	$curOrderNo = $i + 1;
	$sql = "UPDATE Page SET OrderNo = {$curOrderNo} WHERE MenuID = {$menuID} AND PageID = {$curPageID}";
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
  parent.header_sub.location.href = "pages_header_sub.php?menuID=<?php echo $menuID?>";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>