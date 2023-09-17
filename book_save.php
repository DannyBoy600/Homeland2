<?php
session_start();
include "db.php";
include "common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$pageID = $_SESSION["sess_pageID"];
$yearNo = $_SESSION["sess_book_yearNo"];

for ($i=1;$i<53;$i++)
{
	$curCellID = com_getReqParamInt("hid_cellID_{$i}");
  $curIsDirty = com_getReqParamInt("hid_dirty_{$i}");
  $curState = com_getReqParamInt("hid_state_{$i}");
  $curContactID = com_getReqParamInt("hid_contactID_{$i}");
  if ($curIsDirty == 1)
  {
    $sql = "
UPDATE PageBookCell SET
ContactID = {$curContactID},
State = {$curState}
WHERE CellID = {$curCellID}";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  }
}

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
function onLoad()
{
  self.location.href = "book_center.php?yearNo=" + "<?php echo $yearNo?>";
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>